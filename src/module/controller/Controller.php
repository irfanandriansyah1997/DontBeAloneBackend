<?php
namespace DontBeAlone\module\controller;

class Controller
{
    private $database;

    function __construct($database) {
        $this->database = $database;
    }

    public function getDatabase() {
        return $this->database;
    }

    private function secureInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    public function secureForm($form) {
        foreach ($form as $key => $value) {
            $form[$key] = $this->secure_input($value);
        }
    }
}
