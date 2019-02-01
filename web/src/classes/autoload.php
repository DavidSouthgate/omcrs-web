<?php

function autoloadClassesDirectory($dir, $className) {

    // If class exists in current directory, require class file and return.
    if(file_exists($dir . "/" . $className . ".php")) {
        require_once($dir . "/" . $className . ".php");
        return;
    }

    // Get all subdirectories of current directory
    $dirs = array_filter(glob($dir."/*"), 'is_dir');

    // Loop for every subdirectory and recur function into that directory
    foreach ($dirs as $dir) {
        autoloadClassesDirectory($dir, $className);
    }
}

spl_autoload_register(function ($className) {
    autoloadClassesDirectory(dirname(__FILE__), $className);
});