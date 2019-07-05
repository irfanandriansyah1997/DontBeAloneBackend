<?php
namespace DontBeAlone;

use DontBeAlone\module\app\abstracts\AppAbstract;
use DontBeAlone\module\database\MySQL;
use DontBeAlone\module\database\DatabaseConfig;
use DontBeAlone\module\database\abstracts\DatabaseAbstract;

class App extends AppAbstract {
    public function run() {
        echo "run";
    }

    public function setupDatabaseConfig(): DatabaseAbstract {
        $config = new DatabaseConfig(
            'user',
            'mysql',
            3306,
            'appdb',
            'rahasia123'
        );
        
        $database = new MySQL();
        $database->setConnection($config);

        return $database;
    }

    public function setupLoggerConfig() {

    }
}