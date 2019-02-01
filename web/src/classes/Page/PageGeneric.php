<?php

class PageGeneric
{

    public static function help() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $user = Login::checkUserLoggedIn();
        $data["user"] = $user;
        echo $templates->render("help", $data);
    }

    public static function download() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $user = Login::checkUserLoggedIn();
        $data["user"] = $user;
        echo $templates->render("download", $data);
    }
}