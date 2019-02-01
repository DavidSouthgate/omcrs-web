<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers SessionTest
 */
final class SessionTest extends TestCase{

    public function testSessionArray(){
        $arr = [];
        $arr["sessionIdentifier"] = null;
        $arr["owner"] = "a";
        $arr["title"] = "test";
        $arr["courseID"] = "aa";
        $arr["allowGuests"] = true;
        $arr["onSessionList"] = true;
        $arr["questionControlMode"] = 1;
        $arr["defaultTimeLimit"] = 0;
        $arr["allowModifyAnswer"] = true;
        $arr["allowQuestionReview"] = true;
        $arr["classDiscussionEnabled"] = true;
        $arr["additionalUsers"] = 1;
        $session = new Session($arr);
        $expected = $session->toArray();

        $this->assertEquals(
            $arr,
            $expected
        );
    }
}
