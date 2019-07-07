<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\traits\Activity;
use DontBeAlone\traits\ActivityType;
use DontBeAlone\traits\ActivityUser;
    
class activityController extends Controller {
    use Activity;
    use ActivityType;
    use ActivityUser;

    const STATUS = [
        '0' => 'Pending',
        '1' => 'Accepted',
        '2' => 'Reject'
    ];

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
            ],
            'join_activity' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'grant_activity' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_user_role' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }
}
