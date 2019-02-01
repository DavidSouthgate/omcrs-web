<?php

class ApiLegacySessionQuestion
{
    public static function getqids($user, $config, $mysqli) {
        $errors = [];
        $data = [];

        $session = DatabaseSession::loadSession($_REQUEST["id"], $mysqli);
        $questions = DatabaseSessionQuestion::loadSessionQuestions($_REQUEST["id"], $mysqli);

        if(!$questions || !$session) {
            $errors[] = "Invalid session";
            ApiLegacy::sendResponse($_REQUEST["action"], $errors, $data, $config);
            die();
        }

        // If the user cannot edit this session, output error
        if(!$session->checkIfUserCanEdit($user)) {
            $errors[] = "You do not have permission to modify session ".$_REQUEST["id"].".";
            ApiLegacy::sendResponse($_REQUEST["action"], $errors, $data, $config);
            die();
        }

        foreach($questions["questions"] as $question) {
            $data["quid"][] = $question->getSessionQuestionID();
        }

        ApiLegacy::sendResponse($_REQUEST["action"], $errors, $data, $config);
    }

    public static function quinfo($user, $config, $mysqli) {
        $errors = [];
        $data = [];

        // If a key is not in the session, make a new one
        if(!isset($_SESSION["omcrs_legacy_key"])) {
            $_SESSION["omcrs_legacy_key"] = DatabaseApiKey::newApiKey($user, $mysqli);
        }

        $data["questionResponseInfo"]["attributes"]["questiontype"] = "MCQ A-D";
        $data["questionResponseInfo"]["attributes"]["id"] = "8042";
        $data["questionResponseInfo"]["attributes"]["questionClass"] = "basicQuestion";
        $data["questionResponseInfo"]["attributes"]["displayURL"] = "services.php?action=display&key=" . $_SESSION["omcrs_legacy_key"] . "&id=" . $_REQUEST["id"];
        $data["questionResponseInfo"]["activeUsers"] = 0;
        $data["questionResponseInfo"]["totalUsers"] = 1;
        $data["questionResponseInfo"]["responseCount"] = 5;
        $data["questionResponseInfo"]["timeGone"] = 181076;

        /*
        // Load sessions from database
        $sessions = DatabaseSession::loadUserSessions($user->getId(), $mysqli);

        // Output each session
        foreach($sessions as $session) {
            $sessionInfo["ownerID"] = $session->getOwner();;
            $sessionInfo["title"] = $session->getTitle();
            $sessionInfo["created"] = date("Y-m-d H:i", $session->getCreated());
            $sessionInfo["attributes"]["id"] = $session->getSessionID();
            $data["sessionInfo"][] = $sessionInfo;
        }
        */

        ApiLegacy::sendResponse($_REQUEST["action"], $errors, $data, $config);
    }
}