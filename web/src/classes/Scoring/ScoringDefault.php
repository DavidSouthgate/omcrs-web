<?php

class ScoringDefault implements Scoring
{
    /**
     * Scoring for questions that allow the user to pick a single answer
     * @param bool $correct
     * @param int $optionsTotal
     * @return float
     */
    public static function score($correct, $optionsTotal = null) {
        return $correct ? 1 : 0;
    }

    /**
     * Scoring for questions that allow the user to pick multiple answers
     * @param int $userCorrectCount     The number of items the user got correct
     * @param int $userIncorrectCount   The number of items the user got incorrect
     * @param int $correctTotal         The total number of correct items
     * @param int $optionsTotal         The total number of options
     * @return float
     */
    public static function scoreMultiple($userCorrectCount, $userIncorrectCount, $correctTotal, $optionsTotal) {
        if($correctTotal === 0 or $userCorrectCount === 0) {
            return 0;
        }

        return round($userCorrectCount/($userCorrectCount + $userIncorrectCount),2);
    }
}