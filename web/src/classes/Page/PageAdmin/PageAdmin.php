<?php

class PageAdmin
{
    public static function admin() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // If the user is not an admin, 403
        if(!$user->isAdmin()) {
            PageError::error403();
        }

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load all sessions
        $sessions = DatabaseSessionIdentifier::loadAllSessions($mysqli);

        if(!$sessions) PageError::error500("Could not load sessions in ".__FILE__." on line ".__LINE__);

        // Load all users
        $users = DatabaseUser::loadAllUsers($mysqli);

        if(!$users) PageError::error500("Could not load users in ".__FILE__." on line ".__LINE__);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Admin");

        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["sessions"] = $sessions;
        $data["users"] = $users;
        echo $templates->render("admin/admin", $data);
    }
}