<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\module\form\Form;
    
class userController extends Controller {
    public function behaviour() {
        return [
            'get_user_by_username' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'update' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'update_password' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }

    public function get_user_by_username(string $username) {
        $response = $this->getDatabase()->select(
            'SELECT *
            FROM t_user
            WHERE username = ?
            LIMIT 0,1',
            array($username),
            array('%s')
        );

        if (count($response) > 0) {
            return json_encode([
                "data" => $response[0],
                "message" => "Success fetch data",
                "success" => true
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Oops, username {$username} is not found",
            "success" => false
        ]);
    }

    public function update() {
        if (isset($_POST['username'])) {
            $field = [['key' => 'email', 'format' => '%s'], ['key' => 'name', 'format' => '%s'],
                ['key' => 'phone_number', 'format' => '%s'], ['key' => 'address', 'format' => '%s'],
                ['key' => 'bio', 'format' => '%s']];

            $form = (new Form($field))
                ->setSource($_POST)
                ->removeFieldNull()
                ->removeSpecialChar()
                ->result();


            return json_encode([
                "data" => null,
                "message" => "Oops, username not defined",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Oops, username not defined",
            "success" => false
        ]);
    }

    public function update_password() {
    }
}
