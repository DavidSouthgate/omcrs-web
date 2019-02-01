<?php

class ApiSessionQuestionNew
{

    /**
     * @param int $sessionIdentifier
     * @param Question $question
     */
    private static function insertQuestion($sessionIdentifier, $question) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        if(!$session)
            ApiError::unknown();

        $sessionID = $session->getSessionID();

        if(!$session->checkIfUserCanEdit($user)) {
            ApiError::permissionDenied();
        }

        if(!$sessionID)
            ApiError::unknown();

        // Insert question into the database
        $questionID = DatabaseQuestion::insert($question, $mysqli);

        if(!$questionID)
            ApiError::unknown();

        // Insert question session combo into DatabaseSession
        $sessionQuestionID = DatabaseSessionQuestion::insert($sessionID, $questionID, $mysqli);

        if(!$sessionQuestionID)
            ApiError::unknown();

        // Reload question from database
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        $output = $question->toArray();

        Api::output($output);
    }

    /**
     * Creates a new MCQ or MRQ depending on what is passed to question
     * @param int $sessionIdentifier
     * @param QuestionMcq|QuestionMrq $question
     */
    private static function choicesQuestion($sessionIdentifier, $question) {
        $output = [];

        // Add basic question details
        $question->setQuestion($_REQUEST["question"]);
        $question->setCreated(time());
        $question->setLastUpdate(time());

        // Loop through the request to add choices
        foreach($_REQUEST as $key => $value) {

            // Use regex to determine whether the key is in format "choice-1"
            preg_match("/(choice-)(\w*[0-9]\w*)/", $key, $matches);

            // If in that format, add as choice
            if($matches) {

                // Get the choice index from the regex match
                $index = $matches[2];

                // Check if this choice is correct
                $correct = array_key_exists("choice-correct-$index", $_REQUEST) ? $_REQUEST["choice-correct-$index"] : false;

                // Choice is correct if value is 'true'
                $correct = $correct=="true" ? true : false;

                // Add as choice
                $question->addChoice($value, $correct);
            }
        }

        self::insertQuestion($sessionIdentifier, $question);
    }

    /**
     * Creates a new Text Question
     * @param int $sessionIdentifier
     * @param Question $question
     */
    private static function textQuestion($sessionIdentifier, $question) {
        $output = [];

        // Add basic question details
        $question->setQuestion($_REQUEST["question"]);
        $question->setCreated(time());
        $question->setLastUpdate(time());

        self::insertQuestion($sessionIdentifier, $question);
    }

    /**
     * @param int $sessionIdentifier
     */
    public static function mcq($sessionIdentifier) {
        self::choicesQuestion($sessionIdentifier, new QuestionMcq());
    }

    /**
     * @param int $sessionIdentifier
     */
    public static function mrq($sessionIdentifier) {
        self::choicesQuestion($sessionIdentifier, new QuestionMrq());
    }

    /**
     * @param int $sessionIdentifier
     */
    public static function text($sessionIdentifier) {
        self::textQuestion($sessionIdentifier, new QuestionText());
    }

    /**
     * @param int $sessionIdentifier
     */
    public static function textLong($sessionIdentifier) {
        self::textQuestion($sessionIdentifier, new QuestionTextLong());
    }
}