<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseResponseTest
 */
final class DatabaseResponseTest extends TestCase
{
    public function testInsert(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["question"] = "name";
        $array["type"] = "mcq";
        $question = new Question($array);

        DatabaseQuestion::insert($question, $mysqli);

        $this->assertNotNull(
            DatabaseResponse::insert($question->getSessionQuestionID(), $user->getId(), "test", $mysqli)
        );
    }

    public function testLoad(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["question"] = "name";
        $array["type"] = "mcq";
        $question = new Question($array);

        DatabaseQuestion::insert($question, $mysqli);
        $this->assertNotNull(
            DatabaseResponse::insert($question->getSessionQuestionID(), $user->getId(), "test", $mysqli)
        );

        $response = DatabaseResponse::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);

        $this->assertNotNull($response);

        $this->assertEquals(
            $response->getResponse(),
            "test"
        );
    }

    public function testUpdate(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["question"] = "name";
        $array["type"] = "mcq";
        $question = new Question($array);

        DatabaseQuestion::insert($question, $mysqli);
        DatabaseResponse::insert($question->getSessionQuestionID(), $user->getId(), "test", $mysqli);

        $responseID = DatabaseResponse::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli)->getResponseID();
        $updated = DatabaseResponse::update($responseID, "update", $mysqli);

        $response = DatabaseResponse::loadUserResponse($question->getSessionQuestionID(), $user->getId(), $mysqli);

        $this->assertNotNull($updated);

        $this->assertEquals(
            $response->getResponse(),
            "update"
        );
        DatabaseResponse::update($responseID, "test", $mysqli);
    }
}
