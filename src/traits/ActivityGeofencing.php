<?php
namespace DontBeAlone\traits;

trait ActivityGeofencing {
    public function action_get_activity() {
        if (isset($_GET['lat']) && isset($_GET['lng'])) {
            $param = [
                'lat' => $_GET['lat'],
                'lng' => $_GET['lng'],
                'radius' => self::RADIUS,
                'distance' => $_GET['distance'] ?? self::MAX_DISTANCE,
                'type' => $_GET['type'] ?? '-1',
                'keyword' => $_GET['keyword'] ?? ''
            ];

            $query = "SELECT * FROM (
                SELECT
                    t.*,
                    ty.type as 'activity_type_type',
                    ty.detail as 'activity_type_detail',
                    ty.icon as 'activity_type_icon',
                    ty.marker_icon as 'activity_type_marker',
                    u.username as 'activity_user_username',
                    u.name as 'activity_user_name',
                    u.photo as 'activity_user_photo',
                    (
                        {$param['radius']} * acos(
                            cos(radians({$param['lat']}))
                            * cos(radians(t.lat))
                            * cos(radians(t.lng) - radians({$param['lng']}))
                            + sin(radians({$param['lat']}))
                            * sin(radians(t.lat))
                        )
                    ) AS distance
                FROM t_activity t
                INNER JOIN t_activity_type ty ON ty.id_activity_type = t.activity_type
                INNER JOIN t_activity_user tu ON tu.id_activity = t.id_activity AND tu.level_user = 'admin'
                INNER JOIN t_user u ON u.username = tu.username
            ) AS distances
            WHERE distance < {$param['distance']}  AND is_banned = 0";
            $query .= $param['type'] == '-1' ? "" : " AND activity_type = {$param['type']}";
            $query .= $param['keyword'] == '' ? "" : " AND activity_name LIKE '%{$param['keyword']}%'";
            $query .= " ORDER BY distance";
            $query .= " LIMIT 30";

            return json_encode([
                "data" => array_map(static function($item) {
                    return [
                        "id_activity" => $item->id_activity,
                        "activity_name" => $item->activity_name,
                        "activity_type" => [
                            'id_activity_type' => $item->activity_type,
                            "type" => $item->activity_type_type,
                            "detail" => $item->activity_type_detail,
                            'icon' => $item->activity_type_icon,
                            'marker' => $item->activity_type_marker
                        ],
                        "activity_user" => [
                            'username' => $item->activity_user_username,
                            'name' => $item->activity_user_name,
                            'photo' => $item->activity_user_photo
                        ],
                        "datetime" => $item->datetime,
                        "price" => $item->price,
                        "description" => $item->description,
                        "lat" => $item->lat,
                        "lng" => $item->lng,
                        "address" => $item->address,
                        "is_banned" => $item->is_banned,
                        "distance" => $item->distance,
                    ];
                }, $this->getDatabase()->query($query)),
                "message" => "Success fetch data",
                "success" => true
            ]);
        }

        return json_encode([
            "data" => null,
            "message" => "Param lat lng is not defined",
            "success" => false
        ]);
    }

    
}
