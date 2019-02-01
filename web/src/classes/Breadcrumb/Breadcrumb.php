<?php

class Breadcrumb
{

    /** @var BreadcrumbItem[] */
    private $items;

    /**
     * Breadcrumb constructor.
     * @param array $items
     */
    public function __construct(array $items = []) {
        $this->items = $items;
    }

    /**
     * Adds new item to array of items
     * @param string $title
     * @param string $link
     */
    public function addItem($title = null, $link = null) {
        array_push($this->items, new BreadcrumbItem($title, $link));
    }

    /**
     * Counts the number of breadcrumb items
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * @return BreadcrumbItem[]
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @param BreadcrumbItem[] $items
     */
    public function setItems($items) {
        $this->items = $items;
    }
}