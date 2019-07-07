<?php
namespace DontBeAlone\traits;

trait ActivityUser {
    private function insert_activity_user(
        string $username,
        string $id_activity,
        string $level_user = 'user',
        string $status = '0'
    ) {
        $form = [
            'value' => [
                'username' => $username,
                'id_activity' => $id_activity,
                'level_user' => $level_user,
                'status' => $status,
            ],
            'format' => ['%s', '%s', '%s', '%s']
        ];

        return $this->getDatabase()->insert(
            't_activity_user',
            $form['value'],
            $form['format']
        );
    }
}
