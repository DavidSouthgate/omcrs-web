<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers AlertTest
 */
final class AlertTest extends TestCase
{

    public function testCanBeCreatedFromValidArray() {

        $array = [];
        $array["title"]        = "Alert Title";
        $array["message"]      = "Alert Message";
        $array["type"]         = "danger";
        $array["dismissable"]  = true;

        $alert = new Alert($array);

        $this->assertInstanceOf(
            Alert::class,
            $alert
        );

        $this->assertEquals(
            $array,
            $alert->toArray()
        );
    }

    public function testAlertToArray(){

        $array = [];
        $array["title"]        = "Test";
        $array["test"]         = false;
        $alert = new Alert($array);

        Alert::displayAlertSession($alert, -20);

        $arr = [];
        $arr["title"] = "Test";
        $arr["message"] = null;
        $arr["type"] = null;
        $arr["dismissable"]  = false;
        $this->assertEquals(
            $_SESSION["omcrs_alert"]["alert"],
            $arr
        );

        $this->assertNotEquals(
            $_SESSION["omcrs_alert"]["expire"],
            -20
        );
    }
}
