<?php
namespace DontBeAlone\module\database\interfaces;

use DontBeAlone\module\database\DatabaseConfig;

interface DatabaseInterface {
    public function setConnection(DatabaseConfig $connection_obj);
    public function getConnection();
};
