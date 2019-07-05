<?php
namespace DontBeAlone\controller;

// use DontBeAlone\module\controller\Controller;

class indexController {
    public function index() {
        echo "index";
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
