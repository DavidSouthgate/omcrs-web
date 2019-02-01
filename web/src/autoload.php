<?php
require_once("functions.php");
require_once("classes/autoload.php");

try {
    if(!@include_once("vendor/autoload.php")) {
        throw new Exception("'vendor/autoload.php' does not exist");
    }
}
catch(Exception $e) {
    basicGenericError(
        "PHP library dependencies not installed",
        "These can be installed using <a href='https://getcomposer.org/'>composer</a> and running 'composer install'
                  inside the web source code directory"
    );
    die();
}

try {
    if(!@include_once("config.php")) {
        throw new Exception("'config.php' does not exist");
    }
}
catch(Exception $e) {
    basicGenericError(
        "OMCRS config file not found.",
        "A config file is required to edit OMCRS. A sample config file can be found in sample.config.php. Your
                  final config file should be called config.php in the same directory."
    );
    die();
}