<?php

class User
{

    /** @var int */
    private $id = null;

    /** @var string */
    private $username = null;

    /** @var string */
    private $givenName = null;

    /** @var string */
    private $surname = null;

    /** @var string */
    private $email = null;

    /** @var bool */
    private $isSessionCreator = false;

    /** @var bool */
    private $isAdmin = false;

    /** @var bool */
    private $isGuest = false;

    /**
     * User constructor.
     * @param array|null $array
     */
    public function __construct($array = null) {

        // If data has been given as an array
        if($array !== null) {
            $this->fromArray($array);
        }
    }

    /**
     * Used to load user details from an array
     * @param $array
     */
    public function fromArray($array) {
        $this->id               = isset($array["id"])               ? $array["id"]                  : $this->id;
        $this->username         = isset($array["username"])         ? $array["username"]            : $this->username;
        $this->givenName        = isset($array["givenName"])        ? $array["givenName"]           : $this->givenName;
        $this->surname          = isset($array["surname"])          ? $array["surname"]             : $this->surname;
        $this->email            = isset($array["email"])            ? $array["email"]               : $this->email;
        $this->isSessionCreator = isset($array["isSessionCreator"]) ? text2bool($array["isSessionCreator"])    : $this->isSessionCreator;
        $this->isAdmin          = isset($array["isAdmin"])          ? text2bool($array["isAdmin"])             : $this->isAdmin;
        $this->isGuest          = isset($array["isGuest"])          ? text2bool($array["isGuest"])             : $this->isGuest;

        // TODO
        $this->isSessionCreator = isset($array["isSessionCreatorOverride"]) ? text2bool($array["isSessionCreatorOverride"])    : $this->isSessionCreator;
        $this->isAdmin          = isset($array["isAdminOverride"])          ? text2bool($array["isAdminOverride"])             : $this->isAdmin;
        $this->id               = isset($array["userID"])               ? intval($array["userID"])                  : $this->id;
    }

    /**
     * Outputs user details as an array
     * @return mixed
     */
    public function toArray() {
        $array["id"]                = $this->id;
        $array["username"]          = $this->username;
        $array["givenName"]         = $this->givenName;
        $array["surname"]           = $this->surname;
        $array["email"]             = $this->email;
        $array["isSessionCreator"]  = $this->isSessionCreator;
        $array["isAdmin"]           = $this->isAdmin;
        $array["isGuest"]           = $this->isGuest;
        return $array;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->isGuest() ? "guest-" . $this->getId() : $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getGivenName() {
        return $this->givenName;
    }

    /**
     * @param string $givenName
     */
    public function setGivenName($givenName) {
        $this->givenName = $givenName;
    }

    /**
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getFullName() {
        if($this->isGuest()) {
            return "Anonymous Guest";
        }

        $output = "";

        // If a given name is available add it to the output
        if($this->givenName !== null)
            $output .= $this->givenName;

        // If both a given name and a surname is available, add a space
        if($this->givenName !== null && $this->surname !== null)
            $output .= " ";

        // If a surname is available add it to the output
        if($this->surname !== null)
            $output .= $this->surname;

        if(!$output)
            $output = $this->username;

        return $output;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isSessionCreator() {
        return $this->isSessionCreator;
    }

    /**
     * @param bool $isSessionCreator
     */
    public function setIsSessionCreator($isSessionCreator) {
        $this->isSessionCreator = $isSessionCreator;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return bool
     */
    public function isGuest() {
        return $this->isGuest;
    }

    /**
     * @param bool $isGuest
     */
    public function setIsGuest($isGuest) {
        $this->isGuest = $isGuest;
    }
}