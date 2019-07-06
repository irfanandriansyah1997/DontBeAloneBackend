<?php
namespace DontBeAlone\module\auth;

use DontBeAlone\module\controller\Controller;

abstract class Auth extends Controller {
    private function getUserBySocialMedia(array $field, string $social_media) {
        return $this->getDatabase()->select(
            "SELECT *
            FROM t_user
            WHERE email = ? and {$social_media} = ?
            LIMIT 0,1",
            array($field['email'], $field[$social_media]),
            array('%s', '%s')
        );
    }

    protected function getUserByField(string $key, string $value) {
        return $this->getDatabase()->select(
            "SELECT *
            FROM t_user
            WHERE {$key} = ?
            LIMIT 0,1",
            array($value),
            array('%s')
        );
    }

    public function loginBySocialMedia(string $social_media, array $field) {
        $existingUser = $this->getUserBySocialMedia($field, $social_media);

        if (count($existingUser) > 0) {
            return json_encode([
                "data" => $existingUser[0],
                "message" => "Success Login",
                "success" => true
            ]);
        } else {
            $valueClause = array();
            $valueClause[$social_media] = $field[$social_media];
            $updatedUser = $this->getDatabase()->update(
                't_user',
                $valueClause,
                array('%s'),
                array('email' => $field['email']),
                array('%s')
            );

            if ($updatedUser) {
                $existingUser = $this->getUserBySocialMedia($field, $social_media);
                return json_encode([
                    "data" => $existingUser[0],
                    "message" => "Success Login",
                    "success" => true
                ]);
            }
        }

        return json_encode([
            "data" => null,
            "message" => "An error has occured, please contact the administrator",
            "success" => false
        ]);
    }

    abstract public function action_login_fb();

    abstract public function action_login_tw();

    abstract public function action_login_gp();
}
