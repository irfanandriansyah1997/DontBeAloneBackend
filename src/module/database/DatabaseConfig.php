<?php
namespace DontBeAlone\module\database;

use DontBeAlone\module\database\interfaces\DatabaseConfigInterface;

class DatabaseConfig implements DatabaseConfigInterface
{
    private $username;
    private $host;
    private $port;
    private $database;
    private $password;

    function __construct(
        string $username,
        string $host,
        int $port,
        string $database,
        string $password
    ) {
        $this->setUsername($username);
        $this->setHost($host);
        $this->setPort($port);
        $this->setDatabase($database);
        $this->setPassword($password);
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setHost(string $host) {
        $this->host = $host;
    }

    public function setPort(int $port) {
        $this->port = $port;
    }

    public function setDatabase(string $database) {
        $this->database = $database;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getHost(): string {
        $host = $this->host;
        $port = $this->port;

        return "{$host}:{$port}";
    }

    public function getConfig(): array {
        return [
            'username' => $this->username,
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'password' => $this->password
        ];
    }
}
