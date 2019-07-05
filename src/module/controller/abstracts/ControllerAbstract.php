<?php
namespace DontBeAlone\module\controller\interfaces;

use DontBeAlone\module\controller\interfaces\ControllerInterface;

abstract class ControllerAbstract implements ControllerInterface {
    protected $router;

    public function setRouter(string $router) {
        $this->router = $router;
    }
}
