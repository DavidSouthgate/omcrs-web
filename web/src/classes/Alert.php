<?php

class Alert
{
    private $title = null;
    private $message = null;
    private $type = null;
    private $dismissable = false;

    public function __construct($array = []) {
        $this->title        = isset($array["title"])        ? $array["title"]       : $this->title;
        $this->message      = isset($array["message"])      ? $array["message"]     : $this->message;
        $this->type         = isset($array["type"])         ? $array["type"]        : $this->type;
        $this->dismissable  = boolval(isset($array["dismissable"])  ? $array["dismissable"] : $this->dismissable);
    }

    public function toArray() {
        $output["title"]        = $this->title;
        $output["message"]      = $this->message;
        $output["type"]         = $this->type;
        $output["dismissable"]  = $this->dismissable;
        return $output;
    }

    /**
     * Puts an alert
     * @param Alert $alert
     * @param int $expire
     */
    public static function displayAlertSession($alert, $expire = null) {
        if($expire == null || $expire < 0) {
            $expire = time() + 30;
        }

        $_SESSION["omcrs_alert"]["alert"] = $alert->toArray();
        $_SESSION["omcrs_alert"]["expire"] = $expire;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return bool|mixed
     */
    public function getDismissable() {
        return $this->dismissable;
    }

    /**
     * @param bool|mixed $dismissable
     */
    public function setDismissable($dismissable) {
        $this->dismissable = boolval($dismissable);
    }
}