<?php
require_once("../autoload.php");
session_start();

Flight::set("data", []);
Flight::set("config", $config);

Flight::set("databaseConnect",
    function() use ($config) {

        // Attempt to connect to the database
        $mysqli = @mysqli_connect($config["database"]["host"], $config["database"]["username"], $config["database"]["password"], $config["database"]["name"]);

        // If error connecting to database, display error 500
        if (!$mysqli) {
            ApiError::unknown();
            die();
        }
        return $mysqli;
    }
);

Flight::route("/login", array("ApiLogin", "login"));
Flight::route("/logout", array("ApiLogin", "logout"));
Flight::route("/session/", array("ApiSession", "listSessions"));
Flight::route("/session/new/", array("ApiSession", "edit"));
Flight::route("/session/getactive/", array("ApiSession", "getActiveSessions"));
Flight::route("/session/@sessionID/", array("ApiSession", "details"));
Flight::route("/session/@sessionID/live/", array("ApiSessionQuestion", "live"));
Flight::route("/session/@sessionID/edit/", array("ApiSession", "edit"));
Flight::route("/session/@sessionID/delete/", array("ApiSession", "delete"));
Flight::route("/session/@sessionID/start/", array("ApiSession", "startSession"));
Flight::route("/session/@sessionID/stop/", array("ApiSession", "stopSession"));
Flight::route("/session/@sessionID/question/", array("ApiSessionQuestion", "listSessionQuestion"));
Flight::route("/session/@sessionID/question/active/", array("ApiSessionQuestion", "activeSessionQuestion"));
Flight::route("/session/@sessionID/question/all/", array("ApiSessionQuestion", "allSessionQuestion"));
Flight::route("/session/@sessionID/question/reorder/", array("ApiSessionQuestion", "reorder"));
Flight::route("/session/@sessionID/results/", array("ApiSession", "getResults"));
Flight::route("/session/@sessionID/export/", array("ApiSession", "export"));

Flight::route("/session/@sessionID/question/new/mcq/", array("ApiSessionQuestionNew", "mcq"));
Flight::route("/session/@sessionID/question/new/mrq/", array("ApiSessionQuestionNew", "mrq"));
Flight::route("/session/@sessionID/question/new/text/", array("ApiSessionQuestionNew", "text"));
Flight::route("/session/@sessionID/question/new/textlong/", array("ApiSessionQuestionNew", "textLong"));

Flight::route("/session/@sessionID/question/@sessionQuestionID/", array("ApiSessionQuestion", "viewSessionQuestion"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/results/", array("ApiSessionQuestion", "questionResults"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/delete/", array("ApiSessionQuestion", "deleteSessionQuestion"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/edit/", array("ApiSessionQuestion", "edit"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/users/", array("ApiSessionQuestion", "users"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/screenshot/", array("ApiSessionQuestion", "screenshot"));
Flight::route("/session/@sessionID/question/@sessionQuestionID/analysis/", array("ApiSessionQuestion", "analysis"));

Flight::route("/user/", array("ApiUser", "listUsers"));
Flight::route("/user/@userID/", array("ApiUser", "details"));
Flight::route("/user/@userID/edit/", array("ApiUser", "edit"));
Flight::route("/user/@userID/delete/", array("ApiUser", "delete"));

Flight::map('error', array("ApiError", "handler"));
Flight::map('notFound', array("ApiError", "notFound"));

Flight::start();