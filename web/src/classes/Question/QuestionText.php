<?php

class QuestionText extends Question
{

    /**
     * QuestionText constructor.
     */
    public function __construct($array = []) {
        parent::__construct($array);
        $this->type = "text";
        $this->typeDisplay = "Text";
    }
}