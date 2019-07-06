<?php
namespace DontBeAlone\traits;

trait ActivityType {
    public function action_get_activity_type() {
        return json_encode(
            array_map(static function($item) {
                $item->detail = json_decode($item->detail);
                return $item;
            }, $this->getDatabase()->query('SELECT * FROM t_activity_type'))
        );
    }
}
