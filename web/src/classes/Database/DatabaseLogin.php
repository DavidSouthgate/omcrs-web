<?php

class DatabaseLogin
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $mysqli) {

        // Make username and password databas safe
        $username = Database::safe($username, $mysqli);
        $password = Database::safe($password, $mysqli);

        // Run query to select user from database
        $sql = "SELECT *
                FROM `omcrs_user` as u
                WHERE u.`username` = '$username'";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // If the username does not exist, return null
        if($result->num_rows<=0)
            return null;

        // Fetch row from database query result
        $row = $result->fetch_assoc();

        // If user inputted password does not match database
        if(!password_verify($password, $row["password"])) {
            return null;
        }

        // TODO: Rename columns
        $row["isSessionCreator"] = $row["isSessionCreatorOverride"];
        $row["isAdmin"] = $row["isAdminOverride"];
        $row["id"] = $row["userID"];

        // Create a new user
        $user = new User($row);

        // Return new user
        return $user;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $givenName
     * @param string $surname
     * @param string $email
     * @param mysqli $mysqli
     * @return bool
     */
    public static function register($username, $password, $givenName, $surname, $email, $mysqli) {

        // Hash the password
        $passwordHashed = (string)password_hash($password, PASSWORD_BCRYPT);

        // Make variables database safe
        $username = Database::safe($username, $mysqli);
        $passwordHashed = Database::safe($passwordHashed, $mysqli);
        $givenName = Database::safe($givenName, $mysqli);
        $surname = Database::safe($surname, $mysqli);
        $email = Database::safe($email, $mysqli);

        // Run query to add user to users table
        $sql = "INSERT INTO `omcrs_user` (`username`, `password`, `givenName`, `surname`, `email`)
                VALUES ('$username', '$passwordHashed', '$givenName', '$surname', '$email');";
        $result = $mysqli->query($sql);

        // If duplicate key error, username already exists
        if($mysqli->errno==1062) {
            return 100;
        }

        // If query was not successful
        if(!$result) return null;

        return true;
    }

    /**
     * @param string $username
     * @param string $password
     * @param mysqli $mysqli
     * @return bool
     */
    public static function updatePassword($username, $password, $mysqli) {

        // Hash the password
        $passwordHashed = (string)password_hash($password, PASSWORD_BCRYPT);

        $username = Database::safe($username, $mysqli);
        $passwordHashed = Database::safe($passwordHashed, $mysqli);

        $sql = "UPDATE `omcrs_user` as u
                SET `password` = '$passwordHashed'
                WHERE u.`username` = '$username'";
        $result = $mysqli->query($sql);

        return !!$result;
    }
}