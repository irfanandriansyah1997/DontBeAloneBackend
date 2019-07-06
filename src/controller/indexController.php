<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;

class indexController extends Controller{
    public function behaviour() {
        return [
            'index' => ['header' => []],
            'get' => ['header' => []],
            'create' => ['header' => []],
            'edit' => ['header' => []],
            'delete' => ['header' => []]
        ];
    }

    public function action_index() {
        $a = $this->getDatabase()->query('SELECT * FROM appdb.users');
        print_r($a);
    }

    public function action_get(string $id) {
        $a = $this->getDatabase()->select(
            'SELECT * FROM appdb.users WHERE id = ?',
            array($id),
            array('%d')
        );
        print_r($a);
    }

    public function action_create() {
        $a = $this->getDatabase()->insert(
            'users',
            [
                'username' => 'ihan',
                'password' => 'ihan gembok',
                'email' => 'bogang gembok 123',
                'api_token' => '1471897918518198017991981',
                'created_at' => '2019-06-18 13:09:48',
                'updated_at' => '2019-06-18 13:09:48'
            ],
            [
                '%s', '%s', '%s', '%s', '%s', '%s'
            ]
        );

        print_r($a ? 'sukses' : 'gagal');
    }

    public function action_edit(string $id) {
        $a = $this->getDatabase()->update(
            'users',
            [
                'username' => 'ihan gembok',
                'password' => 'ihan gembok',
            ],
            [
                '%s', '%s'
            ],
            [
                'id' => $id
            ],
            [
                '%d'
            ]
        );

        print_r($a ? 'sukses' : 'gagal');
    }

    public function action_delete(string $id) {
        $a = $this->getDatabase()->delete('users', 'id', $id);

        print_r($a ? 'sukses' : 'gagal');
    }
}
