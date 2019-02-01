<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionTest
 */
final class DatabaseSessionTest extends TestCase
{
    public function testInsert()
    {

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["owner"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);
        $this->assertNotNull($sessionIdentifier);
    }

    public function testLoad(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["owner"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);

        $this->assertNotNull(DatabaseSession::loadSession
        (DatabaseSessionIdentifier::loadSessionID
        ($sessionIdentifier, $mysqli), $mysqli));

        $this->assertNotNull(DatabaseSession::loadUserSessions($user->getId(), $mysqli));
    }

    public function testUpdate(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["owner"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);
        $session->setTitle("test");

        DatabaseSession::update($session, $mysqli);

        $this->assertEquals(
            DatabaseSession::loadSession
            (DatabaseSessionIdentifier::loadSessionID
            ($sessionIdentifier, $mysqli), $mysqli)->getTitle(),
            "test"
        );
    }

    public function testDelete(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["owner"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);

        $this->assertTrue(DatabaseSessionIdentifier::delete($sessionIdentifier, $mysqli));
    }
}
