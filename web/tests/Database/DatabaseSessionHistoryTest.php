<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionHistoryTest
 */
final class DatabaseSessionHistoryTest extends TestCase{

    public function testNullInsert(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionHistory::insert(new User(), new Session(), $mysqli));
    }
}
