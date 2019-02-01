<?php

class ApiLegacySession
{

    /**
     * @param User $user
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function sessionList($user, $config, $mysqli) {
        $errors = [];
        $data = [];

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

        ApiLegacy::sendResponse("sessionlist", $errors, $data, $config);
    }

    /**
     * @param User $user
     * @param array $config
     * @param mysqli $mysqli
     */
    public static function sessionDetail($user, $config, $mysqli) {
        $errors = [];
        $data = [];

        $sessionID = $_REQUEST["id"];

        // If no session ID, create a new session
        if (!isset($sessionID)) {
            $session = new Session();
            $session->setOwner($user->getId());
        }

        // Otherwise, load existing session
        else {
            $session = DatabaseSession::loadSession($sessionID, $mysqli);

            // If the user cannot edit this session, output error
            if(!$session->checkIfUserCanEdit($user)) {
                $errors[] = "You do not have permission to modify session $sessionID.";
                ApiLegacy::sendResponse("sessiondetail", $errors, $data, $config);
                die();
            }
        }

        // If no session loaded, output error
        if(!$session) {
            $errors[] = "Session $sessionID not found.";
            ApiLegacy::sendResponse("sessiondetail", $errors, $data, $config);
            die();
        }

        // Update session details from request
        if(isset($_REQUEST["title"]))               $session->setTitle(                 $_REQUEST["title"]);
        if(isset($_REQUEST["courseIdentifier"]))    $session->setCourseID(              $_REQUEST["courseIdentifier"]);
        if(isset($_REQUEST["allowGuests"]))         $session->setAllowGuests(           $_REQUEST["allowGuests"]);
        if(isset($_REQUEST["visible"]))             $session->setOnSessionList(         $_REQUEST["visible"]);
        if(isset($_REQUEST["questionMode"]))        $session->setQuestionControlMode(   $_REQUEST["questionMode"]);
        if(isset($_REQUEST["defaultQuActiveSecs"])) $session->setDefaultTimeLimit(      $_REQUEST["defaultQuActiveSecs"]);
        if(isset($_REQUEST["allowQuReview"]))       $session->setAllowModifyAnswer(     $_REQUEST["allowQuReview"]);
        if(isset($_REQUEST["ublogRoom"]))           $session->setClassDiscussionEnabled($_REQUEST["ublogRoom"]);

        // If a new session was created, insert into database
        if(!isset($sessionID))
            $sessionID = DatabaseSession::insert($session, $mysqli);

        // Otherwise, update session in database
        else
            DatabaseSession::update($session, $mysqli);

        // Load the new session data
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        $data["sessionDetail"]["attributes"]["id"]      = $session->getSessionID();;
        $data["sessionDetail"]["title"]                 = $session->getTitle();
        $data["sessionDetail"]["courseIdentifier"]      = $session->getCourseID();
        $data["sessionDetail"]["allowGuests"]           = $session->getAllowGuests();
        $data["sessionDetail"]["visible"]               = $session->getOnSessionList();
        $data["sessionDetail"]["questionMode"]          = $session->getQuestionControlMode();
        $data["sessionDetail"]["defaultQuActiveSecs"]   = $session->getDefaultTimeLimit();
        $data["sessionDetail"]["allowQuReview"]         = $session->getAllowModifyAnswer();
        $data["sessionDetail"]["ublogRoom"]             = $session->getClassDiscussionEnabled();
        $data["sessionDetail"]["maxMessagelength"]      = -999;

        ApiLegacy::sendResponse("sessiondetail", $errors, $data, $config);
    }
}