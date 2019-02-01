<?php

class QuestionFactory
{
    /**
     * Returns a new instance of a question type object from a given type.
     * @param $type string Type of question
     * @param $array
     * @return Question
     * @throws Exception 'QuestionFactory_ClassNotFoundException': Given type does not translate to login object
     */
    public static function create($type, $array)
    {
        switch ($type) {
            case "mcq":
                return new QuestionMcq($array);
                break;
            case "mrq":
                return new QuestionMrq($array);
                break;
            case "text":
                return new QuestionText($array);
                break;
            case "textlong":
                return new QuestionTextLong($array);
                break;
            default:
                throw new Exception("QuestionFactory_ClassNotFoundException");
        }
    }
}