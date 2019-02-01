<?php

class QuestionMrq extends QuestionMcq
{
    /**
     * QuestionMrq constructor.
     * @param array|null $array
     */
    public function __construct($array = []) {
        parent::__construct($array);
        $this->type = "mrq";
        $this->typeDisplay = "Multiple Response";
    }
}