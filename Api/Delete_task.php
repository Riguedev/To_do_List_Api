<?php

require "/xampp/htdocs/To_Do_List/Class/Data_base.php";

session_start();

if(!isset($_SESSION["userData"]["userId"])) {
    header("Location: https://www.example.com");
    http_response_code(403);
    die();
}

if($_SERVER["REQUEST_METHOD"] != "DELETE") {
    http_response_code(400);
    die("El metodo no es valido");
}

$task = json_decode(file_get_contents("php://input"), true);

$dbConnection = new DataBaseOperation();
$dbConnection->deleteTask($task["task_id"], $_SESSION["userData"]["userId"]);
$dbConnection = NULL;

$response = [
    "message" => "La tarea fue eliminda",
    "state" => true
];

http_response_code(200);
echo json_encode($response);
