<?php

class ApiUser
{

    public static function listUsers() {
        /**
         * @var $mysqli mysqli
         * @var $user User
         */
        extract(self::setup());


        $output = [];
        foreach(DatabaseUser::loadAllUsers($mysqli) as $u) {
            $output[] = $u->toArray();
        }

        Api::output($output);
    }

    public static function details($userID) {
        /**
         * @var $mysqli mysqli
         * @var $user User
         * @var $thisUser User
         */
        extract(self::setupUser($userID));

        if(!$thisUser)
            ApiError::unknown();

        Api::output($thisUser->toArray());
    }

    public static function edit($userID) {
        /**
         * @var $mysqli mysqli
         * @var $user User
         * @var $thisUser User
         */
        extract(self::setupUser($userID));


        if($thisUser === null)
            ApiError::notFoundCustom("user not found");

        // Change user details
        $thisUser->fromArray($_REQUEST);

        // Update this user in the database
        $result = DatabaseUser::update($thisUser, $mysqli);
//        if($result === null)
//            ApiError::unknown();

        // Load this user again from the database to verify changes
        $thisUser = DatabaseUser::loadDetailsFromUserID($userID, $mysqli);

        Api::output($thisUser->toArray());
    }

    public static function delete($userID) {
        /**
         * @var $mysqli mysqli
         * @var $user User
         * @var $thisUser User
         */
        extract(self::setupUser($userID));

        if(!$thisUser)
            ApiError::unknown();

        $result = DatabaseUser::delete($userID, $mysqli);

        if(!$result)
            ApiError::unknown();

        $output = [];
        $output["success"] = true;

        Api::output($output);
    }


    /**
     * @return array
     */
    private static function setup() {

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Get user from API
        $user = Api::checkApiKey($_REQUEST["key"], $mysqli);

        // If invalid API key
        if($user === null) {
            ApiError::invalidApiKey();
            die();
        }

        // If user is not an admin
        if(!$user->isAdmin()) {
            ApiError::permissionDenied();
            die();
        }

        return [
            "mysqli" => $mysqli,
            "user" => $user,
        ];
    }

    private static function setupUser($userID) {
        /**
         * @var $mysqli mysqli
         * @var $user User
         */
        extract(self::setup());

        $thisUser = DatabaseUser::loadDetailsFromUserID($userID, $mysqli);

        return [
            "mysqli" => $mysqli,
            "user" => $user,
            "thisUser" => $thisUser,
        ];
    }
}