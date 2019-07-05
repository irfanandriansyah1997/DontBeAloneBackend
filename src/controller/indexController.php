<?php
namespace DontBeAlone\controller;

use DontBeAlone\module\controller\Controller;

class indexController extends Controller{

    public function index() {
        $a = $this->getDatabase()->query('SELECT * FROM appdb.users');
        print_r($a);
    }

    public function get(string $id) {
        $a = $this->getDatabase()->select(
            'SELECT * FROM appdb.users WHERE id = ? and username = ?',
            array($id, 'ihan'),
            array('%d', '%s')
        );
        print_r($a);
    }

    public function create() {
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

    public function edit(string $id) {
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

    public function delete(string $id) {
        $a = $this->getDatabase()->delete('users', 'id', $id);

        print_r($a ? 'sukses' : 'gagal');
    }
}
