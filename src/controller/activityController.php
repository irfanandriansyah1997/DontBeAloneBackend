<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\traits\Activity;
use DontBeAlone\traits\ActivityGeofencing;
use DontBeAlone\traits\ActivityType;
use DontBeAlone\traits\ActivityUser;
    
class activityController extends Controller {
    use Activity;
    use ActivityGeofencing;
    use ActivityType;
    use ActivityUser;

    const STATUS = [
        '0' => 'Pending',
        '1' => 'Accepted',
        '2' => 'Rejected'
    ];
    const RADIUS = 6373;
    const MAX_DISTANCE = 20;

    public function behaviour() {
        return [
            'get_activity' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_activity_type_trending' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_activity_by_user' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
            'get_activity_by_id' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ],
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
