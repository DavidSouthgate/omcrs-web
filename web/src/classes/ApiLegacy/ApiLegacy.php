<?php

class ApiLegacy
{

    public static function api() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        switch ($_REQUEST["action"]) {
            case "login":
                $username = $_REQUEST["uname"];
                $password = $_REQUEST["pwd"];
                ApiLegacyLogin::login($username, $password, $config, $mysqli);
                die();
                break;

            case "display":
                ApiLegacyDisplay::display($config, $mysqli);
                die();
                break;
        }


        // Get details of logged in user
        $user = Login::checkUserLoggedIn();

        // If user is not logged in, display error
        if(!$user) {
            $error[0] = "You must be logged in first.";
            self::sendResponse(null, $error, [], $config);
            die();
        }

        // If user is not session creator, display error
        if(!$user->isSessionCreator() && !$user->isAdmin()) {
            $error[0] = "User ".$user->getUsername()." does not have permission to create and edit sessions.";
            self::sendResponse(null, $error, [], $config);
            die();
        }

        error_log($_REQUEST["action"]);
        error_log(json_encode($_REQUEST));

        // Switch on action
        switch ($_REQUEST["action"]) {

            // The Session List
            case "sessionlist":
                ApiLegacySession::sessionList($user, $config, $mysqli);
                break;

            // The Session Details
            case "sessiondetail":
                ApiLegacySession::sessionDetail($user, $config, $mysqli);
                break;

            case "quinfo":
                ApiLegacySessionQuestion::quinfo($user, $config, $mysqli);
                break;

            case "quinfoshort":
                ApiLegacySessionQuestion::quinfo($user, $config, $mysqli);
                break;

            case "getqids":
                ApiLegacySessionQuestion::getqids($user, $config, $mysqli);
                break;

            case "display":
                ApiLegacyDisplay::display($user, $config, $mysqli);
                break;

            default:
                $error[0] = "Unrecognised action '".$_REQUEST["action"]."'.";
                self::sendResponse(null, $error, [], $config);
        }
    }

    private static function ensureSessionCreator($user) {

        die();
    }

    public static function sendResponse($messageName, $errors, $data, $config) {
        header ("Content-Type:text/xml");
        echo "<?xml version=\"1.0\"?>\n";
        echo "<OMCRSResponse version=\"".$config["version"]."\"";
        if($messageName) {
            echo " messageName='$messageName'";
        }
        echo ">\n";
        if(sizeof($errors) == 0)
            echo "<errors/>\n";
        else {
            echo "<errors>\n";
            foreach($errors as $error)
                echo "<error>$error</error>\n";
            echo "</errors>\n";
        }
        if($data === false)
            echo "<data/>\n";
        else {
            echo self::array2XML('data', $data);
        }
        echo "</OMCRSResponse>";
    }

    private static function array2XML($name, $data) {
        $out = '';
        if(is_array($data)) {
            if(self::is_assoc($data)) {
                $out .= "<$name";
                if(isset($data['attributes'])) {
                    foreach($data['attributes'] as $k=>$v) {
                        if(is_bool($v))
                            $v2 = $v?'1':'0';
                        else
                            $v2 = htmlentities($v);
                        $out .= " $k=\"{$v2}\"";
                    }
                }
                if((isset($data['attributes']))&&(isset($data[0]))&&(sizeof($data)==2)) {
                    $out .= ">";
                    $out .= htmlentities($data[0]);
                }
                else {
                    $out .= ">\n";
                    foreach($data as $k=>$v) {
                        if($k !== 'attributes') {
                            $out .= self::array2XML($k, $v);
                        }
                    }
                }
                $out .= "</$name>\n";
            }
            else {
                foreach($data as $k=>$v) {
                    $out .= self::array2XML($name, $v);
                }
            }
        }
        else  {
            $out = "<$name>";
            if(is_bool($data))
                $out .= $data?'1':'0';
            else
                $out .= htmlentities($data);
            $out .= "</$name>\n";
        }
        return $out;
    }

    private static function is_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}