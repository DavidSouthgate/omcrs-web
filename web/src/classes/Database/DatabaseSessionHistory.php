<?php

class DatabaseSessionHistory
{

    /**
     * Insert an item into session history
     * @param User $user
     * @param Session $session
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($user, $session, $mysqli) {
        $userID = Database::safe($user->getId(), $mysqli);
        $sessionID = Database::safe($session->getSessionID(), $mysqli);

        // Run query to insert
        $sql = "INSERT INTO `omcrs_sessionHistory` (`userID`, `sessionID`, `time`)
                VALUES ('$userID', '$sessionID', '".time()."')";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        // Return the ID of the row in session history
        return $mysqli->insert_id;
    }
}