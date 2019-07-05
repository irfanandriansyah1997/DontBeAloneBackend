<?php
namespace DontBeAlone\module\database\interfaces;

interface DatabaseConfigInterface {
    public function setUsername(string $username);
    public function setHost(string $host);
    public function setPort(int $port);
    public function setDatabase(string $database);
    public function setPassword(string $password);
    public function getHost(): string;
};
