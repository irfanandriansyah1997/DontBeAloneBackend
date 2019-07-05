<?php
namespace DontBeAlone\module\database\abstracts;

use DontBeAlone\module\database\DatabaseConfig;
use DontBeAlone\module\database\interfaces\DatabaseInterface;

abstract class DatabaseAbstract implements DatabaseInterface
{
    protected $connection;

    abstract public function setConnection(DatabaseConfig $connection_obj);

    public function getConnection() {
        return $this->connection;
    }
}
