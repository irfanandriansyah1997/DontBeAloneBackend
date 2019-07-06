<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
    
class activityController extends Controller {
    public function behaviour() {
        return [
            'index' => [
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

    public function action_index(string $username) {
    }

    public function action_update() {
    }

    public function action_update_password() {
    }
}
