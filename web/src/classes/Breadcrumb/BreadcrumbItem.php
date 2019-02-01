<?php

class BreadcrumbItem
{

    /** @var string */
    private $title;

    /** @var string */
    private $link;

    /**
     * BreadcrumbItem constructor.
     * @param string $title
     * @param string $link
     */
    public function __construct($title = null, $link = null) {
        $this->title = $title;
        $this->link = $link;
    }

    /**
     * Whether the breadcrumb item has a link
     * @return bool
     */
    public function hasLink() {
        return $this->link!=null;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }
}