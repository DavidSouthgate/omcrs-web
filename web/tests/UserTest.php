<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers UserTest
 */
final class UserTest extends TestCase{

    public function testUser(){
        $array["id"] = 3;
        $array["username"] = "test";
        $array["givenName"] = "given";
        $array["surname"] = "surname";
        $array["email"] = "test@test.com";
        $array["isSessionCreator"] = true;
        $array["isAdmin"] = true;
        $array["isGuest"] = false;
        $user = new User($array);
        $expected = $user->toArray();

        $this->assertEquals(
            $array,
            $expected
        );
    }
}
