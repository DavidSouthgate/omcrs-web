<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers QuestionTest
 */
final class QuestionTest extends TestCase
{

    public function testQuestionToArray(){
        $questionArr = [];
        $questionArr["sessionQuestionID"] = 3;
        $questionArr["type"] = "mcq";
        $questionArr["question"] = "is this a test";
        $questionArr["created"] = 2017;
        $questionArr["lastUpdate"] = 2017;
        $questionArr["active"] = true;
        $question = new Question($questionArr);
        $arr = $question->toArray();

        $this->assertEquals(
            $arr,
            $questionArr
        );
    }

    public function testQuestionType(){
        $questionArr = [];
        $questionMcq = QuestionFactory::create("mcq", $questionArr);
        $questionText = QuestionFactory::create("text", $questionArr);
        $questionLong = QuestionFactory::create("textlong", $questionArr);
        $this->assertInstanceOf(
            QuestionMcq::class,
            $questionMcq
        );
        $this->assertInstanceOf(
            QuestionText::class,
            $questionText
        );
        $this->assertInstanceOf(
            QuestionTextLong::class,
            $questionLong
        );
    }

    public function testMcq(){
        $questionArr = [];
        $questionMcq = new QuestionMcq($questionArr);
        $questionMcq->addChoice("a");
        $this->assertEquals(
            count($questionMcq->getChoices()),
            1
        );
    }

    public function testMcqChoice(){
        $choice = new QuestionMcqChoice("a", "true");
        $arr = $choice->toArray();
        $this->assertEquals(
            $arr,
            array("choice" => "a", "correct" => true)
        );
    }
}
