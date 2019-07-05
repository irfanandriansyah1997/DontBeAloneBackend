<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;

class indexController extends Controller{

    public function index() {
        print_r($this->getDatabase());
    }

    public function create() {
        echo "create";
    }

    public function edit(string $id) {
        echo "edit {$id}";
    }

    public function delete(string $id) {
        echo "delete {$id}";
    }
}
