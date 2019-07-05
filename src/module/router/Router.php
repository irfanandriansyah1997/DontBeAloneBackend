<?php
namespace DontBeAlone\module\router;

use DontBeAlone\module\request\Request;
use DontBeAlone\controller\indexController;

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
            $request->action = $explode_url[1];
            $request->params = array_slice($explode_url, 2);
        }
    }

    static public function registerController() {
        return [
            'index' => indexController::class
        ];
    }
}
