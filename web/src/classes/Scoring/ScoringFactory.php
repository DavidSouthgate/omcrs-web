<?php

class ScoringFactory
{
    /**
     * Returns a new instance of a scoring type object from a given type.
     * @param $type string Type of scoring
     * @return Scoring
     * @throws Exception 'ScoringFactory_ClassNotFoundException': Given type does not translate to scoring object
     */
    public static function create($type)
    {
        switch ($type) {
            case "default":
                return new ScoringDefault();
                break;
            default:
                throw new Exception("ScoringFactory_ClassNotFoundException");
        }
    }
}