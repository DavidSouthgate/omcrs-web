<?php
use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/TestHelper.php");

/**
 * @covers LoginTypeAnyTest
 */
final class LoginTypeAnyTest extends TestCase{

    public function testCheckLogin(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $user = LoginTypeAny::checkLogin("teach", "", $config);

        $this->assertTrue($user->isSessionCreator());

        $user = LoginTypeAny::checkLogin("admin", "", $config);

        $this->assertTrue($user->isSessionCreator());
        $this->assertTrue($user->isAdmin());
    }
}
