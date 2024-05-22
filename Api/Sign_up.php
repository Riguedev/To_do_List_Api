<?php

require "/xampp/htdocs/To_Do_List/Class/Users.php";
require "/xampp/htdocs/To_Do_List/Class/Data_base.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data['name']) && isset($data['email']) && isset($data['password']) && isset($data['vpass'])){
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $vpass = $data['vpass'];

        $user = new users($name, $email, $password, $vpass);

        if($user->validateUser() === true) {
            $encriptPass = hash("sha256", $password);

            $dbConnection = new DataBaseOperation();
            $dbConnection->saveNewUser($name, $email, $encriptPass);

            $response = [
                "message" => "El usuario se registro con exito",
                "state" => true
            ];

            http_response_code(200);
            echo json_encode($response);
            
        } else {
            $validationErrors = $user->validateUser();

            http_response_code(400);
            $errors = [
                "errores" => $validationErrors
            ];

            header(http_response_code(400));

            echo json_encode($errors);
        }
    } else {
        http_response_code(400);
        echo "Debes de llenar todos los campos";
    }
}