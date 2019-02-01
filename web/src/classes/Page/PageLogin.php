<?php

class PageLogin
{

    public static function login() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        echo $templates->render("login/login", $data);
    }

    public static function loginSubmit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Check this login
        $user = Login::checkLogin(
            $_POST["username"],
            $_POST["password"],
            $config,
            $mysqli);

        if($user === null) {
            PageError::error500("Error loading user");
            die();
        }

        // If invalid login
        if(!$user) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid username or password");
            Alert::displayAlertSession($alert);

            // Forward to login
            header("Location: " . $config["baseUrl"] . "login/");
            die();
        }

        // Forward the user home
        header("Location: " . $config["baseUrl"]);
        die();
    }

    public static function anonymous() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Login", $config["baseUrl"] . "login/");
        $breadcrumbs->addItem("Anonymous");

        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("login/anonymous", $data);
    }

    public static function anonymousSubmit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Create an anonymous user
        Login::anonymousUserCreate($_POST["nickname"], $mysqli);

        // Forward the user home
        header("Location: " . $config["baseUrl"]);
        die();
    }
}