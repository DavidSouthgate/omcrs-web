<?php

class PageSessionEditQuestionResponse
{

    public static function response($sessionIdentifier, $sessionQuestionID) {
        $templates = Flight::get("templates");
        $config = Flight::get("config");
        $data = self::setup($sessionIdentifier, $sessionQuestionID, $config);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($data["session"]->getTitle() ? $data["session"]->getTitle() : "Session") . " (#$sessionIdentifier)" . " Edit", $config["baseUrl"]."session/$sessionIdentifier/edit");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/edit/question/");
        //$breadcrumbs->addItem("Question", $config["baseUrl"]."session/$sessionIdentifier/edit/question/$sessionQuestionID/");
        $breadcrumbs->addItem("Responses");
        $data["breadcrumbs"] = $breadcrumbs;

        echo $templates->render("session/edit/questions/response", $data);
    }

    public static function live($sessionIdentifier, $sessionQuestionID) {
        $templates = Flight::get("templates");
        $config = Flight::get("config");
        $data = self::setup($sessionIdentifier, $sessionQuestionID, $config);
        $data["live"] = true;
        echo $templates->render("session/edit/questions/response", $data);
    }

    private static function setup($sessionIdentifier, $sessionQuestionID, $config) {
        $data = Flight::get("data");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        if($session === null) {
            PageError::error500();
            die();
        }

        $sessionID = $session->getSessionID();

        // Load the question from the database
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        if($question === null || $sessionID!=$question->getSessionID()) {
            header("Location: ..");
            die();
        }

        $responsesMcq = null;
        $responsesText = null;
        $responseMrq = null;

        if(in_array($question->getType(), array("text", "textlong"))) {
            $responsesWordCloud = DatabaseResponse::loadWordcloud($sessionQuestionID, $mysqli);
            $responsesText = DatabaseResponse::loadResponses($sessionQuestionID, $mysqli);
        }

        elseif($question->getType() == "mcq") {
            $responsesMcq = DatabaseResponseMcq::loadChoicesTotal($sessionQuestionID, $mysqli);
            $userMcqResponses = DatabaseResponseMcq::loadResponses($sessionQuestionID, $mysqli);
        }

        elseif($question->getType() == "mrq") {
            $responsesMrq = DatabaseResponseMcq::loadChoicesTotal($sessionQuestionID, $mysqli);
            $userMrqResponses = DatabaseResponseMrq::loadResponses($sessionQuestionID, $mysqli);
        }

        $data["responsesMrq"] = $responsesMrq;
        $data["userMrqResponses"] = $userMrqResponses;
        $data["user"] = $user;
        $data["responsesMcq"] = $responsesMcq;
        $data["userMcqResponses"] = $userMcqResponses;
        $data["responsesWordCloud"] = $responsesWordCloud;
        $data["responsesText"] = $responsesText;
        $data["session"] = $session;
        $data["question"] = $question;

        return $data;
    }
}