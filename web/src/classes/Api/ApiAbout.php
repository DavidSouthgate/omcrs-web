<?php

class ApiAbout
{

    public static function about() {
        $output = [];
        $output["version"] = "2.0.0 indev";
        Api::output($output);
    }
}