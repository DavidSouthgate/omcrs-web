<?php
require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseApiKeyTest
 */
final class DatabaseApiKeyTest extends TestCase
{

    public function testInvalidApiKey() {
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        // An example invalid API key
        $apiKey = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        // Check if example API key is valid
        $valid = DatabaseApiKey::checkApiKey($apiKey, $mysqli);

        // This example API key should not be valid
        $this->assertEquals(
            $valid,
            false
        );
    }

    public function testNewApiKeyValid() {
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        // Check the API key
        $apiKey = DatabaseApiKey::newApiKey($user, $mysqli);

        // Ensure API key is correct length
        $this->assertEquals(
            strlen($apiKey) == 64,
            true
        );

        // Ensure API key only contains lower case a-z or 0-9
        $this->assertEquals(
            preg_match('/(^[a-z0-9]*$)/', $apiKey),
            1
        );

        // Check if new API key is valid
        $user = DatabaseApiKey::checkApiKey($apiKey, $mysqli);

        // This example API key should return a User class
        $this->assertEquals(
            get_class($user),
            "User"
        );

        // The username should be 'teacher'
        $this->assertEquals(
            $user->getUsername(),
            "teacher"
        );

        // Expire the api key
        $result = DatabaseApiKey::apiKeyExpire($apiKey, $mysqli);

        // Expiring the API key should return true
        $this->assertEquals(
            $result,
            true
        );

        // Check if new API key is valid
        $user = DatabaseApiKey::checkApiKey($apiKey, $mysqli);

        // The API key should no longer be valid
        $this->assertEquals(
            $user,
            false
        );
    }
}
