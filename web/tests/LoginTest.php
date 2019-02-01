<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/TestHelper.php");

/**
 * @covers LoginTest
 */
final class LoginTest extends TestCase{

    public function testLogin(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $_SESSION["omcrs_user"] = $user->toArray();

        $this->assertInstanceOf(
            User::class,
            Login::checkUserLoggedIn()
        );
    }

    public function testAnonymousUser(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        Login::anonymousUserCreate("test", $mysqli);

        $this->assertInstanceOf(
            User::class,
            Login::checkUserLoggedIn()
        );
    }
}
