<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseResponseMrqTest
 */
final class DatabaseResponseMrqTest extends TestCase{

    public function testInsertNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMrq::insert(null,
                null, null, null, $mysqli));

        $this->assertNull(
            DatabaseResponseMrq::insert(0,
                0, null, new QuestionMrq(), $mysqli));
    }

    public function testUpdateNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMrq::update(null,
                null, null, null, $mysqli));

        $this->assertNull(
            DatabaseResponseMrq::insert(0,
                0, null, new QuestionMrq(), $mysqli));
    }

    public function testLoadUserResponsesOnNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMrq::loadUserResponses(0, 0, $mysqli)
        );

        $this->assertNull(
            DatabaseResponseMrq::loadUserResponses(null, null, $mysqli)
        );
    }

    public function testNonExistentLoadResponses(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $array = [];
        $array["sessionQuestionID"] = 1;
        $question = new Question($array);

        $this->assertEquals(DatabaseResponseMrq::loadResponses(1, $mysqli), []);
    }


}
