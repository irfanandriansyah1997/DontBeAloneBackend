<?php
namespace DontBeAlone\traits;

trait ActivityUser {
    public function action_join_activity() {
        if (isset($_POST['id_activity']) && isset($_POST['username'])) {
            if (count($this->getUser($_POST['username'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Username {$_POST['username']} is not found",
                    "success" => false
                ]);
            } else if (count($this->getActivity($_POST['id_activity'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Oops activity is not found",
                    "success" => false
                ]);
            } else if($this->isUserAvailableInActivity($_POST['id_activity'], $_POST['username'])['isAvailable']) {
                return json_encode([
                    "data" => null,
                    "message" => "{$_POST['username']} is already join",
                    "success" => false
                ]);
            }

            $result = $this->insertActivityUser($_POST['username'], $_POST['id_activity']);

            if ($result) {
                return json_encode([
                    "data" => null,
                    "message" => "{$_POST['username']} success join activity",
                    "success" => true
                ]);
            }

            return json_encode([
                "data" => null,
                "message" => "An error has occured, please contact the administrator",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "ID Activity / Username is not found",
            "success" => false
        ]);
    }

    public function action_leave_activity() {
        if (isset($_POST['id_activity']) && isset($_POST['username'])) {
            if (count($this->getUser($_POST['username'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Username {$_POST['username']} is not found",
                    "success" => false
                ]);
            } else if (count($this->getActivity($_POST['id_activity'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Oops activity is not found",
                    "success" => false
                ]);
            } else if($this->isUserAvailableInActivity($_POST['id_activity'], $_POST['username'])['isAvailable']) {
                $data = $this->isUserAvailableInActivity($_POST['id_activity'], $_POST['username'])['data'];
                $delete = $this->getDatabase()->delete('t_activity_user', 'id_activity_user', $data[0]->id_activity_user);

                if ($delete) {
                    return json_encode([
                        "data" => null,
                        "message" => "{$_POST['username']} leave activity",
                        "success" => true
                    ]);
                }
            }

            return json_encode([
                "data" => null,
                "message" => "{$_POST['username']} is not join",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "ID Activity / Username is not found",
            "success" => false
        ]);
    }

    public function action_grant_activity() {
        if (isset($_POST['id_activity']) && isset($_POST['username'])) {
            if (count($this->getUser($_POST['username'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Username {$_POST['username']} is not found",
                    "success" => false
                ]);
            } else if (count($this->getActivity($_POST['id_activity'])) === 0) {
                return json_encode([
                    "data" => null,
                    "message" => "Oops activity is not found",
                    "success" => false
                ]);
            } else if($this->isUserAvailableInActivity($_POST['id_activity'], $_POST['username'])['isAvailable']) {
                $setGrantAccount = $this->getDatabase()->update(
                    't_activity_user',
                    array('status' => $_POST['status']),
                    array('%s'),
                    array(
                        'username' => $_POST['username'],
                        'id_activity' => $_POST['id_activity'],
                    ),
                    array('%s', '%s')
                );

                if ($setGrantAccount) {
                    return json_encode([
                        "data" => null,
                        "message" => "Account {$_POST['username']} success update status in {$_POST['id_activity']} activity",
                        "success" => true
                    ]);
                }

                return json_encode([
                    "data" => null,
                    "message" => "An error has occured, please contact the administrator",
                    "success" => false
                ]);
            }

            return json_encode([
                "data" => null,
                "message" => "{$_POST['username']} not joined this activity",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "ID Activity / Username is not found",
            "success" => false
        ]);
    }

    public function action_get_user_role(string $id_activity, string $username) {
        if (count($this->getUser($username)) === 0) {
            return json_encode([
                "data" => null,
                "message" => "Username {$username} is not found",
                "success" => false
            ]);
        } else if (count($this->getActivity($id_activity)) === 0) {
            return json_encode([
                "data" => null,
                "message" => "Oops activity is not found",
                "success" => false
            ]);
        }

        $available = $this->isUserAvailableInActivity($id_activity, $username);

        if($available['isAvailable']) {
            $response = $available['data'][0];
            $response->status = self::STATUS[$response->status];
            
            return json_encode([
                "data" => $response,
                "message" => "Activity User Founded",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "{$username} not joined this activity",
            "success" => false
        ]);
    }

    private function insertActivityUser(
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

    private function isUserAvailableInActivity(string $id_activity, string $username) {
        $user = $this->getDatabase()->select(
            'SELECT *
            FROM t_activity_user
            WHERE id_activity = ? and username = ?
            LIMIT 0,1',
            array($id_activity, $username),
            array('%d', '%s')
        );

        return [
            'isAvailable' => count($user) > 0,
            'data' => $user
        ];
    }
}
