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
}
