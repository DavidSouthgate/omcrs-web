<?php

class DatabaseApiKey
{

    /**
     * Generate new API key
     * @return string
     */
    private static function generateApiKey() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    /**
     * Creates new API key
     * @param User $user
     * @param mysqli $mysqli
     * @return string|null
     */
    public static function newApiKey($user, $mysqli) {
        $i = 0;

        // Make username database safe
        $username = Database::safe($user->getUsername(), $mysqli);

        // Generate new api key
        $key = self::generateApiKey();

        // While the API key has been used
        while(self::checkApiKey($key, $mysqli)) {

            // Generate new api key
            $key = self::generateApiKey();
        }

        // Key creation time
        $created = time();

        $isSessionCreator   = $user->isSessionCreator() ? "1"   : "0";
        $isAdmin            = $user->isAdmin()          ? "1"   : "0";

        // Run SQL Query
        $sql = "INSERT INTO `omcrs_apiKey` (`key`, `created`, `username`, `isSessionCreator`, `isAdmin`)
                VALUES ('$key', $created, '$username', '$isSessionCreator', '$isAdmin');";
        $result = $mysqli->query($sql);

        return ($result ? $key : null);
    }

    /**
     * Checks api key
     * @param string $key
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkApiKey($key="", $mysqli) {

        // Escape database key
        $key = Database::safe($key, $mysqli);

        // Run database query
        $sql = "SELECT `username`, `key`, `created`, `isSessionCreator`, `isAdmin`
                FROM `omcrs_apiKey`
                WHERE `omcrs_apiKey`.`key` = '$key'";
        $result = $mysqli->query($sql);

        // If query did not return a result, i.e. the key does not exist
        if($result->num_rows == 0)
            return false;

        $row = $result->fetch_assoc();

        // If key has expired, return false
        if($row["created"] <= 0)
            return null;

        $user = new User();
        $user->setUsername($row["username"]);
        $user->setIsSessionCreator($row["isSessionCreator"]=="1"?true:false);
        $user->setIsAdmin($row["isAdmin"]=="1"?true:false);

        $user = DatabaseUser::loadDetails($user, $mysqli);

        return $user;
    }

    /**
     * @param string $key
     * @param mysqli $mysqli
     * @return bool
     */
    public static function apiKeyExpire($key, $mysqli) {

        // Escape database key
        $key = Database::safe($key, $mysqli);

        // Run database query
        $sql = "UPDATE `omcrs_apiKey`
                SET `created`=0
                WHERE `omcrs_apiKey`.`key`='$key'";
        $result = $mysqli->query($sql);

        return !!$result;
    }
}
