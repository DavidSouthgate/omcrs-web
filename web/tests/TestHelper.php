<?php

class TestHelper
{

    /**
     * Connect to the database
     * @param array $config
     * @return mysqli
     */
    public static function databaseConnect($config) {
        return new mysqli($config["database"]["host"], $config["database"]["username"], $config["database"]["password"], $config["database"]["name"]);
    }

    /**
     * @param array $config
     * @param mysqli $mysqli
     * @return bool|User
     */
    public static function userSessionCreator($config, $mysqli) {
        return Login::checkLogin("teacher", "orangemonkey", $config, $mysqli);
    }
}
