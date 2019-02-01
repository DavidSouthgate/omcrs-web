<?php

class DatabaseSessionQuestionScreenshot
{

    /**
     * @param string $sessionQuestionID
     * @param string $screenshotID
     * @param mysqli $mysqli
     * @return bool|null
     */
    public static function insert($sessionQuestionID, $screenshotID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);
        $screenshotID = Database::safe($screenshotID, $mysqli);

        // Run query to insert
        $sql = "INSERT INTO `omcrs_sessionQuestionScreenshot` (`sessionQuestionID`, `screenshotID`)
                VALUES ('$sessionQuestionID', '$screenshotID') ";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        return $mysqli->insert_id;
    }

    /**
     * @param string $sessionQuestionID
     * @param string $filename
     * @param mysqli $mysqli
     * @return bool|null
     */
    public static function insertFilename($filename, $sessionQuestionID, $mysqli) {

        // Insert new item into uploads table
        $screenshotID = DatabaseUpload::insert($filename, $mysqli);

        // If error, return null
        if(!$screenshotID) return null;

        // Insert item into sessionQuestionScreenshot table
        return self::insert($sessionQuestionID, $screenshotID, $mysqli);
    }

    /**
     * @param string $sessionQuestionID
     * @param mysqli $mysqli
     * @return string
     */
    public static function loadSessionQuestionID($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        // Run query to load using session question ID
        $sql = "SELECT *
                FROM `omcrs_sessionQuestionScreenshot` as sqs
                WHERE sqs.`sessionQuestionID` = $sessionQuestionID
                ORDER BY ID DESC
                LIMIT 1";

        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;
        if($result->num_rows == 0) return null;

        $row = $result->fetch_assoc();

        // Load filename from screenshot ID
        return DatabaseUpload::load($row["screenshotID"], $mysqli);
    }
}