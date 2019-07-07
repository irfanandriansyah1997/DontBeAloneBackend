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
                'type' => $_GET['type'] ?? null
            ];

            $query = "SELECT * FROM (
                SELECT
                    *,
                    (
                        {$param['radius']} * acos(
                            cos(radians({$param['lat']}))
                            * cos(radians(lat))
                            * cos(radians(lng) - radians({$param['lng']}))
                            + sin(radians({$param['lat']}))
                            * sin(radians(lat))
                        )
                    ) AS distance
                FROM t_activity
            ) AS distances
            WHERE distance < {$param['distance']}";
            $query .= " AND activity_type = {$param['type']}";
            $query .= " ORDER BY distance";
            $query .= " LIMIT 30";

            return json_encode([
                "data" => $this->getDatabase()->query($query),
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
