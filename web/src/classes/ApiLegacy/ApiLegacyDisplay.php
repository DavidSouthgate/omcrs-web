<?php
use JpGraph\JpGraph;

class ApiLegacyDisplay
{

    public static function display($config, $mysqli) {

        $user = DatabaseApiKey::checkApiKey($_REQUEST["key"], $mysqli);
        $session = DatabaseSession::loadSession($_REQUEST["id"], $mysqli);

        if(!$user || !$session || !$session->checkIfUserCanEdit($user)) {
            echo "<h1>Permission Error</h1>";
            echo "<p>You do not have the permission to view this page</p>";
            die();
        }

        $question = DatabaseSessionQuestion::loadActiveQuestion($session->getSessionID(), 0, $mysqli);

        if(!$question) {
            echo "<h1>There is no active question</h1>";
            die();
        }

        if($question->getType() == "mcq") {
            $output = "";



            
            
            echo $output;
        }

        else {
            echo "Cannot Display Question Type";
        }
    }
}