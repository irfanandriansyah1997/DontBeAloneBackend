<?php
namespace DontBeAlone\traits;

use DontBeAlone\module\form\Form;

trait Activity {
    public function action_insert() {
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
            
            $insertQuery = $this->getDatabase()->insert(
                't_activity',
                $form['value'],
                $form['format']
            );

            if ($insertQuery) {
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
        }

        return json_encode([
            "data" => null,
            "message" => "Activity Type is not found",
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

            $isAvailable = $this->get_activity($id_activity);

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
        $isAvailable = $this->get_activity($id_activity);

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

    private function get_activity(string $id_activity) {
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
