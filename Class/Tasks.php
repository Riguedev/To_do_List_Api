<?php
class Task {

    public $title;
    public $description;
    public $state;
    public $userId;

    public function __construct($title, $description, $state, $userId) {
        $this->title = $title;
        $this->description = $description;
        $this->state = $state;
        $this->userId = $userId;
    }
}
