<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 25/02/18
 * Time: 23:51
 */

class LoginTypeNative implements LoginType
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $config, $mysqli = null) {

        // Check login using database
        $user = DatabaseLogin::checkLogin($username, $password, $mysqli);

        // If error logging in, return null
        if(!$user)
            return null;

        return $user;
    }
}