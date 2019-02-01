<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
* @covers DatabaseSessionQuestionScreenshotTest
*/
final class DatabaseSessionQuestionScreenshotTest extends TestCase{

    public function testInsertNull(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionQuestionScreenshot::insert("a", "a", $mysqli));
        $this->assertNull(DatabaseSessionQuestionScreenshot::insert(null, null, $mysqli));
    }
}
