<?php

class LoginTypeLdap implements LoginType
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

        if(strlen(trim($password))==0)
            return null;
        //$error = false;
        $clrtime = time()+5; // For paranoid prevention of timing to narrow username/password guesses
        $ldap_host = $config["ldap"]["host"];
        $ds = @ldap_connect($ldap_host);
        if(isset($config["ldap"]["bind"])) {
            ldap_bind($ds, $config["ldap"]["bind"]["user"], $config["ldap"]["bind"]["pass"]);
        }

        if(!$ds) {
            //echo 'failed to contact LDAP server';
            return null;
        }

        $sr = @ldap_search($ds, $config["ldap"]["context"], 'cn='.$username);
        if(!$sr)
        {
            //echo 'failed to contact LDAP server';
            return null;
        }
        $entry = ldap_first_entry($ds, $sr);
        if($entry)
        {
            $user_dn = ldap_get_dn($ds, $entry);
            $ok = @ldap_bind( $ds, $user_dn, $password);
            //ldap_free_result( $sr );
            if($ok)
            {
                $sr = ldap_search($ds, $config["ldap"]["context"], 'cn='.$username);
                $count = ldap_count_entries( $ds, $sr );
                if($count>0)
                {
                    $records = ldap_get_entries($ds, $sr );
                    $record = $records[0];
                    return self::userFromLDAP($record, $config);
                }
                else
                    //echo "No Identity vault entry found.<br/>";
                ldap_free_result( $sr );
            }
            else
            {
                while($clrtime < time()) sleep(1); // Paranoid prevention of timing to narrow username/password guesses
                //echo 'Incorrect password';
                return null; //Incorrect password
            }
        }
        else
        {
            while($clrtime < time()) sleep(1); // Paranoid prevention of timing to narrow username/password guesses
            //echo 'Incorrect username';
            return null; //Incorrect username
        }
    }

    private static function userFromLDAP($record, $config) {
        $user = new User();
        $user->setUsername($record['uid'][0]);
        $user->setGivenName($record['givenname'][0]);
        $user->setSurname($record['sn'][0]);

        if(isset($record['mail'][0]))
            $user->setEmail($record['mail'][0]);
        elseif(isset($record['emailaddress'][0]))
            $user->setEmail($record['emailaddress'][0]);

        if(is_array($config["ldap"]["sessionCreatorRules"])) {

            foreach($config["ldap"]["sessionCreatorRules"] as $rule) {

                if(isset($record[$rule['field']])) {

                    is_array($record[$rule['field']]) ? $values = $record[$rule['field']] : $values = array($record[$rule['field']]);
                    foreach($values as $value) {

                        if((isset($rule['match']))&&($rule['match']==$value)) {
                            $user->setIsSessionCreator(true);
                        }

                        if((isset($rule['regex']))&&(preg_match($rule['regex'],$value))) {
                            $user->setIsSessionCreator(true);
                        }

                        if((isset($rule['contains']))&&(strpos($value, $rule['contains'])!==false)) {
                            $user->setIsSessionCreator(true);
                        }
                    }
                }
            }
        }

        return $user;
    }
}
