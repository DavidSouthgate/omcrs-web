<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionIdentifierTest
 */
final class DatabaseSessionIdentifierTest extends TestCase{

    public function testInvalidSessionID(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $this->assertNull(DatabaseSessionIdentifier::loadSession("asdasdas", $mysqli));
        $this->assertNull(DatabaseSessionIdentifier::loadSession(null, $mysqli));
    }
}
