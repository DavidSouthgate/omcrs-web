<?php

class Question
{
    /** @var int */
    private $questionID;

    /** @var int */
    private $sessionQuestionID;

    /** @var int */
    private $sessionID;

    /** @var int */
    private $sessionIdentifier;

    /** @var string */
    protected $type;

    /** @var string */
    protected $typeDisplay;

    /** @var string */
    protected $question;

    /** @var int */
    private $created = null;

    /** @var int */
    private $lastUpdate = null;

    /** @var bool */
    private $active = false;

    /**
     * Question constructor.
     * @param $array
     */
    public function __construct($array = []) {
        $this->fromArray($array);
    }

    public function fromArray($array = []) {
        $this->setQuestionID(       isset($array["questionID"])        ? $array["questionID"]        : $this->getQuestionID());
        $this->setSessionQuestionID(isset($array["sessionQuestionID"]) ? $array["sessionQuestionID"] : $this->getSessionQuestionID());
        $this->setType(             isset($array["type"])              ? $array["type"]              : $this->getType());
        $this->setQuestion(         isset($array["question"])          ? $array["question"]          : $this->getQuestion());
        $this->setCreated(          isset($array["created"])           ? $array["created"]           : $this->getCreated());
        $this->setLastUpdate(       isset($array["lastUpdate"])        ? $array["lastUpdate"]        : $this->getLastUpdate());
        $this->setActive(           isset($array["active"])            ? $array["active"]            : $this->isActive());
        $this->setSessionID(        isset($array["sessionID"])         ? $array["sessionID"]         : $this->getSessionID());
        $this->setSessionIdentifier(isset($array["sessionIdentifier"])         ? $array["sessionIdentifier"]         : $this->getSessionIdentifier());
    }

    public function toArray() {
        $output["type"] = $this->type;
        $output["sessionQuestionID"] = $this->sessionQuestionID;
        $output["question"] = $this->question ? $this->question : $this->typeDisplay . " Question";
        $output["created"] = $this->created;
        $output["lastUpdate"] = $this->lastUpdate;
        $output["active"] = $this->active;
        return $output;
    }

    /**
     * @return int
     */
    public function getQuestionID() {
        return $this->questionID;
    }

    /**
     * @param int $questionID
     */
    public function setQuestionID($questionID) {
        $this->questionID = intval($questionID);
    }

    /**
     * @return int
     */
    public function getSessionQuestionID() {
        return $this->sessionQuestionID;
    }

    /**
     * @param int $sessionQuestionID
     */
    public function setSessionQuestionID($sessionQuestionID) {
        $this->sessionQuestionID = intval($sessionQuestionID);
    }

    /**
     * @return int
     */
    public function getSessionID() {
        return $this->sessionID;
    }

    /**
     * @param int $sessionID
     */
    public function setSessionID($sessionID) {
        $this->sessionID = $sessionID;
    }

    /**
     * @return int
     */
    public function getSessionIdentifier() {
        return $this->sessionIdentifier;
    }

    /**
     * @param int $sessionIdentifier
     */
    public function setSessionIdentifier($sessionIdentifier) {
        $this->sessionIdentifier = $sessionIdentifier;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTypeDisplay() {
        return $this->typeDisplay;
    }

    /**
     * @param string $typeDisplay
     */
    public function setTypeDisplay($typeDisplay) {
        $this->typeDisplay = $typeDisplay;
    }

    /**
     * @return mixed
     */
    public function getQuestion() {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question) {
        $this->question = $question;
    }

    /**
     * @return int
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param int $created
     */
    public function setCreated($created) {
        $this->created = intval($created);
    }

    /**
     * @return int
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * @param int $lastUpdate
     */
    public function setLastUpdate($lastUpdate) {
        $this->lastUpdate = intval($lastUpdate);
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active) {
        $this->active = text2bool($active);
    }
}