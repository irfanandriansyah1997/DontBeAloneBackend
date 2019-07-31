<?php
namespace DontBeAlone\controller;

use DateTime;
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
            'search' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'update' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'update_photo' => [
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

    public function get_user(string $username) {
        return $this->getDatabase()->select(
            'SELECT *
            FROM t_user
            WHERE username = ?
            LIMIT 0,1',
            array($username),
            array('%s')
        );
    }

    public function get_user_by_keyword(string $keyword) {
        $query = "
            SELECT *
            FROM t_user
            WHERE username LIKE '%{$keyword}%'
            OR name LIKE '%{$keyword}%'
        ";

        return $this->getDatabase()->query($query);
    }

    public function action_get_user_by_username(string $username) {
        $response = $this->get_user($username);

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

    public function action_search() {
        $response = $this->get_user_by_keyword($_GET['keyword']);

        if (count($response) > 0) {
            return json_encode([
                "data" => $response,
                "message" => "Success fetch data",
                "success" => true
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Oops, username {$_GET['keyword']} is not found",
            "success" => false
        ]);
    }

    public function action_update() {
        if (isset($_POST['username'])) {
            $field = [['key' => 'email', 'format' => '%s'], ['key' => 'name', 'format' => '%s'],
                ['key' => 'phone_number', 'format' => '%s'], ['key' => 'address', 'format' => '%s'],
                ['key' => 'bio', 'format' => '%s']];
            $form = (new Form($field))
                ->setSource($_POST)
                ->removeFieldNull()
                ->removeSpecialChar()
                ->result('update');
            $response = $this->get_user($_POST['username']);
        
            if (count($response) > 0) {
                $updatedUser = $this->getDatabase()->update(
                    't_user',
                    $form['value'],
                    $form['format'],
                    array('username' => $_POST['username']),
                    array('%s')
                );
    
                if ($updatedUser) {
                    $existingUser = $this->get_user($_POST['username']);
                    return json_encode([
                        "data" => $existingUser[0],
                        "message" => "Success Updated User",
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

        return json_encode([
            "data" => null,
            "message" => "Oops, username not defined",
            "success" => false
        ]);
    }

    public function action_update_photo() {
        if (!empty($_FILES['photo']) && isset($_POST['username'])) {
            $response = $this->get_user($_POST['username']);
        
            if (count($response) > 0) {

                $upload = $this->uploadFile($_FILES['photo'], $_POST['username']);

                if ($upload) {
                    $updatedUser = $this->getDatabase()->update(
                        't_user',
                        ['photo' => $upload],
                        array('%s'),
                        array('username' => $_POST['username']),
                        array('%s')
                    );

                    if ($updatedUser) {
                        $existingUser = $this->get_user($_POST['username']);
                        return json_encode([
                            "data" => null,
                            "message" => "Success Updated Photo User",
                            "success" => true
                        ]);
                    }
                }
            }
        
            return json_encode([
                "data" => null,
                "message" => "An error has occured, please contact the administrator",
                "success" => false
            ]);
        }
    }

    private function uploadFile($files, $username) {
        $tempFile = $files['tmp_name'];
        $array = explode('.', $files['name']);
        $extension = end($array);
        $date = new DateTime('2008-09-22');
        $name = 'upload-image-'.$date->getTimestamp().'-'.$username.'.'.$extension;
        $dirUpload = "/var/www/html/public/upload/";
        $uploading = move_uploaded_file($tempFile, $dirUpload.$name);


        if ($uploading) {
            return 'http://111.92.169.32/upload/'.$name;
        } else {
            return false;
        }
    }

    public function action_update_password() {
        if (isset($_POST['username'])) {
            $field = [['key' => 'password', 'format' => '%s']];
            $source = ['password' => md5($_POST['password'])];
            $form = (new Form($field))
                ->setSource($source)
                ->removeFieldNull()
                ->result('update');
            $response = $this->get_user($_POST['username']);
        
            if (count($response) > 0) {
                $updatedUser = $this->getDatabase()->update(
                    't_user',
                    $form['value'],
                    $form['format'],
                    array('username' => $_POST['username']),
                    array('%s')
                );
    
                if ($updatedUser) {
                    $existingUser = $this->get_user($_POST['username']);
                    return json_encode([
                        "data" => null,
                        "message" => "Success Updated User",
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

        return json_encode([
            "data" => null,
            "message" => "Oops, username not defined",
            "success" => false
        ]);
    }
}
