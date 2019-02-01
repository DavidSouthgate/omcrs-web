<?php

class QuestionMcq extends Question
{
    /** @var array */
    private $choices = [];

    /**
     * QuestionMcq constructor.
     * @param array|null $array
     */
    public function __construct($array = []) {
        parent::__construct($array);
        $this->setChoices(isset($array["choices"]) ? $array["choices"] : $this->getChoices());
        $this->type = "mcq";
        $this->typeDisplay = "Multiple Choice";
    }

    /**
     * @return array
     */
    public function toArray() {
        $output = parent::toArray();

        $output["choices"] = [];
        foreach($this->choices as $choice) {
            array_push($output["choices"], $choice->toArray());
        }

        return $output;
    }

    /**
     * @param $choice
     * @param bool $correct
     */
    public function addChoice($choice, $correct = false, $choiceID = null) {
        array_push($this->choices, new QuestionMcqChoice($choice, $correct, $choiceID));
    }

    /**
     * @return QuestionMcqChoice[]
     */
    public function getChoices() {
        return $this->choices;
    }

    /**
     * @param QuestionMcqChoice[] $choices
     */
    public function setChoices($choices) {
        $this->choices = $choices;
    }
}