<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseQuestionTest
 */
final class DatabaseQuestionTest extends TestCase
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

        $this->assertNotNull($question->getSessionQuestionID());
        $this->assertNotNull($question->getQuestionID());

        DatabaseSessionQuestion::delete($question->getSessionQuestionID(), $mysqli);
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

        $this->assertNotNull($question->getSessionQuestionID());
        $this->assertNotNull($question->getQuestionID());

        $question->setQuestion("new");
        DatabaseQuestion::update($question, $mysqli);

        $this->assertEquals(
            $question->getQuestion(),
            "new"
        );

        DatabaseSessionQuestion::delete($question->getSessionQuestionID(), $mysqli);
        //TODO need to delete from questions table
    }

}
