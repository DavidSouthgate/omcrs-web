<?php

/**
 * Login using old version of omcrs on uni server.
 * WARNING: THIS SHOULD ONLY BE USED IN DEVELOPMENT TO SIMULATE UofG LDAP
 * Class LoginTypeOmcrsV1
 */
class LoginTypeOmcrsV1 implements LoginType
{

    /**
     * Checks login username and password
     * @param string $username
     * @param string $password
     * @param array $config
     * @param mysqli $mysqli
     * @return User|null
     */
    public static function checkLogin($username, $password, $config = [], $mysqli = null) {

        // Url of moodle login
        $url = "https://classresponse.gla.ac.uk/index.php";

        // Initialise cURL
        $ch = curl_init($url);

        // Get post data as string
        $postString = "uname=$username&pwd=$password";

        // Add POST data to CURL request. We are posting 2 items.
        curl_setopt($ch,CURLOPT_POST, 2);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postString);

        // Return the transfer as a string of the return value of curl_exec()
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Include headers in data response
        curl_setopt($ch, CURLOPT_HEADER, 1);

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // If curl error, return null
        if($response === false)
            return null;

        // Get info of cURL request
        $info = curl_getinfo($ch);

        // Separate header from body
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        // Close the cURL request
        curl_close($ch);

        // Terrible HTML regex. As this will NEVER be put into production this will do...........
        $re = "/(<div id=\"logoutLink\"><div class=\"loginBox\">)([^<]+)( <a href='\/index\.php\?logout=1'><i class='fa fa-lock'><\/i> Log out<\/a><\/div><\/div>)/";

        preg_match($re, $body, $matches);

        // If the match was not found, invalid login!
        if(!$matches)
            return null;

        $fullName = "David Keith Southgate";

        // Get full name of the user from the page
        $fullName = $matches[2];

        // Create a new user with the given username
        $user = new User();
        $user->setUsername($username);

        // Put the full name in the given name to be simple...
        $user->setGivenName($fullName);
        $user->setSurname("");

        // If the user is a session creator
        if(strpos($body, "Create a new clicker session") > 0) {
            $user->setIsSessionCreator(true);
        }

        return $user;
    }
}
