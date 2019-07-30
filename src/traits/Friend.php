<?php
namespace DontBeAlone\traits;

trait Friend {
    private function getFriendById(string $user1, string $user2) {
        $relationship = $this->getDatabase()->select(
            'SELECT *
            FROM t_friend
            WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
            LIMIT 0,1',
            array($user1, $user2, $user2, $user1),
            array('%s', '%s', '%s', '%s')
        );

        return [
            'isAvailable' => count($relationship) > 0,
            'data' => $relationship
        ];
    }

    private function getAllFriend(string $username) {
        $relationship = $this->getDatabase()->select(
            'SELECT *
            FROM t_friend
            WHERE (sender = ?) OR (receiver = ?)
            ORDER BY status ASC',
            array($username, $username),
            array('%s', '%s')
        );

        return [
            'isAvailable' => count($relationship) > 0,
            'data' => $relationship
        ];
    }

    public function getUser(string $username) {
        return $this->getDatabase()->select(
            'SELECT *
            FROM t_user
            WHERE username = ?
            LIMIT 0,1',
            array($username),
            array('%s')
        );
    }
}
