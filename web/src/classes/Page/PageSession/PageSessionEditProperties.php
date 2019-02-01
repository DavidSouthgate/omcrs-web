<?php

class PageSessionEditProperties
{
    public static function properties($sessionIdentifier) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load session details
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        // If the session is invalid or the user cannot edit this page, forward home
        if($session === null || !$session->checkIfUserCanEdit($user)) {
            header("Location: "  . $config["baseUrl"]);
            die();
        }
        $arr = [];
        $users = $session->getAdditionalUsers();
        foreach ($users as $u){
            array_push($arr, DatabaseUser::loadDetailsFromUsername($u, $mysqli));
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)"  . " Edit", $config["baseUrl"]."session/$sessionIdentifier/edit");
        $breadcrumbs->addItem("Properties");

        //$data = array_merge($data, $session->toArray());

        $data["session"] = $session;
        $data["additionalUsersCsv"] = $session->getAdditionalUsersCsv();
        $data["user"] = $user;
        $data["additionalUsers"] = $arr;
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

        $session = DatabaseSession::loadSession($_POST["sessionID"], $mysqli);

        if(!$session->checkIfUserCanEdit($user)) {
            PageError::error403();
            die();
        }

        // Setup session from submitted data
        $session->fromArray($_POST);

        $error = false;

        // If user is owner
        if($session->checkIfUserIsOwner($user)) {

            $additionalUsers = [];

            // Load new users
            foreach ($_POST as $key => $value) {

                preg_match("/(user-)(\w*[0-9]\w*)/", $key, $matches);

                if($matches) {

                    // Get the user index from the regex matches
                    $index = $matches[2];

                    // If there is an index associated with this user, store it
                    if(isset($_POST["user-" . $index])) {
                        $username = $_POST["user-" . $index];

                        //If user does not exist output error
                        if(!DatabaseUser::checkUserExists($username, $mysqli) and $username != ""){

                            $alert = new Alert();
                            $alert->setType("danger");
                            $alert->setDismissable(true);
                            $alert->setTitle("Additional user does not exist");
                            $alert->setMessage("One of the additional users you have typed does not exist");
                            Alert::displayAlertSession($alert);
                            header("Location: "  . $config["baseUrl"] . "session/" . $session->getSessionIdentifier() . "/edit/properties/");
                            die();

                            $error = true;

                            break;
                        }

                        // Else add the new user
                        array_push($additionalUsers, $username);
                    }
                }
            }

            // If no error setting additional users, don't modify them
            if(!$error)
                $session->setAdditionalUsers($additionalUsers);
        }

        $result = DatabaseSession::update($session, $mysqli);

        if(!$result) PageError::error500("Database error on line " . __LINE__ . " in file " . __FILE__);

        if($error)
            header("Location: "  . $config["baseUrl"] . "session/" . $session->getSessionIdentifier() . "/edit/properties/");

        else
            header("Location: "  . $config["baseUrl"] . "session/" . $session->getSessionIdentifier() . "/edit/");
        die();
    }
}