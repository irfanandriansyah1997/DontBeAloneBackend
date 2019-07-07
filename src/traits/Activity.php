<?php
namespace DontBeAlone\traits;

use DontBeAlone\module\form\Form;

trait Activity {
    public function action_insert() {
        if (isset($_POST['activity_type']) && isset($_POST['username'])) {
            if (count($this->getUser($_POST['username'])) > 0) {
                $field = [
                    ['key' => 'id_activity', 'format' => '%d'],
                    ['key' => 'activity_name', 'format' => '%s'],
                    ['key' => 'activity_type', 'format' => '%d'],
                    ['key' => 'price', 'format' => '%d'],
                    ['key' => 'description', 'format' => '%s'],
                    ['key' => 'lat', 'format' => '%d'],
                    ['key' => 'lng', 'format' => '%d'],
                    ['key' => 'address', 'format' => '%s']
                ];
                $form = (new Form($field))
                    ->setSource(
                        array_merge(
                            $_POST,
                            array(
                                'id_activity' => $this->getIdActivity($_POST['username'])
                            )
                        )
                    )->removeFieldNull()
                    ->removeSpecialChar()
                    ->result('insert');
                
                $insertQuery = $this->getDatabase()->insert(
                    't_activity',
                    $form['value'],
                    $form['format']
                );
    
                if ($insertQuery) {
                    $this->insertActivityUser(
                        $_POST['username'],
                        $form['value']['id_activity'],
                        'admin',
                        '1'
                    );

                    return json_encode([
                        "data" => $form['value'],
                        "message" => "Activity success saved to db",
                        "success" => true
                    ]);
                }

                return json_encode([
                    "data" => null,
                    "message" => "An error has occured, please contact the administrator",
                    "success" => false
                ]);
            } else {
                return json_encode([
                    "data" => null,
                    "message" => "Username {$_POST['username']} not found",
                    "success" => false
                ]);
            }
        }

        return json_encode([
            "data" => null,
            "message" => "Activity Type / Username is not found",
            "success" => false
        ]);
    }

    public function action_update(string $id_activity) {
        if (isset($_POST['activity_type'])) {
            $field = [
                ['key' => 'activity_name', 'format' => '%s'],
                ['key' => 'activity_type', 'format' => '%d'],
                ['key' => 'price', 'format' => '%d'],
                ['key' => 'description', 'format' => '%s'],
                ['key' => 'lat', 'format' => '%d'],
                ['key' => 'lng', 'format' => '%d'],
                ['key' => 'address', 'format' => '%s']
            ];
            $form = (new Form($field))
                ->setSource($_POST)
                ->removeFieldNull()
                ->removeSpecialChar()
                ->result('insert');

            $isAvailable = $this->getActivity($id_activity);

            if(count($isAvailable) > 0) {
                $updatedQuery = $this->getDatabase()->update(
                    't_activity',
                    $form['value'],
                    $form['format'],
                    array('id_activity' => $id_activity),
                    array('%d')
                );
    
                if ($updatedQuery) {
                    return json_encode([
                        "data" => $form['value'],
                        "message" => "Activity success updated",
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
                "message" => "Oops activity is not found",
                "success" => false
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Activity Type is not found",
            "success" => false
        ]);
    }

    public function action_banned(string $id_activity) {
        $isAvailable = $this->getActivity($id_activity);

        if(count($isAvailable) > 0) {
            $bannedQuery = $this->getDatabase()->update(
                't_activity',
                array('is_banned' => $isAvailable[0]->is_banned === 0 ? 1 : 0),
                array('%d'),
                array('id_activity' => $id_activity),
                array('%d')
            );
    
            if ($bannedQuery) {
                $message = $isAvailable[0]->is_banned === 0 ? 'banned' : 'unbanned';

                return json_encode([
                    "data" => null,
                    "message" => "Activity success {$message}",
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
            "message" => "Oops activity is not found",
            "success" => false
        ]);
    }

    private function getIdActivity(string $username) {
        return time() + crc32($username);
    }

    private function getUser(string $username) {
        return $this->getDatabase()->select(
            'SELECT *
            FROM t_user
            WHERE username = ?
            LIMIT 0,1',
            array($username),
            array('%s')
        );
    }

    private function getActivity(string $id_activity) {
        return $this->getDatabase()->select(
            'SELECT *
            FROM t_activity
            WHERE id_activity = ?
            LIMIT 0,1',
            array($id_activity),
            array('%d')
        );
    }
}
