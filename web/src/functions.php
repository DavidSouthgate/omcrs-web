<?php

/**
 * Takes a string and converts it to a boolean with special cases for certain strings
 * @param string $text
 * @return bool
 */
function text2bool($text) {

    // If a value that means true
    if(
        $text === true      ||
        $text === 1         ||
        $text === "1"       ||
        $text === "true"    ||
        $text === "t"
    ) return true;

    // If a value that means false
    if(
        $text === false     ||
        $text === 0         ||
        $text === "0"       ||
        $text === "false"   ||
        $text === "f"
    ) return false;

    // Otherwise use default boolval function to determine boolean value
    return boolval($text);
}

/**
 * Convert a bool to a string usable in a query
 * @param bool $bool
 * @return string
 */
function bool2dbString($bool) {
    return $bool ? "1" : "0";
}

/**
 * Whether this is running as a desktop app
 * @return bool
 */
function isDesktopApp() {
    return strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "omcrs") !== false;
}

function basicGenericError($title, $message, $code = 500, $permanent = false) {
    echo "<!DOCTYPE html>
                <html>
                    <head>
                        <title>$title</title>
                        <style type=\"text/css\">
                            body {
                                font-family: sans-serif,helvetica;
                            }
                            
                            #error {
                                border: grey 1px solid;
                                width: 500px;
                                margin-left: auto;
                                margin-right: auto;
                                margin-top: 100px;
                                padding: 10px;
                            }
                            
                                #error #errorTitle {
                                    font-size: 30px;
                                }
                                #error #errorMessage p {
                                    margin-bottom:0px;
                                }
                                #error #errorMessage li {
                                    margin-left: 20px;
                                }
                        </style>
                    </head>
                    <body>
                        <div id=\"error\">
                            <div id=\"errorTitle\">
                                $title
                            </div>
                            <div id=\"errorMessage\">
                                <p>
                                    $message
                                </p>
                                <p>
                                    You can either
                                    <a href=\"javascript:history.back()\">go back</a> or try again later!
                                </p>
                            </div>
                        </div>
                    </body>
                </html>";
    die();
}

function require_once_error($require, $title, $message) {
    try {
        if(!@include_once($require)) {
            throw new Exception("'$require' does not exist");
        }
    }
    catch(Exception $e) {
        basicGenericError($title, $message);
        die();
    }
}

function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/=', '._-');
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '._-', '+/='));
}