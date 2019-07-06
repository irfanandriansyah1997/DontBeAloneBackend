<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;
use DontBeAlone\traits\ActivityType;
    
class activityController extends Controller {
    use ActivityType;

    public function behaviour() {
        return [
            'get_activity_type' => [
                'header' => [
                    'Content-Type: application/json;charset=utf-8'
                ]
            ]
        ];
    }
}
