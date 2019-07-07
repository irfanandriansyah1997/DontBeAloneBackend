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
        $this->setBehaviour($controller->behaviour()[$this->request->action]);

        echo call_user_func_array([$controller, 'action_' . $this->request->action], $this->request->params);
    }

    private function setBehaviour($behaviour) {
        $header = $behaviour['header'];

        foreach($header as $value) {
            header($value);
        }
    }

    public function loadController() {
        $controller = Router::registerController();
        return new $controller[$this->request->controller]($this->database);
    }
}
