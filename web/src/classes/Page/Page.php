<?php

class Page
{

    /**
     * @param $config
     * @return bool|User
     */
    static public function ensureUserLoggedIn($config) {

        $user = Login::checkUserLoggedIn();

        // If the user is not logged in
        if(!$user) {

            // Redirect them to the login page
            header("Location: " . $config["baseUrl"] . "login/");
            die();
        }

        return $user;
    }

    /**
     * @param User $user
     * @param $config
     */
    static public function ensureUserIsSessionCreator($user, $config) {

        // If user can not create sessions, forward them home
        if(!$user->isSessionCreator() && !$user->isAdmin()) {
            PageError::error403();
            die();
        }
    }
}