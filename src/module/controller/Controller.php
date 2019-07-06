<?php
namespace DontBeAlone\module\controller;

use DontBeAlone\module\database\MySQL;

abstract class Controller
{
    private $database;

    function __construct(MySQL $database) {
        $this->database = $database;
    }

    abstract public function behaviour();

    public function getDatabase(): MySQL {
        return $this->database;
    }
}
