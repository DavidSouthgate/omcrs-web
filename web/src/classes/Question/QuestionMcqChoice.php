<?php

class QuestionMcqChoice
{
    private $choiceID = null;
    private $choice = null;
    private $correct = false;

    /**
     * QuestionMcqChoice constructor.
     * @param null $choice
     * @param bool $correct
     */
    public function __construct($choice, $correct = false, $choiceID = null) {
        $this->choice = $choice;
        $this->correct = boolval($correct);
        $this->choiceID = $choiceID;
    }

    public function toArray() {
        $output["choice"] = $this->choice;
        $output["correct"] = $this->correct;
        return $output;
    }

    /**
     * @return null
     */
    public function getChoiceID() {
        return $this->choiceID;
    }

    /**
     * @param null $choiceID
     */
    public function setChoiceID($choiceID) {
        $this->choiceID = $choiceID;
    }

    /**
     * @return null
     */
    public function getChoice() {
        return $this->choice;
    }

    /**
     * @param null $choice
     */
    public function setChoice($choice) {
        $this->choice = $choice;
    }

    /**
     * @return bool
     */
    public function isCorrect() {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect($correct) {
        $this->correct = boolval($correct);
    }
}