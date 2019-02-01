<?php

class LoginTypeLdapCsq extends LoginTypeLdap
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $config = [], $mysqli = null) {

        // A login starting with _ will be logged in as a student
        $username2 = $username;
        if(substr($username, 0, 1) == "_") {
            $username2 = substr($username, 1, 999);
        }

        // Perform LDAP login
        $user = parent::checkLogin($username2, $password, $config);

        $user->setUsername($username);

        // If login was valid
        if($user) {

            // Force all CSQ members to have admin and teacher permissions
            $username = strtolower($username);
            switch($username) {
                case "2262645c";    // Chase
                case "2205747i";    // Hristo
                case "2141683m";    // Michael
                case "2198207s";    // David
                case "2036909a";    // Nora
                    $user->setIsSessionCreator(true);
                    $user->setIsAdmin(true);
                    break;
            }
        }

        return $user;
    }
}