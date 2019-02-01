<?php

class ApiSessionQuestion
{

    public static function listSessionQuestion($sessionIdentifier)
    {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        $questions = DatabaseSessionQuestion::loadSessionQuestions($session->getSessionID(), $mysqli);
        $output = [];

        foreach ($questions["questions"] as $question) {
            /** @var $question Question */

            $output[] = $question->toArray();
        }

        Api::output($output);
    }

    public static function live($sessionIdentifier)
    {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        $result = DatabaseSessionQuestion::loadSessionQuestions($session->getSessionID(), $mysqli);

        $active = $result["active"];
        $activeSessionQuestionID = $result["activeSessionQuestionID"];

        $output = [];

        foreach ($result["questions"] as $question) {
            /** @var $question Question */

            $item = [];
            $item["sessionQuestionID"] = $question->getSessionQuestionID();
            $item["question"] = $question->getQuestion();
            $item["active"] = $question->isActive();
            $output["questions"][] = $item;
        }

        $output["active"] = $active;
        $output["activeSessionQuestionID"] = intval($activeSessionQuestionID);
        $output["defaultTimeLimit"] = $session->getDefaultTimeLimit();

        Api::output($output);
    }

    /**
     * Load session question IDs for all active questions in a session. Any logged in user can access this API as this
     * is used in the website javascript frontend.
     * @param int $sessionIdentifier
     */
    public static function activeSessionQuestion($sessionIdentifier)
    {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display error
        if (!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if (!$user) {
            ApiError::invalidApiKey();
            die();
        }

        $questions = DatabaseSessionQuestion::loadAllActiveQuestions($sessionID, $mysqli);

        // Add all of the session question IDs to the output
        $output = [];
        foreach ($questions as $question) {
            $output[] = $question->getSessionQuestionID();
        }

        Api::output($output);
    }

    /**
     * Load session question IDs for all questions in a session.
     * @param int $sessionIdentifier
     */
    public static function allSessionQuestion($sessionIdentifier)
    {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        $questions = DatabaseSessionQuestion::loadSessionQuestions($session->getSessionID(), $mysqli);
        $questions = array_reverse($questions["questions"]);

        // Add all of the session question IDs to the output
        $output = [];
        foreach ($questions as $question) {
            $output[] = $question->getSessionQuestionID();
        }

        Api::output($output);
    }

    public static function viewSessionQuestion($sessionIdentifier, $sessionQuestionID)
    {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setupSessionQuestion($sessionIdentifier, $sessionQuestionID));

        Api::output($question->toArray());
    }

    /**
     * Edit a session question
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function edit($sessionIdentifier, $sessionQuestionID)
    {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setupSessionQuestion($sessionIdentifier, $sessionQuestionID));

        $question->fromArray($_REQUEST);

        $result = DatabaseSessionQuestion::update($question, $session, $mysqli);

        // Update question in database, if error updating display error
        if (!$result) {

            ApiError::unknown();
            die();
        }

        Api::output($question->toArray());
    }

    /**
     * Delete question from session
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function deleteSessionQuestion($sessionIdentifier, $sessionQuestionID)
    {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setupSessionQuestion($sessionIdentifier, $sessionQuestionID));

        // Delete session question
        $result = DatabaseSessionQuestion::delete($sessionQuestionID, $mysqli);

        // If error deleting session question
        if (!$result) {
            ApiError::unknown();
            die();
        }

        Api::output(["success" => true]);
    }

    public static function users($sessionIdentifier, $sessionQuestionID)
    {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        $sessionID = $session->getSessionID();

        $users = DatabaseSessionQuestion::users($sessionID, $sessionQuestionID, $mysqli);

        $output = [];
        $output["answered"] = intval($users["answered"]);
        $output["total"] = intval($users["total"]);
        Api::output($output);
    }

    public static function reorder($sessionIdentifier)
    {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        $orderJson = Api::checkParameter("order");

        // Decode order json
        $order = json_decode($orderJson);

        // If error decoding
        if (!$order) {
            ApiError::unknown();
            die();
        }

        $result = DatabaseSessionQuestion::reorder($session->getSessionID(), $order, $mysqli);

        if (!$result) {
            ApiError::unknown();
            die();
        }

        $output = [];
        $output["success"] = true;
        Api::output($output);
    }

    /**
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function screenshot($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setupSessionQuestion($sessionIdentifier, $sessionQuestionID));

        // Create a new upload object
        $upload = new Upload("/var/www/uploads/");

        // Use posted base64 data
        $upload->base64($_REQUEST["base64"]);

        // If not valid data
        if (!$upload->isValid()) {
            ApiError::custom("uploadInvalid", "The uploaded image was invalid");
            die();
        }

        // Save to disk
        $result = $upload->save();

        if (!$result) {
            ApiError::custom("couldNotSaveImage", "The server could not save the uploaded image");
            die();
        }

        // Insert item into database
        $result = DatabaseSessionQuestionScreenshot::insertFilename($upload->getFilename(), $sessionQuestionID, $mysqli);

        if (!$result) {
            ApiError::custom("databaseError", "Could not add image details to database");
            die();
        }

        $output = [];
        $output["success"] = true;
        Api::output($output);
    }

    public static function questionResults($sessionIdentifier, $sessionQuestionID) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session id, display error
        if (!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // Check the API Key and get the username of the user
        if (!$user) {
            ApiError::invalidApiKey();
            die();
        }

        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        $output = [];

        if($question->getType() == "mcq" or $question->getType() == "mrq"){
            $results = DatabaseResponseMcq::loadResponses($sessionQuestionID, $mysqli);
        }
        else{
            $results = DatabaseResponse::loadResponses($sessionQuestionID, $mysqli);
        }

        foreach ($results as $response) {
            $temp = [];
            $temp["choice"] = $response->getResponse();
            $temp["username"] = $response->getUsername();
            array_push($output, $temp);
        }

        Api::output($output);
    }

    private static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param int $sessionIdentifier
     * @param int $sessionQuestionID
     */
    public static function analysis($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session question variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         * @var $question Question
         */
        extract(self::setupSessionQuestion($sessionIdentifier, $sessionQuestionID));

        $config = Flight::get("config");

        $sessionQuestionID = escapeshellarg($sessionQuestionID);

        $command = escapeshellcmd($config["baseDir"] . "/src/analysis/analysis.py $sessionQuestionID");
        exec($command, $output, $return);

        $output = $output[0];

        if($return !== 0) {
            ApiError::custom("analysisError", "The analysis execution failed");
            die();
        }

        if(!self::isJson($output)) {
            ApiError::custom("analysisError", "The analysis produced an invalid output");
            die();
        }

        Api::outputJson($output);
    }

    private static function setupSession($sessionIdentifier) {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API key
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // If user was not loaded, output error
        if (!$user) {
            ApiError::invalidApiKey();
            die();
        }

        // Get the session ID for this
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display error
        if (!$sessionID) {
            ApiError::notFoundCustom("Session Not Found");
            die();
        }

        // Load the session
        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // If user cannot edit this session, output permission denied
        if (!$session->checkIfUserCanEdit($user)) {
            ApiError::permissionDenied();
            die();
        }

        return [
            "mysqli" => $mysqli,
            "user" => $user,
            "session" => $session
        ];
    }

    /**
     * Setup session question whilst ensuring permissions are kept
     * @param $sessionIdentifier
     * @param $sessionQuestionID
     * @return array
     */
    private static function setupSessionQuestion($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $mysqli mysqli
         * @var $user User
         * @var $session Session
         */
        extract(self::setupSession($sessionIdentifier));

        // Load the question
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        // If this question does not belong to this session
        if (!$question || $question->getSessionID() != $session->getSessionID()) {
            ApiError::unknown();
            die();
        }

        return [
            "mysqli" => $mysqli,
            "user" => $user,
            "session" => $session,
            "question" => $question
        ];
    }
}