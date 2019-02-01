<?php

class PageError
{

    public static function error403() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();

        Flight::halt(403);
        echo $templates->render("error/error403", $data);
    }

    public static function error404() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();

        Flight::halt(404);
        echo $templates->render("error/error404", $data);
    }

    public static function error500($log = null) {
        if($log)
            error_log($log);

        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();

        Flight::halt(500);
        echo $templates->render("error/error500", $data);
    }

    /**
     * Displays a generic error page
     * @param string $title The title of the error
     * @param string $message The error message (HTML allowed)
     * @param int $code A HTTP error code
     * @param bool $permanent Whether trying again might solve the problem
     */
    public static function generic($title, $message, $code = 200, $permanent = true) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();
        $data["title"] = $title;
        $data["message"] = $message;
        $data["permanent"] = $permanent;
        Flight::halt($code);
        echo $templates->render("error/generic", $data);
    }
}