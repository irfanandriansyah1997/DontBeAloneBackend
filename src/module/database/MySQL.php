<?php
namespace DontBeAlone\module\database;

use DontBeAlone\module\database\abstracts\DatabaseAbstract;

class MySQL extends DatabaseAbstract {
    public function setConnection(DatabaseConfig $connection_obj) {
        $config = $connection_obj->getConfig();
        $connection = new \MySQLi($connection_obj->getHost(), $config['username'], $config['password'], $config['database']) or die(mysqli_error());

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $this->connection = $connection;
    }

    public function query(string $query) {
        $db = $this->getConnection();
        $result = $db->query($query);

        return array_map(static function($item) use ($result) {
            return $result->fetch_object();
        }, array_fill(0, $result->num_rows, null));
    }

    public function select(string $query, array $data, array $format) {
        $db = $this->getConnection();
        $stmt = $db->prepare($query);
        $format = str_replace('%', '', implode('', $format));    
        array_unshift($data, $format);
        call_user_func_array( array( $stmt, 'bind_param'), $this->refValues($data));
        $stmt->execute();
        $result = $stmt->get_result(); 

        return array_map(static function($item) use ($result) {
            return $result->fetch_object();
        }, array_fill(0, $result->num_rows, null));
    }

    public function insert(string $table, array $data, array $format) {
        if ( empty( $table ) || empty( $data ) ) {
            return false;
        }
        
        $db = $this->getConnection();
        $format = str_replace('%', '', implode('', $format));
        list( $fields, $placeholders, $values ) = $this->prepQuery($data);
        array_unshift($values, $format); 
        $stmt = $db->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");
        call_user_func_array( array( $stmt, 'bind_param'), $this->refValues($values));
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        }
        
        return false;
    }

    public function update(
        string $table,
        array $data,
        array $format,
        array $where,
        array $where_format
    ) {
        if ( empty( $table ) || empty( $data ) ) {
            return false;
        }
        
        $db = $this->getConnection();
        $format = str_replace('%', '', implode('', $format));
        $where_format = str_replace('%', '', implode('', $where_format));
        $format .= $where_format;
        list( $fields, $placeholders, $values ) = $this->prepQuery($data, 'update');
        $where_clause = '';
        $where_values = [];
        $count = 0;
        
        foreach ( $where as $field => $value ) {
            if ( $count > 0 ) {
                $where_clause .= ' AND ';
            }
            
            $where_clause .= $field . '=?';
            $where_values[] = $value;
            
            $count++;
        }

        array_unshift($values, $format);
        $values = array_merge($values, $where_values);
        $stmt = $db->prepare("UPDATE {$table} SET {$placeholders} WHERE {$where_clause}");

        call_user_func_array( array( $stmt, 'bind_param'), $this->refValues($values));
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            return true;
        }
        
        return false;
    }

    public function delete($table, $key, $id) {
        $db = $this->getConnection();
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$key} = ?");
        $stmt->bind_param('d', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        }
    }

    private function prepQuery($data, $type='insert') {
        $fields = '';
        $placeholders = '';
        $values = array();
        
        foreach ( $data as $field => $value ) {
            $fields .= "{$field},";
            $values[] = $value;
            
            if ($type == 'update') {
                $placeholders .= $field . '=?,';
            } else {
                $placeholders .= '?,';
            }
            
        }
        
        $fields = substr($fields, 0, -1);
        $placeholders = substr($placeholders, 0, -1);
        
        return array( $fields, $placeholders, $values );
    }

    private function refValues($array) {
        $refs = array();
        foreach ($array as $key => $value) {
            $refs[$key] = &$array[$key]; 
        }
        return $refs; 
    }
}
