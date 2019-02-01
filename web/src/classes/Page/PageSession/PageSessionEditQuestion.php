<?php

class PageSessionEditQuestion extends PageSessionEdit
{

    /**
     * Page to add a new question to a session
     * @param int $sessionIdentifier
     */
    public static function add($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)" . " Edit", $config["baseUrl"]."session/$sessionIdentifier/edit");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/edit/question/");
        $breadcrumbs->addItem("New");

        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/edit/questions/edit", $data);
    }

    /**
     * Submits a new session
     * @param int $sessionIdentifier
     */
    public static function addSubmit($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        // Attempt to create a new question for this question type
        try {
            $question = QuestionFactory::create($_POST["questionType"], $_POST);
        }

        // If error creating question, log the error and display an error page
        catch(Exception $e) {
            Error::exception($e, __LINE__, __FILE__);
            die();
        }

        // If MCQ or MRQ question
        if(in_array(get_class($question), ["QuestionMcq", "QuestionMrq"])) {

            // Loop for every posted value
            foreach($_POST as $key => $value) {

                // Use regex to check if this is a "mcq-choice-1" field
                preg_match("/(mcq-choice-)(\w*[0-9]\w*)/", $key, $matches);

                // If there are matches then this is a "mcq-choice-1" field
                if($matches) {

                    // Get the index
                    $index = $matches[2];

                    // Boolean for if this is correct
                    $correct = text2bool(array_key_exists("mcq-choice-correct-$index", $_POST) ? $_POST["mcq-choice-correct-$index"] : "false");

                    // Add choice
                    $question->addChoice($value, $correct);
                }
            }
        }

        // Insert question into the database
        $questionID = DatabaseQuestion::insert($question, $mysqli);

        // Load the session ID
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if($sessionID === null) {
            PageError::error404();
            die();
        }

        // Insert question session combo into DatabaseSession
        DatabaseSessionQuestion::insert($sessionID, $questionID, $mysqli);

        header("Location: " . $config["baseUrl"] . "session/$sessionIdentifier/edit/");
        die();
    }

    public static function edit($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if($sessionID === null) {
            PageError::error404();
            die();
        }

        // Get question whilst ensuring permissions are kept
        $question = self::setupQuestion($sessionID, $sessionQuestionID, $mysqli);

        // Load whether a screenshot exists
        $screenshot = !!DatabaseSessionQuestionScreenshot::loadSessionQuestionID($sessionQuestionID, $mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)"  . " Edit", $config["baseUrl"]."session/$sessionIdentifier/edit");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/edit/question/");
        $breadcrumbs->addItem("Edit");

        $data["question"] = $question;
        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["screenshot"] = $screenshot;
        echo $templates->render("session/edit/questions/edit", $data);
    }

    public static function editSubmit($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if($sessionID === null) {
            PageError::error404();
            die();
        }

        // Get question whilst ensuring permissions are kept
        $question = self::setupQuestion($sessionID, $sessionQuestionID, $mysqli);

        // If MCQ question
        if(in_array(get_class($question), ["QuestionMcq", "QuestionMrq"])) {

            // Remove existing choices
            $question->setChoices([]);

            // Load new choices
            foreach ($_POST as $key => $value) {

                // Use regex to check if this is a "mcq-choice-1" field
                preg_match("/(mcq-choice-)(\w*[0-9]\w*)/", $key, $matches);

                // If there are matches then this is a "mcq-choice-1" field
                if($matches) {

                    // Get the choice index from the regex matches
                    $choiceIndex = $matches[2];

                    // If no choice ID, use null
                    $choiceID = null;

                    // If there is a choice ID associated with this choice, store it
                    if(isset($_POST["mcq-choice-id-" . $choiceIndex])) {
                        $choiceID = intval($_POST["mcq-choice-id-" . $choiceIndex]);
                    }

                    // Get the index
                    $index = $matches[2];

                    // Boolean for if this is correct
                    $correct = text2bool(array_key_exists("mcq-choice-correct-$index", $_POST) ? $_POST["mcq-choice-correct-$index"] : "false");

                    // Add a new choice
                    $question->addChoice($value, $correct, $choiceID);
                }
            }
        }

        // Update question text
        $question->setQuestion($_POST["question"]);

        DatabaseQuestion::update($question, $mysqli);

        header("Location: " . $config["baseUrl"] . "session/$sessionIdentifier/edit/");
        die();
    }


    public static function screenshot($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if($sessionID === null) {
            PageError::error404();
            die();
        }

        // Load filename
        $filename = DatabaseSessionQuestionScreenshot::loadSessionQuestionID($sessionQuestionID, $mysqli);

        if($filename === null) {
            PageError::error404();
            die();
        }

        // Load extension
        $extension = Upload::getExtensionFromFilename($filename);

        // Load header
        $header = Upload::getHeaderFromExtension($extension);

        header("Content-type: $header");

        if(!file_exists($config["baseDir"] . "/uploads/" . $filename)) {
            PageError::error404();
            die();
        }

        echo readfile($config["baseDir"] . "/uploads/" . $filename);
        die();
    }

    /**
     * Setup questions whilst ensuring permissions are kept
     * @param int $sessionID
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return Question|QuestionMcq
     */
    private static function setupQuestion($sessionID, $sessionQuestionID, $mysqli) {

        // If no session question ID, go up a page
        if($sessionQuestionID === null)
            header("Location: ..");

        // Load the question
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        // Display a 404 if the question wasn't loaded or this question doesn't belong to this session
        if($question === null || $sessionID != $question->getSessionID()) {
            PageError::error404();
            die();
        }

        return $question;
    }
}