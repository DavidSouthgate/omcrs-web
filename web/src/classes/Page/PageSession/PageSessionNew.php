<?php

class PageSessionNew
{

    // aka new session
    public static function add() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Ensure user is allowed to create sessions
        Page::ensureUserIsSessionCreator($user, $config);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem("New");

        $data["session"] = new Session();
        $data["user"] = $user;
        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("session/edit/properties", $data);
    }

    public static function submit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Ensure user is allowed to create sessions
        Page::ensureUserIsSessionCreator($user, $config);

        // Setup session from submitted data
        $session = new Session($_POST);

        $session->setOwner($user->getId());

        // Load new users
        foreach ($_POST as $key => $value) {

            preg_match("/(user-)(\w*[0-9]\w*)/", $key, $matches);

            if($matches) {

                // Get the user index from the regex matches
                $index = $matches[2];

                // If there is an index associated with this user, store it
                if(isset($_POST["user-" . $index])) {
                    $username = $_POST["user-" . $index];
                    // Add a new user
                    $session->addAdditionalUser($username);
                }

            }
        }

        $sessionID = DatabaseSession::insert($session, $mysqli);

        if($sessionID === null) {
            PageError::error500("Could not create session");
            die();
        }

        header("Location: "  .$config["baseUrl"] . "session/$sessionID/edit/");
        die();
    }
}