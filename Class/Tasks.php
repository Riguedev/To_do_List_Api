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

    public function validateTask() {
        $errors = [];
        if ($this->title == "") {
            $errors[] = "El titulo esta vacío";
        }

        if ($this->description == "") {
            $errors[] = "La descripción esta vacía";
        }

        if ($this->state > 1) {
            $errors[] = "Ocurrio un problema con el estado";
        }

        if (!is_int($this->userId)) {
            $errors[] = "El apartado propietario esta vacío";
        }

        if (count($errors) == 0) {
            return true;
        } else {
            return $errors;
        }
    }
}
