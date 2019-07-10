<?php
namespace DontBeAlone\traits;

trait ActivityType {
    public function action_get_activity_type() {
        return json_encode(
            array(
                "data" => array_map(static function($item) {
                    $item->detail = "" . $item->detail . "";
                    $item->marker = $item->marker_icon;

                    unset($item->marker_icon);

                    return $item;
                }, $this->getDatabase()->query('SELECT * FROM t_activity_type')),
                "message" => "Success Fetch Data",
                "success" => true
            )
        );
    }
}
