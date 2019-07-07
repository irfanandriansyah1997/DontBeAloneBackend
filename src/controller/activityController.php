<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\traits\Activity;
use DontBeAlone\traits\ActivityType;
    
class activityController extends Controller {
    use Activity;
    use ActivityType;

    public function behaviour() {
        return [
            'insert' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'update' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'banned' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_activity_type' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }
}
