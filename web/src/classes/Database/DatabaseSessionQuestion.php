<?php

class DatabaseSessionQuestion
{

    /**
     * @param int $sessionID
     * @param int $questionID
     * @param mysqli $mysqli
     * @return int
     */
    public static function insert($sessionID, $questionID, $mysqli) {

        // Make items database safe
        $sessionID  = Database::safe($sessionID, $mysqli);
        $questionID = Database::safe($questionID, $mysqli);

        $sql = "INSERT INTO `omcrs_sessionQuestions` (
                    `sessionID`,
                    `questionID`
                )
                VALUES (
                    '$sessionID',
                    '$questionID'
                )";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $mysqli->insert_id;
    }

    /**
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return int
     */
    public static function delete($sessionQuestionID, $mysqli) {

        // Make items database safe
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);

        $sql = "DELETE FROM `omcrs_sessionQuestions`
                WHERE `omcrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $sql = "DELETE FROM `omcrs_questionsMcqChoices`
                WHERE `omcrs_questionsMcqChoices`.`questionID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $result ? true : false;
    }

    /**
     * Update a question
     * @param Question $question
     * @param Session $session
     * @param mysqli $mysqli
     * @return bool success?
     */
    public static function update($question, $session, $mysqli) {
        $active = Database::safe(bool2dbString($question->isActive()), $mysqli);
        $sessionID = Database::safe($session->getSessionID(), $mysqli);
        $sessionQuestionID = Database::safe($question->getSessionQuestionID(), $mysqli);

        // If activating question and this is a teacher led question
        if($question->isActive() && $session->getQuestionControlMode() === 0) {

            // Disable all questions
            $sql = "UPDATE `omcrs_sessionQuestions`
                    SET `active` = '0'
                    WHERE `omcrs_sessionQuestions`.`sessionID` = $sessionID";
            $result = $mysqli->query($sql);

            if(!$result)
                return false;
        }

        // Activate question
        $sql = "UPDATE `omcrs_sessionQuestions`
                SET `active` = '$active'
                WHERE `omcrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return DatabaseQuestion::update($question, $mysqli);
    }

    /**
     * @param int $sessionID
     * @param mysqli $mysqli
     * @return array|null
     */
    public static function loadSessionQuestions($sessionID, $mysqli) {
        $sessionID  = Database::safe($sessionID, $mysqli);

        $sql = "SELECT sq.`ID` as `sessionQuestionID`, q.`questionID`, sq.`active`, q.`question`
                FROM
                    `omcrs_sessionQuestions` as sq,
                    `omcrs_questions` as q
                WHERE sq.`questionID` = q.`questionID`
                  AND sq.`sessionID` = '$sessionID'
                ORDER BY sq.`ID` DESC";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $output["questions"] = [];
        $output["active"] = false;

        // Loop for each row in result
        while($row = $result->fetch_assoc()) {

            $question = DatabaseQuestion::load($row["questionID"], $mysqli);
            $question->setSessionQuestionID($row["sessionQuestionID"]);
            $question->setSessionID($sessionID);
            $question->setQuestion($row["question"]);

            if($row["active"]) {
                $output["active"] = true;
                $output["activeSessionQuestionID"] = $row["sessionQuestionID"];
                $question->setActive(true);
            }

            array_push($output["questions"], $question);
        }

        return $output;
    }

    /**
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function loadQuestion($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        // Run SQL query to get question ID
        $sql = "SELECT `ID`, `sessionID`, `questionID`, `active`
                FROM `omcrs_sessionQuestions` as sq
                WHERE sq.`ID` = $sessionQuestionID
                LIMIT 1";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        $question = DatabaseQuestion::load($row["questionID"], $mysqli);

        if(!$question) return $question;

        $question->setSessionID($row["sessionID"]);
        $question->setSessionQuestionID($row["ID"]);
        $question->setActive($row["active"]);

        return $question;
    }

    /**
     * Loads a single active question
     * @param int $sessionID
     * @param int $questionNumber The question number (starting at 0)
     * @param mysqli $mysqli
     * @return Question|null
     */
    public static function loadActiveQuestion($sessionID, $questionNumber = 0, $mysqli) {
        $sessionID      = Database::safe($sessionID, $mysqli);
        $questionNumber = Database::safe($questionNumber, $mysqli);

        // Run SQL query to get active question
        $sql = "SELECT `ID`, `questionID`
                FROM `omcrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                AND sq.`active` = 1
                LIMIT $questionNumber,1";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();
        if(!$row) return null;
        $question = DatabaseQuestion::load($row["questionID"], $mysqli);
        $question->setSessionQuestionID($row["ID"]);

        return $question;
    }

    /**
     * Loads all active questions for a session
     * @param int $sessionID
     * @param mysqli $mysqli
     * @return Question[]|null
     */
    public static function loadAllActiveQuestions($sessionID, $mysqli) {
        $sessionID      = Database::safe($sessionID, $mysqli);

        // Run SQL query to get all active questions
        $sql = "SELECT `ID`, `questionID`
                FROM `omcrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                  AND sq.`active` = 1";
        $result = $mysqli->query($sql);

        // If error, return NULL
        if(!$result) return null;

        $output = [];

        // Loop for every active question
        while($row = $result->fetch_assoc()) {

            // Load the question, if successful add to the output
            if($question = DatabaseSessionQuestion::loadQuestion($row["ID"], $mysqli)) {
                $output[] = $question;
            }
        }

        return $output;
    }

    public static function countActiveQuestions($sessionID, $mysqli) {
        $sessionID = Database::safe($sessionID, $mysqli);

        // Run SQL query to get number of questions
        $sql = "SELECT count(`ID`) as count
                FROM `omcrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID
                  AND sq.`active` = 1";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        if($result->num_rows <= 0) {
            return null;
        }

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        return $row["count"];
    }

    /**
     * @param int $sessionQuestionID
     * @param bool $active
     * @param mysqli $mysqli
     * @return bool
     */
    public static function questionActivate($sessionQuestionID, $active = true, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);
        $active = $active ? "1" : "0";

        $sql = "UPDATE `omcrs_sessionQuestions`
                SET `active` = '$active'
                WHERE `omcrs_sessionQuestions`.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return true;
    }

    /**
     * Get the total number of users in a session and the number of users who have answered this question
     * @param int $sessionID
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return int[]
     */
    public static function users($sessionID, $sessionQuestionID, $mysqli) {
        $sessionID = Database::safe($sessionID, $mysqli);
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT answered.answered, total.total
                FROM
                (
                    SELECT count(time) as answered
                    FROM
                    (
                        (
                            SELECT r.time, r.sessionQuestionID, r.userID
                            FROM `omcrs_response` as r
                            WHERE r.sessionQuestionID = $sessionQuestionID
                        )
                        UNION
                        (
                            SELECT r.time, r.sessionQuestionID, r.userID
                            FROM `omcrs_responseMcq` as r
                            WHERE r.sessionQuestionID = $sessionQuestionID
                        )
                    ) as answeredCount
                ) AS answered,
                (
                    SELECT count(totalCount.userID) as total
                    FROM
                    (
                        SELECT userID
                        FROM `omcrs_sessionHistory` as sh
                        WHERE sh.`sessionID` = $sessionID
                        GROUP BY userID
                    ) as totalCount
                ) as total";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Fetch the row returned from the table
        $row = $result->fetch_assoc();

        $output = [];
        $output["answered"] = intval($row["answered"]);
        $output["total"] = intval($row["total"]) - 1; // Remove 1 as this includes owner

        // If owner has answered the question, increase total
        if($output["answered"] > $output["total"]) {
            $output["total"] = $output["answered"];
        }

        //$output["answered"] = (time() % 100) * 5;
        //$output["total"] = 500;

        return $output;
    }

    /**
     * Reorder questions in a session
     * @param int $sessionID
     * @param array $order Of form E.g. [2, 3, 5, 4] Where 2,3,4,5 are session question IDs in a new order
     * @param mysqli $mysqli
     * @return bool
     */
    public static function reorder($sessionID, $order, $mysqli) {
        $sessionID = Database::safe($sessionID, $mysqli);

        // TODO LOCK

        // Run query to get all existing session questions
        $sql = "SELECT *
                FROM `omcrs_sessionQuestions` as sq
                WHERE sq.`sessionID` = $sessionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // Loop for every old session question
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Ensure every item in new order was in old order
        $i = 0;
        foreach($order as $o) {
            $flag = false;
            foreach($rows as $row) {
                if($row["questionID"] == $o) {
                    $array["ID"][] = $rows[$i]["ID"];
                    $array["questionID"][] = $o;
                    $array["active"][] = $row["active"];
                    $flag = true;
                    break;
                }
            }
            if(!$flag) {
                return null;
            }
            $i++;
        }

        // Build Query
        $sql = "START TRANSACTION;";
        for($i=0; $i<count($array["ID"]); $i++) {
            $ID         = Database::safe($array["ID"][$i],          $mysqli);
            $questionID = Database::safe($array["questionID"][$i],  $mysqli);
            $active     = Database::safe($array["active"][$i],      $mysqli);

            $sql .= " UPDATE `omcrs_sessionQuestions` as sq SET sq.`questionID` = '$questionID', sq.`active` = '$active' WHERE sq.`ID` = $ID;";
        }
        $sql .= " COMMIT;";

        $mysqli->multi_query($sql);

        // If error running one of the queries, return null
        while ($mysqli->next_result());
        if ($mysqli->errno)
            return null;

        return true;
    }
}