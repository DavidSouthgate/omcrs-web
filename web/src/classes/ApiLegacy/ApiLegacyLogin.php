<?php

class ApiLegacyLogin
{

    /**
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function login($username, $password, $config, $mysqli) {
        $errors = [];
        $data = [];

        // Log the user in
        $user = Login::checkLogin($username, $password, $config, $mysqli);

        if($user === null) {
            die("Error 500");
        }

        // If incorrect login, output error
        if(!$user) {
            $errors[] = "Incorrect login";
            ApiLegacy::sendResponse("login", $errors, [], $config);
            die();
        }

        $data["serverInfo"]["courseIdSupported"] = "0";

        // Add all of the generic questions
        $i = 0;
        foreach(QuestionGeneric::getQuestions() as $question) {
            $data["serverInfo"]["globalQuType"][$i]["0"] = $question["nameShort"];
            $data["serverInfo"]["globalQuType"][$i]["attributes"]["id"] = -$i;
            $i++;
        }

        ApiLegacy::sendResponse("login", $errors, $data, $config);
    }
}