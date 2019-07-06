<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
    
class authController extends Controller{
    public function behaviour() {
        return [
            'login' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }

    public function login() {
        $field = [
            'username' => $_POST['username'],
            'password' => md5($_POST['password'])
        ];
        $response = $this->getDatabase()->select(
            'SELECT *
            FROM t_user
            WHERE username = ? and password = ?
            LIMIT 0,1',
            array($field['username'], $field['password']),
            array('%s', '%s')
        );

        if (count($response) > 0) {
            return json_encode([
                "data" => $response[0],
                "message" => "Success Login",
                "success" => true
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Incorrect username / password, please check it and try again",
            "success" => false
        ]);
    }
}
