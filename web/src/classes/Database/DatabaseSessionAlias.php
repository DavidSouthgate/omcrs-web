<?php

class DatabaseSessionAlias
{

    /**
     * Get the session ID if an alias exists
     * @param string $session
     * @param mysqli $mysqli
     * @return int
     */
    public static function loadSessionID($session, $mysqli) {
        $session = Database::safe($session, $mysqli);

        // Run query to get session ID from session aliases
        $sql = "SELECT sa.`sessionID`
                FROM `omcrs_sessionAlias` as sa
                WHERE sa.`alias` = '$session'";
        $result = $mysqli->query($sql);

        // If error running query or there are no results, return null
        if($result === false || $result->num_rows == 0) {
            return null;
        }

        // Fetch the database row
        $row = $result->fetch_assoc();

        return $row["sessionID"];
    }
}