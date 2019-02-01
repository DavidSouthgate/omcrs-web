<?php

class ApiError
{

    public static function handler() {
        Api::output();
    }

    public static function notFound() {
        $output = [];
        $output["error"]["code"]    = "notFound";
        $output["error"]["message"] = "Command not found";
        Api::output($output);
        die();
    }

    public static function notFoundCustom($s) {
        $output = [];
        $output["error"]["code"]    = "notFound";
        $output["error"]["message"] = $s;
        Api::output($output);
        die();
    }

    public static function invalidApiKey() {
        $output = [];
        $output["error"]["code"]    = "invalidApiKey";
        $output["error"]["message"] = "Invalid API Key";
        Api::output($output);
        die();
    }

    public static function permissionDenied() {
        $output = [];
        $output["error"]["code"]    = "permissionDenied";
        $output["error"]["message"] = "You do not have permission to view this page";
        Api::output($output);
        die();
    }

    public static function unknown() {
        $output = [];
        $output["error"]["code"]    = "unknownError";
        $output["error"]["message"] = "Unknown Error";
        Api::output($output);
        die();
    }

    public static function custom($code, $message) {
        $output = [];
        $output["error"]["code"]    = $code;
        $output["error"]["message"] = $message;
        Api::output($output);
        die();
    }
}