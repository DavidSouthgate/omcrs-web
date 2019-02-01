<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseResponseMcqTest
 */
final class DatabaseResponseMcqTest extends TestCase
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
        $question = new QuestionMcq($array);
        $question->addChoice("A", false, 1);
        $question->addChoice("B", false, 2);

        DatabaseQuestion::insert($question, $mysqli);

        $result = DatabaseResponseMcq::insert($question->getSessionQuestionID(),
            $user->getId(),
            1,
            $mysqli);

        $this->assertNotNull($result);
    }
}
