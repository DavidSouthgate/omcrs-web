<?php

class PageSessionLive
{

    public static function live() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        echo $templates->render("session/live", $data);
    }
}