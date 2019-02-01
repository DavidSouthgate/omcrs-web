<?php

interface LoginType
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $config, $mysqli = null);
}