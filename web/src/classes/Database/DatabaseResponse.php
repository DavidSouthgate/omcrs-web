<?php

class DatabaseResponse
{

    /**
     * @param int $sessionQuestionID
     * @param int $userID
     * @param string $response
     * @param mysqli $mysqli
     * @return int|null
     */
    public static function insert($sessionQuestionID, $userID, $response, $mysqli) {
        $sessionQuestionID  = Database::safe($sessionQuestionID, $mysqli);
        $userID             = Database::safe($userID, $mysqli);
        $response           = Database::safe($response, $mysqli);

        $sql = "INSERT INTO `omcrs_response` (`time`, `sessionQuestionID`, `userID`, `response`) 
                VALUES ('".time()."', '$sessionQuestionID', '$userID', '$response')";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $mysqli->insert_id;
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
        $sql = "SELECT r.`ID`, r.`response`
                FROM `omcrs_response` as r
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                AND r.`userID` = $userID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        // If the user hasn't submitted a response, return null
        if($result->num_rows <= 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        $response = new Response();
        $response->setResponseID($row["ID"]);
        $response->setResponse($row["response"]);

        return $response;
    }

    public static function update($responseID, $response, $mysqli) {
        $responseID = Database::safe($responseID, $mysqli);
        $response   = Database::safe($response, $mysqli);

        $sql = "UPDATE `omcrs_response`
                SET
                    `response` = '$response',
                    `time` = '".time()."'
                WHERE `omcrs_response`.`ID` = $responseID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        return $responseID;
    }

    /**
     * Load an array of responses for a question
     * @param $sessionQuestionID
     * @param $mysqli
     * @return Response[]|null
     */
    public static function loadResponses($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT r.`userID`, username, time, response
                FROM
                    `omcrs_response` as r,
                    `omcrs_user` as u
                WHERE r.`sessionQuestionID` = $sessionQuestionID
                  AND r.`userID` = u.`userID`";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $responses = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {

            $response = new Response();
            $response->setResponseID($row["ID"]);
            $response->setResponse($row["response"]);
            $response->setTime($row["time"]);
            $response->setUsername($row["username"]);       // TODO: LEGACY REMOVE ME
            $response->setUser(DatabaseUser::loadDetailsFromUserID($row["userID"], $mysqli));

            $responses[] = $response;
        }

        return $responses;
    }

    public static function loadWordCloud($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT response
                FROM `omcrs_response` as r
                WHERE r.`sessionQuestionID` = $sessionQuestionID";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $dict = [];

        // Foreach row returned
        while($row = $result->fetch_assoc()) {

            // Get the response
            $response = $row["response"];

            // Remove everything except letters
            $response = preg_replace("/[^a-z]+/i", " ", $response);

            $responseExplode = explode(" ", $response);

            $responseExplode = StopWords::removeStop($responseExplode);

            foreach($responseExplode as $key => $value) {

                // Make only the first letter uppercase
                //$value = strtolower($value);

                $dict[$value] = isset($dict[$value]) ? $dict[$value] + 1 : 1;
            }
        }

//        print_r($dict);
//        die();
//        //$dict = StopWords::removeStop($dict);

        $output = [];

        foreach($dict as $key => $value) {
            $word = [];
            $word["text"] = $key;
            $word["size"] = $value;
            $output[] = $word;
        }

        // Sort the words by size descending
        $output = self::arraySort($output, "size", SORT_DESC);

        return $output;
    }

    private static function arraySort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[] = $array[$k];
            }
        }

        return $new_array;
    }
}
