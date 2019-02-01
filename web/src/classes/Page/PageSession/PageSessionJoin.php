<?php

class PageSessionJoin
{

    /**
     * Users cannot join a session here. Forward home
     */
    public static function join() {
        $config = Flight::get("config");
        header("Location: " . $config["baseUrl"]);
        die();
    }

    public static function submit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get the session ID
        $sessionID = $_POST["sessionID"];

        // If this session ID is not numeric check if it is an alias
        if(!is_numeric($sessionID)) {

            // Load session alias ID
            $sessionAliasID = DatabaseSessionAlias::loadSessionID($sessionID, $mysqli);

            // Set session ID if one was found from the alias
            if($sessionAliasID !== null) {
                $sessionID = $sessionAliasID;
            }
        }

        // If invalid session ID, forward home
        if(!preg_match("/^[0-9]*$/", $sessionID)) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid Session ID");
            Alert::displayAlertSession($alert);

            header("Location: " . $config["baseUrl"]);
            die();
        }

        // Forward the user to the session page
        header("Location: " . $config["baseUrl"] . "session/$sessionID/");
        die();
    }
}