<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use \PhpOffice\PhpSpreadsheet\Calculation\LookupRef;

class PageSessionExport
{
    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function styleHeader($columnIndex, $rowIndex, &$sheet) {

        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => array(
                    'argb' => 'FFffff99',
                ),
                'endColor' => array(
                    'argb' => 'FFffff99',
                ),
            ),
        );

        try {
            $sheet->getStyleByColumnAndRow($columnIndex, $rowIndex)->applyFromArray($styleArray);
        }
        catch(PhpOffice\PhpSpreadsheet\Style\Exception $e) {}
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param $value
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function setHeader($columnIndex, $rowIndex, $value, &$sheet) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
        self::styleHeader($columnIndex, $rowIndex, $sheet);
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param $value
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function setDataCell($columnIndex, $rowIndex, $value, &$sheet) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
        self::styleDataCell($columnIndex, $rowIndex, $sheet);
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function styleDataCell($columnIndex, $rowIndex, &$sheet) {

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        try {
            $sheet->getStyleByColumnAndRow($columnIndex, $rowIndex)->applyFromArray($styleArray);
        }
        catch(PhpOffice\PhpSpreadsheet\Style\Exception $e) {}
    }

    /**
     * Display text responses in sheet
     * @param Question|QuestionMcq|QuestionMrq|QuestionText|QuestionTextLong $question
     * @param Session $session
     * @param int $questionNumber
     * @param array $users
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $config
     * @param mysqli $mysqli
     */
    private static function text($question, $session, $questionNumber, &$users, &$sheet, $config, $mysqli) {
        $sessionQuestionID = $question->getSessionQuestionID();

        try {
            $dbr = DatabaseResponseFactory::create($question->getType());
        }
        catch(Exception $e) {
            $dbr = new DatabaseResponse();
        }

        $responses = $dbr::loadResponses($sessionQuestionID, $mysqli);

        $i = 8;
        foreach($responses as $response) {

            $user = $response->getUser() === null ? new User() : $response->getUser();

            self::setDataCell(1, $i, $user->isGuest() ? "Guest" : $user->getUsername(), $sheet);
            self::setDataCell(2, $i, $user->getFullName(), $sheet);
            self::setDataCell(3, $i, date($config["datetime"]["datetime"]["long"], $response->getTime()), $sheet);
            self::setDataCell(4, $i, $response->getResponse(), $sheet);

            // Get the scoring object from the session
            $scoring = $session->getScoring();
            $score = 0;
            $correct = null;

            // If this is a question type with choices
            if($question->getType() == "mcq") {

                $correct = true;

                // Foreach question choice
                foreach($question->getChoices() as $choice) {

                    if($response->getChoiceID() == $choice->getChoiceID() && $choice->isCorrect() == false) {
                        $correct = false;
                    }
                }

                $score = $scoring::score($correct, count($question->getChoices()));
            }

            elseif ($question->getType() == "mrq"){

                //get the user choice IDs and split the string
                $userChoices = explode(", ", $response->getChoiceID());

                $userCorrectCount = 0;
                $userIncorrectCount = 0;
                $correctTotal = 0;
                $optionsTotal = count($question->getChoices());

                // Foreach question choice
                foreach($question->getChoices() as $choice) {

                    // If the user answered this, and it is correct. Increment counter
                    if(in_array($choice->getChoiceID(), $userChoices) && $choice->isCorrect()) {
                        $userCorrectCount++;
                    }

                    // If the user answered this, and it is incorrect. Increment counter
                    elseif(in_array($choice->getChoiceID(), $userChoices) && !$choice->isCorrect()) {
                        $userIncorrectCount++;
                    }

                    // If this answer is correct. Increment counter
                    if($choice->isCorrect()) {
                        $correctTotal++;
                    }
                }
                
                $score = $scoring::scoreMultiple($userCorrectCount, $userIncorrectCount, $correctTotal, $optionsTotal);

                $correct = $score==1 ? true : false;
            }

            // If this answer is correct or incorrect
            if($correct === true || $correct === false)
                self::setDataCell(5, $i, $correct ? "Yes" : "No", $sheet);

            // Otherwise, use N/A
            else
                self::setDataCell(5, $i, "N/A", $sheet);

            self::setDataCell(6, $i, $score, $sheet);

            // If this user isn't in the array of users yet, add it
            if(!$user->isGuest() && !key_exists($user->getUsername(), $users)) {
                $users[$user->getUsername()]["fullname"] = $user->getFullName();
            }

            // Store score for user
            $users[$user->getUsername()]["scores"][$questionNumber] = $score;

            $i++;
        }
    }

    /**
     * @param Session $session
     * @param int $questionCount
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $users
     * @param array $config
     * @param mysqli $mysqli
     */
    private static function overviewSheet($session, $questionCount, $users, &$sheet, $config, $mysqli) {

        // Add session details headings
        self::setHeader(1, 1, "Session Title", $sheet);
        self::setHeader(1, 2, "Created", $sheet);

        // Add session details values
        self::setDataCell(2, 1, $session->getTitle(), $sheet);
        self::setDataCell(2, 2, date($config["datetime"]["datetime"]["long"], $session->getCreated()), $sheet);

        // Add overview headings
        self::setHeader(1, 4, "Username", $sheet);
        self::setHeader(2, 4, "Full Name", $sheet);

        // Add column heading for every question
        for($i = 1; $i <= $questionCount; $i++)
            self::setHeader($i + 2, 4, "Q$i", $sheet);

        // Add column heading for total score
        self::setHeader($questionCount + 3, 4, "Total Marks", $sheet);
        $column = $sheet->getCellByColumnAndRow($questionCount + 3, 4)->getColumn();
        $sheet->getColumnDimension($column)->setAutoSize(true);

        // Foreach user, add to overview
        $row = 5;
        foreach($users as $username => $user) {

            // If no valid username, goto next user
            if(!$username)
                continue;

            $fullName = $user["fullname"];

            // Add username and full name
            self::setDataCell(1, $row, $username, $sheet);
            self::setDataCell(2, $row, $fullName, $sheet);

            // Add lookup formula for each question
            for($i = 1; $i <= $questionCount; $i++) {

                $score = key_exists($i, $user["scores"]) ? $user["scores"][$i] : " ";
                self::setDataCell($i + 2, $row, $score, $sheet);
            }

            // Add sum function for total column
            self::setDataCell($questionCount + 3, $row, array_sum($user["scores"]), $sheet);

            $row++;
        }

        // Auto resize all columns
        for($i = 1; $i <= 2; $i++)
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

        for($i = 3; $i <= $questionCount + 3; $i++)
            $sheet->getColumnDimensionByColumn($i)->setWidth(6);
    }

    /**
     * @param Question $question
     * @param Session $session
     * @param int $questionNumber
     * @param array $users
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $config
     * @param mysqli $mysqli
     */
    private static function questionDetailsSheet($question, $session, $questionNumber, &$users, &$sheet, $config, $mysqli) {

        $sessionQuestionID = $question->getSessionQuestionID();

        // Add question details headings
        self::setHeader(1, 1, "Question Text", $sheet);
        self::setHeader(1, 2, "Type", $sheet);
        self::setHeader(1, 3, "Correct Answer(s)", $sheet);
        self::setHeader(1, 4, "Date/Time", $sheet);
        self::setHeader(1, 5, "Total Responses", $sheet);

        // Add question details values
        self::setDataCell(2, 1, $question->getQuestion(), $sheet);
        self::setDataCell(2, 2, $question->getTypeDisplay() . " Question", $sheet);
        // If it is a mcq or mrq get the correct answers and display them
        if($question->getType() == "mcq" || $question->getType() == "mrq"){
            $correctChoices = DatabaseResponseMrq::getCorrectChoices($question->getQuestionID(), $mysqli);
        }
        else{
            $correctChoices = "";
        }
        self::setDataCell(2, 3, $correctChoices, $sheet);
        self::setDataCell(2, 4, date($config["datetime"]["datetime"]["long"], $question->getCreated()), $sheet);
        $row = 7;
        try{
            $responseClass = DatabaseResponseFactory::create($question->getType());
            $responses = count($responseClass::loadResponses($question->getSessionQuestionID(), $mysqli));
            self::setDataCell(2, 5, $responses, $sheet);
        }
        catch(Exception $e){
        }

        // Add headings
        self::setHeader(1, $row, "Username", $sheet);
        self::setHeader(2, $row, "Full Name", $sheet);
        self::setHeader(3, $row, "Date/Time", $sheet);
        self::setHeader(4, $row, "Response", $sheet);
        self::setHeader(5, $row, "Correct?", $sheet);
        self::setHeader(6, $row, "Points", $sheet);

        // Auto resize all columns
        for($i = 1; $i <= 5; $i++)
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

        self::text($question, $session, $questionNumber, $users, $sheet, $config, $mysqli);
    }


    public static function export($sessionIdentifier) {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load the session ID
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();

        $session = DatabaseSession::loadSession($sessionID, $mysqli);

        // Load session questions
        $questions = DatabaseSessionQuestion::loadSessionQuestions($sessionID, $mysqli);

        // Get the default sheet as the overview sheet
        $overviewSheet = $spreadsheet->getActiveSheet();

        $i = count($questions["questions"]);

        $users = [];

        // For each question
        foreach ($questions["questions"] as $question) {
            /** @var $question Question */

            $sheet = $spreadsheet->createSheet();

            // Create a new sheet
            $sheet->setTitle("Q" . $i);

            self::questionDetailsSheet($question, $session, $i, $users, $sheet, $config, $mysqli);

            $i--;
        }

        $overviewSheet->setTitle("Overview");
        self::overviewSheet($session, count($questions["questions"]), $users, $overviewSheet, $config, $mysqli);

        // Create a new temp file
        $tempFile = tempnam("/tmp", "");

        // Save the spreadsheet to a temporary file
        $writer = new Xls($spreadsheet);
        $writer->save($tempFile);

        // Generate the report filename
        // E.g. OMCRS_1_Session_Title.xls
        $filename = str_replace(" ", "_", "OMCRS " . $sessionIdentifier . " " . $session->getTitle());
        $filename = preg_replace('/[^A-Za-z0-9_"\']/', '', $filename);
        $filename .= ".xls";

        // Output the spreadsheet file for download
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/force-download");
        header("Content-Length: " . filesize($tempFile));
        header("Connection: close");
        readfile($tempFile);

        // Remove temp file
        unlink($tempFile);
    }
}