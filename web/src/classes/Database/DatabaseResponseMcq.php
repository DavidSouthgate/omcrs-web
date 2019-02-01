<?php

class DatabaseResponseMcq
{

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param int $choiceID
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($sessionQuestionID, $userID, $choiceID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);
        $choiceID           = Database::safe($choiceID, $mysqli);

        $sql = "INSERT INTO `omcrs_responseMcq` (`time`, `sessionQuestionID`, `userID`, `choiceID`) 
                VALUES ('".time()."', '$sessionQuestionID', '$userID', '$choiceID')";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $mysqli->insert_id;
    }

    /**
     * @param $sessionQuestionID
     * @param mysqli $mysqli
     * @return null
     */
    public static function loadChoicesTotal($sessionQuestionID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT
                    sq.`ID` as sessionQuestionID,
                    sq.`questionID` as questionID,
                    qmcqc.`ID` as choiceID,
                    qmcqc.`choice` as choice,
                    mcqResponseCount.`count`
                FROM
                    `omcrs_sessionQuestions` as sq,
                    `omcrs_questionsMcqChoices` as qmcqc
                LEFT JOIN (
                    SELECT choiceID, count(choiceID) as count, qmcqc.`choice`
                    FROM `omcrs_responseMcq` as rmcq,
                         `omcrs_questionsMcqChoices` as qmcqc
                    WHERE rmcq.`sessionQuestionID` = $sessionQuestionID
                      AND rmcq.`choiceID` = qmcqc.`ID`
                    GROUP BY choiceID
                ) as mcqResponseCount ON qmcqc.`ID` = mcqResponseCount.`choiceID`
                WHERE
                    sq.`questionID` = qmcqc.`questionID`
                    AND sq.`ID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $output = [];
        while($row = $result->fetch_assoc()) {
            $output[] = [
                "choice" => $row["choice"],
                "count" => $row["count"],
            ];
        }

        return $output;
    }

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param mysqli $mysqli
     * @return Response|null ID of existing response
     */
    public static function loadUserResponse($sessionQuestionID, $userID, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);

        // Run query to get the ID
        $sql = "SELECT rmcq.`ID`, rmcq.`choiceID`
                FROM `omcrs_responseMcq` as rmcq
                WHERE rmcq.`sessionQuestionID` = $sessionQuestionID
                AND rmcq.`userID` = $userID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // If the user hasn't submitted a response, return null
        if($result->num_rows <= 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        $response = new Response();
        $response->setResponseID($row["ID"]);
        $response->setResponse($row["choiceID"]);

        return $response;
    }

    public static function update($responseID, $choiceID, $mysqli) {
        $responseID = Database::safe($responseID, $mysqli);
        $choiceID   = Database::safe($choiceID, $mysqli);

        $sql = "UPDATE `omcrs_responseMcq`
                SET
                    `choiceID` = '$choiceID',
                    `time` = '".time()."'
                WHERE `omcrs_responseMcq`.`ID` = $responseID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $responseID;
    }

    /**
     * Load an array of responses for a question
     * @param $sessionQuestionID
     * @param $mysqli
     * @return array|null
     */
    public static function loadResponses($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT r.userID, username, time, choice, r.`choiceID`
                FROM
                    `omcrs_responseMcq` as r,
                    `omcrs_user` as u,
                    `omcrs_questionsMcqChoices` as m
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                  AND r.`userID` = u.`userID`
                  AND m.`ID` = r.`choiceID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $responses = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {

            $response = new Response();
            $response->setResponse($row["choice"]);
            $response->setTime($row["time"]);
            $response->setUsername($row["username"]); // TODO: remove me legacy
            $response->setUser(DatabaseUser::loadDetailsFromUserID($row["userID"], $mysqli));
            $response->setChoiceID($row["choiceID"]);
            $responses[] = $response;
        }

        return $responses;
    }


    /**
     * Load an array of the choices for a mcq or mrq question
     * @param $sessionQuestionID
     * @param $mysqli
     * @param $userID
     * @return array|null
     */
    public static function loadUserChoices($sessionQuestionID, $userID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);
        $userID = Database::safe($userID, $mysqli);

        $sql = "SELECT choice
                FROM
                    `omcrs_responseMcq` as r,
                    `omcrs_user` as u,
                    `omcrs_questionsMcqChoices` as m
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                  AND r.`userID` = u.`userID`
                  AND u.`userID` = $userID
                  AND m.`ID` = r.`choiceID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $responses = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {
            $response = new Response();
            $response->setResponse($row["choice"]);
            array_push($responses, $response);
        }

        return $responses;
    }
}