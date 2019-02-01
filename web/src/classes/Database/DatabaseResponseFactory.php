<?php

class DatabaseResponseFactory
{
    /**
     * Returns a new instance of a DatabaseResponse type object from a given type.
     * @param $type string Type of question
     * @return DatabaseResponse|DatabaseResponseMrq|DatabaseResponseMcq
     * @throws Exception 'DatabaseResponseFactory_ClassNotFoundException': Given type does not translate to login object
     */
    public static function create($type)
    {
        switch ($type) {
            case "mcq":
                return new DatabaseResponseMcq();
                break;
            case "mrq":
                return new DatabaseResponseMrq();
                break;
            case "text":
                return new DatabaseResponse();
                break;
            case "textlong":
                return new DatabaseResponse();
                break;
            default:
                throw new Exception("DatabaseResponseFactory_ClassNotFoundException");
        }
    }
}