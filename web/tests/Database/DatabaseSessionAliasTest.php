<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionAliasTest
 */
final class DatabaseSessionAliasTest extends TestCase{

    public function testNullAlias(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(DatabaseSessionAlias::loadSessionID(null, $mysqli));
        $this->assertNull(DatabaseSessionAlias::loadSessionID("asbsd", $mysqli));
    }
}
