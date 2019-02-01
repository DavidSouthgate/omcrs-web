<?php

class LoginTypeAny implements LoginType
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $config, $mysqli = null) {
        $user = new User();
        $user->setUsername($username);
        $user->setGivenName("Joe");
        $user->setSurname("Bloggs");
        $user->setEmail("joebloggs@example.com");

        if(substr($username, 0, 5) == "teach") {
            $user->setIsSessionCreator(true);
        }
        elseif(substr($username, 0, 5) == "admin") {
            $user->setIsAdmin(true);
            $user->setIsSessionCreator(true);
        }

        return $user;
    }
}