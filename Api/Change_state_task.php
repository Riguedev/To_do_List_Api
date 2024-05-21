<?php

require "/xampp/htdocs/To_Do_List/Class/Data_base.php";

session_start();

if(!isset($_SESSION["userData"]["userId"])) {
    http_response_code(403);
    header("Location: https://www.youtube.com");
    die();
}

if($_SERVER["REQUEST_METHOD"] != "PATCH") {
    http_response_code(400);
    die("El metodo no es valido");
}

$task = json_decode(file_get_contents("php://input"), true);

$dbConnection = new DataBaseOperation();
$task["state"] = $dbConnection->changeTaskState($task["task_id"], $task["state"], $_SESSION["userData"]["userId"]);
$dbConnection = NULL;

http_response_code(200);
echo json_encode($task);
