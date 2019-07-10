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
                        "data" => null,
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
                        "data" => null,
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

    public function action_get_activity_by_id(string $id_activity) {
        $query = "SELECT
            t.id_activity as 'activity_id',
            t.*,
            ty.type as 'activity_type_type',
            ty.detail as 'activity_type_detail',
            ty.icon as 'activity_type_icon',
            ty.marker_icon as 'activity_type_marker_icon'
            FROM t_activity t
            INNER JOIN t_activity_type ty ON ty.id_activity_type = t.activity_type
            WHERE t.id_activity = {$id_activity}
            GROUP BY activity_id
        ";

        return json_encode([
            "data" => array_map(function($item) use ($id_activity) {
                return [
                    "id_activity" => $item->id_activity,
                    "activity_name" => $item->activity_name,
                    "activity_type" => [
                        'id_activity_type' => $item->activity_type,
                        "type" => $item->activity_type_type,
                        "detail" => $item->activity_type_detail,
                        'icon' => $item->activity_type_icon,
                        'marker' => $item->activity_type_marker_icon
                    ],
                    "activity_member" => $this->getActivityUser($id_activity),
                    "datetime" => $item->datetime,
                    "price" => $item->price,
                    "description" => $item->description,
                    "lat" => $item->lat,
                    "lng" => $item->lng,
                    "address" => $item->address,
                    "is_banned" => $item->is_banned
                ];
            }, $this->getDatabase()->query($query)),
            "message" => "Success fetch data",
            "success" => true
        ]);
    }

    public function action_get_activity_type_trending() {
        $query = "SELECT
            ty.*,
            COUNT(*) as 'jml'
            FROM appdb.t_activity t
            INNER JOIN appdb.t_activity_type ty ON ty.id_activity_type = t.activity_type
            GROUP BY t.activity_type
            ORDER BY 'jml' DESC
            LIMIT 6
        ";

        return json_encode([
            "data" => array_map(function($item) use ($id_activity) {
                return [
                    'id_activity_type' => $item->id_activity_type,
                    "type" => $item->type,
                    "detail" => "" . $item->detail . "",
                    'icon' => $item->icon,
                    'marker' => $item->activity_type_marker_icon
                ];
            }, $this->getDatabase()->query($query)),
            "message" => "Success fetch data",
            "success" => true
        ]);
    }

    public function getActivityUser(string $id_activity) {
        return array_map(
            function($item) {
                return [
                    'level_user' => $item->level_user,
                    'status' => self::STATUS[$item->status],
                    'username' => $item->username
                ];
            },
            $this->getDatabase()->select(
                'SELECT * FROM appdb.t_activity_user WHERE id_activity = ?',
                array($id_activity),
                array('%s')
            )
        );
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
