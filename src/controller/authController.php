<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\auth\Auth;
    
class authController extends Auth {
    public function behaviour() {
        return [
            'login' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'login_fb' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'login_tw' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'login_gp' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'register' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }

    public function action_login() {
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

    public function action_login_fb() {
        $field = [
            'email' => $_POST['email'],
            'fb_id' => $_POST['fb_id']
        ];

        return $this->loginBySocialMedia('fb_id', $field);
    }

    public function action_login_tw() {
        $field = [
            'email' => $_POST['email'],
            'tw_id' => $_POST['tw_id']
        ];

        return $this->loginBySocialMedia('tw_id', $field);
    }

    public function action_login_gp() {
        $field = [
            'email' => $_POST['email'],
            'gp_id' => $_POST['gp_id']
        ];

        return $this->loginBySocialMedia('gp_id', $field);
    }

    public function action_register() {
        $field = [
            'username' => $_POST['username'],
            'password' => md5($_POST['password']),
            'email' => $_POST['email'],
            'name' => $_POST['name'],
            'fb_id' => $_POST['fb_id'],
            'tw_id' => $_POST['tw_id'],
            'gp_id' => $_POST['gp_id'],
            'photo' => $_POST['photo']
        ];

        $checkByUsername = $this->getUserByField('username', $field['username']);
        $checkByEmail = $this->getUserByField('email', $field['email']);

        if (count($checkByUsername) > 0) {
            return json_encode([
                "data" => null,
                "message" => "Username has been taken",
                "success" => false
            ]);
        } else if (count($checkByEmail) > 0) {
            return json_encode([
                "data" => null,
                "message" => "Email has been registered, please login",
                "success" => false
            ]);
        } else {
            $insert = $this->getDatabase()->insert(
                't_user',
                $field,
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );

            if ($insert) {
                $user = $this->getUserByField('email', $field['email']);
                return json_encode([
                    "data" => $user[0],
                    "message" => "Success Login",
                    "success" => true
                ]);
            }
        }

        return json_encode([
            "data" => null,
            "message" => "An error has occured, please contact the administrator",
            "success" => false
        ]);
    }
}
