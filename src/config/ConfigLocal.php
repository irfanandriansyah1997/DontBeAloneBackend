<?php
namespace DontBeAlone\config;

class ConfigLocal {
    public $config = [
        'database' => [
            'username' => 'user',
            'host' => 'mysql',
            'port' => 3306,
            'database' => 'appdb',
            'password' => 'rahasia123'
        ]
    ];

    public function getConfig($key) {
        return $this->config[$key];
    }
}
