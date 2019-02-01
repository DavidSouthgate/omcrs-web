<?php
$config = [];

$config["version"]                       = "1.0.0";
$config["title"]                         = "OMCRS";
$config["baseUrl"]                       = isset($_SERVER['HTTPS']) ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . "/";
$config["login"]["type"]                 = "some";

// Whether user's can register new accounts (Only for native login system)
$config["login"]["register"]             = true;

$config["datetime"]["date"]["short"]     = "d/m/y";
$config["datetime"]["date"]["long"]      = "d F Y \\a\\t";
$config["datetime"]["time"]["short"]     = "H:i";
$config["datetime"]["time"]["long"]      = "H:i";
$config["datetime"]["datetime"]["short"] = $config["datetime"]["date"]["short"] . " " . $config["datetime"]["time"]["short"];
$config["datetime"]["datetime"]["long"]  = $config["datetime"]["date"]["long"] . " " . $config["datetime"]["time"]["long"];

// Get database details from docker
$config["database"]["host"]              = getenv("MYSQL_HOST");
$config["database"]["username"]          = getenv("MYSQL_USER");
$config["database"]["password"]          = getenv("MYSQL_PASSWORD");
$config["database"]["name"]              = getenv("MYSQL_DATABASE");

// Basic LDAP details
$config["ldap"]["host"]                  = "127.0.0.1";
$config["ldap"]["context"]               = "o=Example";

// Manual username password combos. Used for initial setting up of admin users.
$config["user"]["users"]["admin"]        = "dufbYqFuU4EV8WgE";

// Users who should always be admin
$config["user"]["admin"][0]               = "admin";

// Details used for LDAP bind
//$config["ldap"]["bind"]["user"] = "";
//$config["ldap"]["bind"]["pass"] = "";

// LDAP fields and values that result in sessionCreator (teacher) status
$config["ldap"]["sessionCreatorRules"]   = array();
$config["ldap"]["sessionCreatorRules"][] = array("field" => "dn", "contains" => "ou=staff");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "homezipcode", "match" => "PGR");
$config["ldap"]["sessionCreatorRules"][] = array("field" => "uid", "regex" => "/^[a-z]{2,3}[0-9]+[a-z]$/");
//$config["ldap"]["sessionCreatorRules"][] = array('field'=>'mail', 'regex'=>'/[a-zA-Z]+\.[a-zA-Z]+.*?@example\.ac\.uk/');

$config["baseDir"] = dirname(dirname(__FILE__));