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

    private function secureInput($data): string {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    public function secureForm($form): void {
        foreach ($form as $key => $value) {
            $form[$key] = $this->secure_input($value);
        }
    }
}
