<?php

class LoginTypeFactory
{
    /**
     * Returns a new instance of a login type object from a given type.
     * @param $type string Type of login
     * @return LoginType
     * @throws Exception 'LoginTypeFactory_ClassNotFoundException': Given type does not translate to login object
     */
    public static function create($type)
    {
        switch ($type) {
            case "ldap":
                return new LoginTypeLdap();
                break;
            case "ldapcsq":
                return new LoginTypeLdapCsq();
                break;
            case "any":
                return new LoginTypeAny();
                break;
            case "some":
                return new LoginTypeSome();
                break;
            case "native":
                return new LoginTypeNative();
                break;
            case "omcrs_v1":
                return new LoginTypeOmcrsV1();
                break;
            default:
                throw new Exception("LoginTypeFactory_ClassNotFoundException");
        }
    }
}