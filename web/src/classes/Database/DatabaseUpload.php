<?php

class DatabaseUpload
{

    /**
     * @param string $filename
     * @param mysqli $mysqli
     * @return bool|null
     */
    public static function insert($filename, $mysqli) {
        $filename = Database::safe($filename, $mysqli);

        // Run query to insert item into uploads table
        $sql = "INSERT INTO `omcrs_uploads` (`filename`)
                VALUES ('$filename')";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;

        return $mysqli->insert_id;
    }

    /**
     * @param int $id
     * @param mysqli $mysqli
     * @return string
     */
    public static function load($id, $mysqli) {
        $id = Database::safe($id, $mysqli);

        // Run query to load using session question ID
        $sql = "SELECT *
                FROM `omcrs_uploads` as u
                WHERE u.`ID` = $id";
        $result = $mysqli->query($sql);

        // If error, return null
        if(!$result) return null;
        if($result->num_rows == 0) return null;

        $row = $result->fetch_assoc();

        return $row["filename"];
    }
}