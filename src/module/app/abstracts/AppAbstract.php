<?php
namespace DontBeAlone\module\app\abstracts;

use DontBeAlone\config\ConfigLocal;
use DontBeAlone\module\app\interfaces\AppInterface;
use DontBeAlone\module\database\abstracts\DatabaseAbstract;

abstract class AppAbstract implements AppInterface {
    protected $database;
    protected $logger;
    protected $config;

    function __construct() {
        $this->config = new ConfigLocal();
        $this->setDatabase($this->setupDatabaseConfig());
    }

    public function setDatabase(DatabaseAbstract $database) {
        $this->database = $database;
    }

    public function setLogger($template) {
        echo "set logger";
    }
};
