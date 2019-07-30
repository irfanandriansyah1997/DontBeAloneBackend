<?php
namespace DontBeAlone\module\router;

use DontBeAlone\module\request\Request;
use DontBeAlone\controller\activityController;
use DontBeAlone\controller\indexController;
use DontBeAlone\controller\authController;
use DontBeAlone\controller\friendController;
use DontBeAlone\controller\userController;

class Router {
    static public function parse($url, $request) {
        if ($url == "/") {
            $request->controller = "index";
            $request->action = "index";
            $request->params = [];
        } else {
            $explode_url = explode('/', $url);
            $explode_url = array_slice($explode_url, 1);
            $request->controller = $explode_url[0];
            $request->action = $explode_url[1] ?? 'index';
            $request->action = explode('?', $request->action)[0];
            $request->params = array_slice($explode_url, 2);
        }
    }

    static public function registerController() {
        return [
            'activity' => activityController::class,
            'index' => indexController::class,
            'auth' => authController::class,
            'user' => userController::class,
            'friend' => friendController::class
        ];
    }
}
