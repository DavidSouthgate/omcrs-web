<?php

class QuestionTextLong extends QuestionText
{
    /**
     * QuestionText constructor.
     */
    public function __construct($array = []) {
        parent::__construct($array);
        $this->type = "textlong";
        $this->typeDisplay = "Long Text";
    }
}