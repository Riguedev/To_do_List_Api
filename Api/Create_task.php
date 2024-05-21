<?php
require "/xampp/htdocs/To_Do_List/Class/Tasks.php";
require "/xampp/htdocs/To_Do_List/Class/Data_base.php";

session_start();

if(!isset($_SESSION["userData"]["userId"])) {
    http_response_code(403);
    header("Location: https://www.youtube.com");
    die();
}

if($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(400);
    die("El metodo no es valido");
}

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data["title"]) || !isset($data["description"]) || !isset($data["state"]) || $data["state"] != 0) {
    http_response_code(400);
    die("Ingresa datos validos");
}

$task = new Task($data["title"], $data["description"], $data["state"], $_SESSION["userData"]["userId"]);

$dbConnection = new DataBaseOperation();
$response = $dbConnection->createTask($task->title, $task->description, $task->state, $task->userId);

$_SESSION["userData"]["tasks"][] = $response;

http_response_code(200);
echo json_encode($response);
