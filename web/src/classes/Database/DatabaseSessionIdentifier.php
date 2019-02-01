<?php

class DatabaseSessionIdentifier
{

    /**
     * Loads session using the Session Identifier
     * @param int $sessionIdentifier
     * @param mysqli $mysqli
     * @return Session|null
     */
    public static function loadSession($sessionIdentifier, $mysqli) {

        // Load the session PK
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If this was an invalid session identifier, return null
        if(!$sessionID) return null;

        // Load the session from the session PK
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        if($session)
            $session->setSessionIdentifier($sessionIdentifier);

        return $session;
    }

    /**
     * Loads the session ID from a session identifier
     * @param int $sessionIdentifier
     * @param mysqli $mysqli
     * @return int|null;
     */
    public static function loadSessionID($sessionIdentifier, $mysqli) {
        $sessionIdentifier = Database::safe($sessionIdentifier, $mysqli);

        $sql = "SELECT *
                FROM `omcrs_sessionIdentifier` as si
                WHERE si.`sessionIdentifier` = $sessionIdentifier";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $row = $result->fetch_assoc();

        return intval($row["sessionID"]);
    }

    public static function delete($sessionIdentifier, $mysqli) {
        $sessionIdentifier = Database::safe($sessionIdentifier, $mysqli);

        return DatabaseSession::delete($sessionIdentifier, $mysqli);
    }

    /**
     * @param mysqli $mysqli
     * @return Session[]|null
     */
    public static function loadAllSessions($mysqli) {

        // Run SQL query to get all sessions
        $sql = "SELECT
                    si.`sessionIdentifier`,
                    u.`username` as owner,
                    s.*
                FROM
                    `omcrs_sessionIdentifier` AS si,
                    `omcrs_sessions` AS s,
                    `omcrs_user` as u
                WHERE si.`sessionID` = s.`sessionID`
                  AND s.`ownerID` = u.`userID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $output = [];

        // Loop for every row in result
        while($row = $result->fetch_assoc()) {

            // Create a new session
            $session = new Session($row);

            array_push($output, $session);
        }

        return $output;
    }
}