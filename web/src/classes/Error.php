<?php

class Error
{

    /**
     * @param Exception $e
     * @param string $line
     * @param string $file
     */
    public static function exception($e, $line, $file) {
        error_log(basename($file) . " on line $line. " . $e->getMessage());
        PageError::error500();
        die();
    }
}