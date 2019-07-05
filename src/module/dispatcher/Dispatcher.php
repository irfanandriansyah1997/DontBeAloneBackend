<?php
namespace DontBeAlone\module\dispatcher;

use DontBeAlone\module\request\Request;
use DontBeAlone\module\router\Router;

class Dispatcher {
    private $request;
    private $database;

    function __construct($param) {
        $this->database = $param['database'];
    }

    public function dispatch() {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);

        $controller = $this->loadController();

        call_user_func_array([$controller, $this->request->action], $this->request->params);
    }

    public function loadController() {
        $controller = Router::registerController();
        return new $controller[$this->request->controller]($this->database);
    }
}
