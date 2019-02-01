<?php

class StopWords
{
    const words = array("a", "about", "all", "am", "an", "and", "any", "are", "aren", "as", "at",
    "be", "been", "being", "below", "between", "both", "but", "by", "can", "cannot",
    "could", "couldn", "did", "didn", "do", "does", "doesn't", "doing", "don",
    "down", "each", "few", "for", "from", "further", "had", "hadn", "has", "hasn",
    "have", "haven", "having", "he", "he", "he", "he", "her", "here", "here", "on", "once", "only",
    "hers", "herself", "him", "himself", "his", "how", "i", "not", "of", "off",
    "if", "in", "into", "is", "isn", "it", "it", "its", "itself", "let", "me", "more",
    "most", "mustn", "my", "m", "s", "t", "d", "ll", "ve", "re", "myself", "no", "nor",
    "or", "other", "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan",
    "she", "she", "she", "she", "should", "shouldn", "so", "some", "such", "through", "to",
    "there", "there", "these", "they", "this", "those",
    "we", "were", "weren", "what", "what s", "when",
    "where", "which", "too", "up", "while", "who", "whom", "why", "with",
    "would", "wouldn", "you", "was", "wasn",
    "your", "yours", "yourself", "yourselves", "than", "that", "the", "their",
    "theirs", "them", "themselves", "then", "");


    public static function isInStop($word){
        return in_array($word, self::getStop());
    }

    public static function removeStop($dict){
        $i = 0;
        $arr = [];
        foreach($dict as $key => $value) {
            $value = strtolower($value);
            if(!self::isInStop($value)){
                $arr[$i] = $value;
                $i++;
            }
        }
        return $arr;
    }

    public static function getStop(){
        return self::words;
    }
}