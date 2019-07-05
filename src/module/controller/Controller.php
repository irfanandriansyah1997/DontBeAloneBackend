<?php
namespace DontBeAlone\module\controller;

use DontBeAlone\module\controller\interfaces\ControllerAbstract;

class Controller
{
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
