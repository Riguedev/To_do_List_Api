<?php

require "/xampp/htdocs/To_Do_List/Class/Data_base.php";

session_start();

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data["email"]) && isset($data['password'])){
        if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) == true) {
            $email = $data["email"];
            $password = hash("sha256", $data["password"]);

            $dbConnection = new DataBaseOperation();
            $userResults = $dbConnection->getUserData($email, $password);
            $taskResults = $dbConnection->getUserTasks($userResults[0]["user_id"]);

            $_SESSION['userData'] = array();
            $_SESSION["userData"]["userId"] = $userResults[0]["user_id"];
            $_SESSION["userData"]["name"] = $userResults[0]["name"];
            $_SESSION["userData"]["email"] = $userResults[0]["email"];
            $_SESSION["userData"]["password"] = $userResults[0]["password"];
            $_SESSION["userData"]["tasks"] = $taskResults;

            $response = [
                "name" => $userResults[0]["name"],
                "email" => $userResults[0]["email"],
                "tasks" => $taskResults
            ];

            http_response_code(200);
            echo json_encode($response);
        
        } else {
            http_response_code(400);
            echo "Ingrese datos validos";
        }

    } else {
        http_response_code(400);
        echo "Los campos no pueden estar vácios";
    }
}