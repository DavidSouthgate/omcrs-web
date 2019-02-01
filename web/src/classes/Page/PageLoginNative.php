<?php

class PageLoginNative
{
    public static function register() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // If not using native logins or registration has been disabled, display 404
        if($config["login"]["type"] !== "native" || !$config["login"]["register"]) {
            PageError::error404();
            die();
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Login", $config["baseUrl"] . "login/");
        $breadcrumbs->addItem("Register");

        $data["breadcrumbs"] = $breadcrumbs;
        echo $templates->render("login/register", $data);
    }

    public static function registerSubmit() {
        $config = Flight::get("config");

        // If not using native logins or registration has been disabled, display 404
        if($config["login"]["type"] !== "native" || !$config["login"]["register"]) {
            PageError::error404();
            die();
        }

        // If passwords don't match
        if($_POST["password"] !== $_POST["verifyPassword"]) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Passwords did not match");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // If username or password was not entered
        if(!$_POST["username"] || !$_POST["password"]) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Invalid username or password");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $register = DatabaseLogin::register($_POST["username"], $_POST["password"], $_POST["givenName"], $_POST["surname"], $_POST["email"], $mysqli);

        if(!$register) PageError::error500("Could not register user in ".__FILE__." on line ".__LINE__);

        // If username already exists
        if($register === 100) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Username already exists");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // If registration failed for another reason
        if(!$register) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Registration failed");
            Alert::displayAlertSession($alert);

            // Forward to register
            header("Location: " . $config["baseUrl"] . "register/");
            die();
        }

        // Otherwise, successful! So forward to login page
        $alert = new Alert();
        $alert->setType("success");
        $alert->setDismissable(true);
        $alert->setTitle("Success!");
        $alert->setMessage("You have been successfully registered");
        Alert::displayAlertSession($alert);
        header("Location: " . $config["baseUrl"] . "login/");
        die();
    }

    public static function changePassword($username = null) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // If not using native logins, display 404
        if($config["login"]["type"] !== "native") {
            PageError::error404();
            die();
        }

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // If user is not an admin, but they specified a username. 403
        if(!$user->isAdmin() && $username) {
            PageError::error403();
            die();
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Change Password");

        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["username"] = $username;
        echo $templates->render("login/changepassword", $data);
    }

    public static function changePasswordSubmit() {
        $config = Flight::get("config");

        // If not using native logins, display 404
        if($config["login"]["type"] !== "native") {
            PageError::error404();
            die();
        }

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // If passwords don't match
        if($_REQUEST["newPassword"] !== $_REQUEST["verifyPassword"]) {

            $alert = new Alert();
            $alert->setType("danger");
            $alert->setDismissable(true);
            $alert->setTitle("Error");
            $alert->setMessage("Passwords did not match");
            Alert::displayAlertSession($alert);
            header("Location: .");
            die();
        }

        $username = $user->getUsername();

        // If user is admin, they can change any password. Other users need to input a valid password
        if(!$user->isAdmin()) {

            // If invalid old password, display error
            if(!DatabaseLogin::checkLogin($username, $_REQUEST["oldPassword"], $mysqli)) {
                $alert = new Alert();
                $alert->setType("danger");
                $alert->setDismissable(true);
                $alert->setTitle("Error");
                $alert->setMessage("Invalid old password given");
                Alert::displayAlertSession($alert);
                header("Location: .");
                die();
            }
        }

        else {
            if(isset($_REQUEST["username"])) {
                $username = $_REQUEST["username"];
            }
        }

        // Therefore, user is allowed to change password. So change it
        $result = DatabaseLogin::updatePassword($username, $_POST["newPassword"], $mysqli);

        if(!$result) PageError::error500("Could not update password in ".__FILE__." on line ".__LINE__);

        // If admin, forward to admin page once done. Otherwise, forward home.
        $url = $user->isAdmin() ? $config["baseUrl"] . "admin?users" : $config["baseUrl"];

        $alert = new Alert();
        $alert->setType("success");
        $alert->setDismissable(true);
        $alert->setTitle("Success!");
        $alert->setMessage("Password changed successfully!");
        Alert::displayAlertSession($alert);
        header("Location: " . $url);
        die();
    }
}