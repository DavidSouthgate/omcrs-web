<?php

class DatabaseQuestion
{

    /**
     * @param int $questionID
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function load($questionID, $mysqli) {
        $questionID = Database::safe($questionID, $mysqli);

        // Run SQL query to get question
        $sql = "SELECT
                    q.`questionID` as questionID,
                    q.`question` as question,
                    q.`created` as created,
                    q.`lastUpdate` as lastUpdate,
                    qt.`name` as type
                FROM
                    `omcrs_questions` as q,
                    `omcrs_questionTypes` as qt
                WHERE q.`questionID` = '$questionID'
                  AND q.`type` = qt.`ID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        if($result->num_rows==0) {
            return null;
        }

        // Get the row from the database
        $row = $result->fetch_assoc();

        // Setup new question
        try {
            $question = QuestionFactory::create($row["type"], $row);
        }
        catch(Exception $e) { return null; }

        // Load question type specific details
        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                $question = self::loadMcq($question, $questionID, $mysqli);
        }

        return $question;
    }

    /**
     * @param QuestionMcq $question
     * @param int $questionID
     * @param mysqli $mysqli
     * @return QuestionMcq|null
     */
    public static function loadMcq($question, $questionID, $mysqli) {
        $questionID = Database::safe($questionID, $mysqli);

        // Run SQL query to get MCQ choices
        $sql = "SELECT `ID`, `choice`, `correct`
                FROM `omcrs_questionsMcqChoices` as qmcqc
                WHERE qmcqc.`questionID` = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Loop for every MCQ choice
        while($row = $result->fetch_assoc()) {
            $question->addChoice($row["choice"], $row["correct"], $row["ID"]);
        }

        return $question;
    }


    /**
     * Add new question to database
     * @param Question $question Question as Question object
     * @param mysqli $mysqli Database connection
     * @return int Session ID
     */
    public static function insert($question, $mysqli) {

        // Make variables safe for database use
        $text       = Database::safe($question->getQuestion(), $mysqli);
        $type       = self::questionTypeToId($question->getType());

        // Run query to insert into omcrs_questions table
        $sql = "INSERT INTO `omcrs_questions` (
                    `question`,
                    `type`,
                    `created`,
                    `lastUpdate`
                )
                VALUES (
                    '$text',
                    '$type',
                    ".time().",
                    ".time().")";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Get the question ID
        $questionID = Database::safe($mysqli->insert_id, $mysqli);

        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                self::insertMcq($question, $questionID, $mysqli);
        }

        return $questionID;
    }

    /**
     * @param QuestionMcq $question
     * @param $questionID
     * @param mysqli $mysqli
     * @return bool
     */
    private static function insertMcq($question, $questionID, $mysqli) {

        $questionID = Database::safe($questionID, $mysqli);

        // Foreach choice
        foreach($question->getChoices() as $choice) {

            // Make text safe for database
            $text = Database::safe($choice->getChoice(), $mysqli);

            // Get database representation of correct boolean
            $correct = $choice->isCorrect() ? "1" : "0";

            // Run query to insert into omcrs_questions table
            $sql = "INSERT INTO `omcrs_questionsMcqChoices` (
                    `questionID`,
                    `choice`,
                    `correct`
                )
                VALUES (
                    '$questionID',
                    '$text',
                    '$correct')";
            $result = $mysqli->query($sql);

            if(!$result) return null;
        }

        return true;
    }

    /**
     * @param Question $question
     * @param mysqli $mysqli
     * @return bool
     */
    public static function update($question, $mysqli) {
        $questionText = Database::safe($question->getQuestion(), $mysqli);
        $questionID = Database::safe($question->getQuestionID(), $mysqli);

        $sql = "UPDATE `omcrs_questions`
                SET
                  `question` = '$questionText',
                  `lastUpdate` = '".time()."'
                WHERE `omcrs_questions`.`questionID` = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        switch(get_class($question)) {
            case "QuestionMcq":
            case "QuestionMrq":
                self::updateMcq($question, $mysqli);
        }

        return $result?true:false;
    }

    /**
     * @param QuestionMcq $question
     * @param mysqli $mysqli
     * @return bool
     */
    private static function updateMcq($question, $mysqli) {
        $questionID = Database::safe($question->getQuestionID(), $mysqli);

        // Get the new choices
        $choices = $question->getChoices();

        // Run query to get the old choices
        $sql = "SELECT *
                FROM `omcrs_questionsMcqChoices`
                WHERE `omcrs_questionsMcqChoices`.questionID = $questionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Index used for looping through new choices
        $i = 0;

        // Loop for every old choice
        while($row = $result->fetch_assoc()) {
            
            // If this choice doesn't exist anymore or this old choice is not the same as the new choice in this position
            if(!isset($choices[$i]) || $row["ID"] != $choices[$i]->getChoiceID()) {

                // Make the old choice ID database safe
                $choiceID = Database::safe($row["ID"], $mysqli);

                // Delete this old choice
                $sql = "DELETE FROM `omcrs_questionsMcqChoices`
                        WHERE `omcrs_questionsMcqChoices`.`questionID` = $questionID
                          AND `omcrs_questionsMcqChoices`.`ID` = $choiceID";
                $result2 = $mysqli->query($sql);

                if(!$result2) return null;
            }

            else {


                // Make the choice text and choice ID database safe
                $choice = Database::safe($choices[$i]->getChoice(), $mysqli);
                $correct = Database::safe(bool2dbString($choices[$i]->isCorrect()), $mysqli);
                $choiceID = Database::safe($choices[$i]->getChoiceID(), $mysqli);

                // Update the old choice
                $sql = "UPDATE `omcrs_questionsMcqChoices`
                        SET `choice` = '$choice', `correct` = '$correct'
                        WHERE `omcrs_questionsMcqChoices`.`questionID` = $questionID
                          AND `omcrs_questionsMcqChoices`.`ID` = $choiceID";
                $result2 = $mysqli->query($sql);

                if(!$result2) return null;

                $i++;
            }
        }

        // Loop through the remaining new choices
        while($i < count($choices)) {

            // Make this choice text database safe
            $choice = Database::safe($choices[$i]->getChoice(), $mysqli);

            // Add this new choice
            $sql = "INSERT INTO `omcrs_questionsMcqChoices` (`questionID`, `choice`)
                    VALUES ($questionID, '$choice')";
            $result = $mysqli->query($sql);

            if(!$result) return null;

            $i++;
        }

        return true;
    }

    private static function questionTypeToId($type) {
        switch($type) {
            case "mcq":
                return 1;
                break;
            case "text":
                return 2;
                break;
            case "textlong":
                return 3;
                break;
            case "mrq":
                return 4;
                break;
        }
    }
}