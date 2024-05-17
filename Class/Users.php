<?php

class users {
    public $name;
    public $email;
    public $password;
    public $vpass;

    public function __construct($name, $email, $password, $vpass) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->vpass = $vpass;
    }

    public function validateUser() {
        $errors = [];
        if ($this->name == "") {
            $errors[] = "Ingrese un nombre válido";
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) != true) {
            $errors[] = "Ingrese un correo electrónico válido";
        }

        if ($this->password == "") {
            $errors[] = "Igrese una contraseña válida";
        }

        if (strlen($this->password)< 8 || strlen($this->password)> 32) {
            $errors[] = "La contraseña debe ser mayor a 8 con un máximo de 32 carácteres";
        }

        if ($this->password != $this->vpass) {
            $errors[] = "Las contraseñas no coinciden";
        }

        if(count($errors) == 0) {
            return true;
        } else {
            return json_encode($errors);
        }
    }

}