<?php

class Login
{

    /**
     * Checks if user is currently logged in
     * @return bool|User False if not. User object if so.
     */
    public static function checkUserLoggedIn() {

        // If user is not stored in the session
        if(!isset($_SESSION["omcrs_user"])) {
            return false;
        }

        // Return the user from the session
        return new User($_SESSION["omcrs_user"]);
    }

    /**
     * @param string $username
     * @param string $password
     * @param array  $config
     * @param mysqli $mysqli
     * @param bool   $store    Whether this login should be stored in the session if it is valid
     * @return User|bool
     */
    public static function checkLogin($username, $password, $config, $mysqli, $store = true) {

        $type = $config["login"]["type"];

        // If username is in list on manual username/password combos in config
        if(isset($config["user"]["users"]) && array_key_exists($username, $config["user"]["users"]) && $config["user"]["users"][$username] == $password) {

            // Create a new user
            $user = new User();
            $user->setGivenName("Joe");
            $user->setSurname("Bloggs");
            $user->setUsername($username);

            // Load additional details from the database
            $user = DatabaseUser::loadDetails($user, $mysqli);

            if($user === null)
                return null;
        }

        else {

            // Load given login type class
            try {
                $login = LoginTypeFactory::create($type);
            }

                // Catch exception for when login type does not exist
            catch(Exception $e) {
                return null;
            }

            if(!$username)
                return false;

            // Check login details with given login type
            $user = $login::checkLogin($username, $password, $config, $mysqli);

            // If invalid user details, return false
            if(!$user)
                return false;

            // If not the native login, load additional details from database
            if($config["login"]["type"] !== "native") {

                // Load additional details from the database
                $user = DatabaseUser::loadDetails($user, $mysqli);

                if($user === null)
                    return null;
            }
        }

        // If the config specifies this user should always be an admin
        if(isset($config["user"]["admin"]) && in_array($user->getUsername(), $config["user"]["admin"])) {

            // Set admin attributes
            $user->setIsAdmin(true);
            $user->setIsSessionCreator(true);
        }

        // If this session should be stored
        if($store) {

            // Store user details in the session
            $_SESSION["omcrs_user"] = $user->toArray();
        }

        return $user;
    }

    public static function anonymousUserCreate($nickname, $mysqli) {

        // If no nickname, just use name Guest
        if(!$nickname) {
            $givenName = "";
            $surname = "Guest";
        }

        // If no nickname, just use nickname followed by "(Guest)"
        else {
            $givenName = $nickname;
            $surname = "(Guest)";
        }

        // Create a new user
        $user = new User();
        $user->setUsername(null);
        $user->setGivenName($givenName);
        $user->setSurname("$surname");
        $user->setIsGuest(true);

        // Load additional details from the database
        $user = DatabaseUser::loadDetails($user, $mysqli);

        // Store user details in the session
        $_SESSION["omcrs_user"] = $user->toArray();

        return $user;
    }
}