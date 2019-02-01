<?php
/**
 * This script is used by the CI test suite to wait for the database to be up and running before running any tests
 */

require_once("autoload.php");

// Ensure mysqli throws exceptions
mysqli_report(MYSQLI_REPORT_STRICT);

$mysqli = null;
$time = time();
$lastError = 0;

// While a connection hasn't been made to the database and it hasn't been 5 minutes
while(!$mysqli && time() < $time + 60*5) {
    try {
        $mysqli = new mysqli($config["database"]["host"], $config["database"]["username"], $config["database"]["password"], $config["database"]["name"]);
        echo "Successfully connected to the database.\r\n";
    }
    catch(Exception $e) {

        // If an error hasn't been posted in the last 20 seconds
        if(time() > $lastError + 20) {
            echo $e->getMessage() . "\r\n";
            echo "Could not connect to database. Trying again.\r\n";
            $lastError = time();
        }

        $mysqli = null;
    }
}

// If a database connection wasn't made, exit with error code
if(!$mysqli) {
    echo "Could not connect to database. Script timed out. Aborting.\r\n";
    exit(1);
}

// Exit successfully
exit;