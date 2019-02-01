<?php

class Api
{

    public static function outputJson($json) {
        header('Content-Type: application/json');
        echo $json;
    }

    public static function output($output = []) {
        self::outputJson(json_encode($output));
    }

    /**
     * Checks whether a parameter was passed
     * @param null $parameter
     * @return bool True if parameter exists
     */
    public static function checkParameter($parameter=null) {

        // Check if parameter has not been given
        if (!$_REQUEST[$parameter]) {
            $output = [];

            $output["error"]["code"] = "parameterNotGiven";
            $output["error"]["message"] = "A required parameter '$parameter' was not given";

            Api::output($output);
            die();
        }

        return $_REQUEST[$parameter];
    }

    /**
     * Checks api key
     * @param string $key
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkApiKey($key, $mysqli) {

        // If session has user, return the user from session
        if(isset($_SESSION["omcrs_user"])) {
            return new User($_SESSION["omcrs_user"]);
        }

        return DatabaseApiKey::checkApiKey($key, $mysqli);
    }
}