<?php

class PageHome
{

    public static function home() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessions = DatabaseSession::loadUserHistoryAndEditableSessions($user->getId(), $mysqli);

        if($sessions === null) PageError::error500("Could not load user's sessions in ".__FILE__." on line ".__LINE__);

        $data["sessions"] = $sessions;

        $data["user"] = $user;
        echo $templates->render("home", $data);
    }
}