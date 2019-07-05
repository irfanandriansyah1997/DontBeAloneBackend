<?php
namespace DontBeAlone\module\app\interfaces;

use DontBeAlone\module\database\abstracts\DatabaseAbstract;

interface AppInterface {
    public function setupDatabaseConfig();
    public function setupLoggerConfig();
};
