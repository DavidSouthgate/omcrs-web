<?php

class Response
{
    /** @var int */
    private $responseID;

    /** @var string */
    private $response;

    /** @var int */
    private $time;

    /** @var string */
    private $username;

    /** @var User */
    private $user;

    /** @var int */
    private $choiceID;

    /**
     * @return int
     */
    public function getResponseID() {
        return $this->responseID;
    }

    /**
     * @param int $responseID
     */
    public function setResponseID($responseID) {
        $this->responseID = intval($responseID);
    }

    /**
     * @return string
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response) {
        $this->response = strval($response);
    }

    /**
     * @return int
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime($time) {
        $this->time = intval($time);
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = strval($username);
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getChoiceID() {
        return $this->choiceID;
    }

    /**
     * @param int $choiceID
     */
    public function setChoiceID($choiceID) {
        $this->choiceID = $choiceID;
    }
}