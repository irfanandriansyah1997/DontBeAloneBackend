<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\traits\Friend;

class friendController extends Controller{
    use Friend;

    public function behaviour() {
        return [
            'add_friend' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'confirm_friend' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'reject_friend' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_friend' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
        ];
    }

    public function action_add_friend() {
        $field = [
            'sender' => $_POST['sender'],
            'receiver' => $_POST['receiver']
        ];

        $relationship = $this->getFriendById($field['sender'], $field['receiver']);
        
        if (!$relationship['isAvailable']) {
            $insert = $this->getDatabase()->insert(
                't_friend',
                $field,
                array('%s', '%s')
            );

            if ($insert) {
                return json_encode([
                    "data" => null,
                    "message" => "Success Insert",
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

    public function action_confirm_friend() {
        $field = [
            'sender' => $_POST['sender'],
            'receiver' => $_POST['receiver']
        ];

        $relationship = $this->getFriendById($field['sender'], $field['receiver']);
        
        if ($relationship['isAvailable']) {
            $update = $a = $this->getDatabase()->update(
                't_friend',
                [
                    'status' => '1',
                ],
                [
                    '%s'
                ],
                $field,
                [
                    '%s', '%s' 
                ]
            );

            if ($update) {
                return json_encode([
                    "data" => null,
                    "message" => "Success Confirm Friend",
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

    public function action_reject_friend() {
        $field = [
            'sender' => $_POST['sender'],
            'receiver' => $_POST['receiver']
        ];

        $relationship = $this->getFriendById($field['sender'], $field['receiver']);
        
        if ($relationship['isAvailable']) {
            $update = $a = $this->getDatabase()->deleteFriend(
                't_friend',
                'sender', 'receiver', $_POST['sender'], $_POST['receiver']
            );

            if ($update) {
                return json_encode([
                    "data" => null,
                    "message" => "Success Reject Friend",
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

    public function action_get_friend() {
        if (isset($_GET['username'])) {
            $field = [
                'username' => $_GET['username']
            ];

            $user = $this->getAllFriend($field['username']);
            $temp = $this;
            
            return json_encode([
                "data" => array_map(
                    static function($item) use ($temp) {
                        return [
                            'sender' => $temp->getUser($item->sender),
                            'receiver' => $temp->getUser($item->receiver),
                            'status' => $item->status
                        ];
                    },
                    $user['data']
                ),
                "message" => "Success fetch get friend",
                "success" => true
            ]);

        }

        return json_encode([
            "data" => false,
            "message" => "Param username is not defined",
            "success" => false
        ]);
    }
}
