<?php

class PageLogout
{

    public static function logout() {
        $config = Flight::get("config");

        // Remove session variable
        unset($_SESSION["omcrs_user"]);

        // Forward user home
        header("Location: " . $config["baseUrl"]);
        die();
    }
}