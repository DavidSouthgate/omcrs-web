<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionQuestionTest
 */
final class DatabaseSessionQuestionTest extends TestCase{

    public function testInsertNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::insert("a", "a", $mysqli));
        $this->assertNull(DatabaseSessionQuestion::insert(null, null, $mysqli));
    }

    public function testDeleteNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::delete("a", $mysqli));
        $this->assertNull(DatabaseSessionQuestion::delete(null, $mysqli));
    }

    public function testLoadQuestionNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::loadQuestion("a", $mysqli));
        $this->assertNull(DatabaseSessionQuestion::loadQuestion(null, $mysqli));
        $this->assertNull(DatabaseSessionQuestion::loadQuestion(-1, $mysqli));
    }

    public function testLoadActiveQuestionsNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertEquals(DatabaseSessionQuestion::loadAllActiveQuestions("a", $mysqli), null);
        $this->assertEquals(DatabaseSessionQuestion::loadAllActiveQuestions(null, $mysqli), null);
    }

    public function testLoadActiveQuestion(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertEquals(DatabaseSessionQuestion::loadActiveQuestion("a", $question=1, $mysqli), null);
        $this->assertEquals(DatabaseSessionQuestion::loadActiveQuestion(null, $question=1, $mysqli), null);
    }

    public function testCountActiveQuestions(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::countActiveQuestions("a", $mysqli));
        $this->assertNull(DatabaseSessionQuestion::countActiveQuestions(null, $mysqli));
    }

    public function testQuestionActivate(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::questionActivate("a", true, $mysqli));
        $this->assertNull(DatabaseSessionQuestion::questionActivate(null, true,  $mysqli));
    }

    public function testUsersNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestion::users("a", "b", $mysqli));
        $this->assertNull(DatabaseSessionQuestion::users(null, null,  $mysqli));
    }
}
