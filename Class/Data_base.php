<?php

class DataBaseOperation{
    public function saveNewUser($name, $email, $encriptPass) {
        $server = "to_do_list";
        $dbUser = "root";
        $server_pass = "";

        try{
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_request = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";

            $dataBaseQuery = $dbConnection->prepare($sql_request);
            $dataBaseQuery->bindParam(":name", $name);
            $dataBaseQuery->bindParam(":email", $email);
            $dataBaseQuery->bindParam(":password", $encriptPass);
            $dataBaseQuery->execute();
            $dbConnection = NULL;

        } 
        catch(PDOException $e) {
            http_response_code(500);
            die("Ocurrio un error");
        }
    }

    public function getUserData($email, $password) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";

        try{
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sqlRequest = "SELECT * FROM users WHERE email = :email AND password = :password";

            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":email", $email);
            $dataBaseQuery->bindParam(":password", $password);
            $dataBaseQuery->execute();
            $userResults = $dataBaseQuery->fetchAll(PDO::FETCH_ASSOC);
            $dbConnection = NULL;

        } catch (PDOException $e) {
            http_response_code(500);
            error_log($e->getMessage());
            die("Ha Ocurrido un problema");
        }

        return $userResults;
    }

    public function getUserTasks($userId) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";

        try {
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sqlRequest = "SELECT * FROM tasks WHERE user_id = :user_id";

            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":user_id", $userId);
            $dataBaseQuery->execute();
            $taskResults = $dataBaseQuery->fetchAll(PDO::FETCH_ASSOC);

            $dbConnection = NULL;

        } catch(PDOException $e) {
            http_response_code(500);
            error_log($e->getMessage());
            die("Ocurrio un error");
        }
        return $taskResults;
    }

    public function createTask($title, $description, $state, $userId) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";

        try{
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sqlRequest = "INSERT INTO tasks (title, description, state, user_id) VALUES (:title, :description, :state, :userId)";
        
            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":title", $title);
            $dataBaseQuery->bindParam(":description", $description);
            $dataBaseQuery->bindParam("state", $state);
            $dataBaseQuery->bindParam(":userId", $userId);
            $dataBaseQuery->execute(); 

            $lastInserId = $dbConnection->lastInsertId();

            $response = array(
                "task_id" => (int)$lastInserId,
                "title" => $title,
                "description" => $description,
                "state" => (int)$state,
                "user_id" => (int)$userId
            );

            $dbConnection = NULL;
        }
        catch(PDOException $e){
            http_response_code(500);
            die("Ocurrio un error");
        }
        return $response;
    }

    public function updateTask($taskId, $title, $description, $userId) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";

        try {
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sqlRequest = "UPDATE tasks SET title = :title, description = :description WHERE task_id = :taskId AND user_id = :userId";

            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":taskId", $taskId);
            $dataBaseQuery->bindParam(":title", $title);
            $dataBaseQuery->bindParam(":description", $description);
            $dataBaseQuery->bindParam(":userId", $userId);
            $dataBaseQuery->execute();
            $dbConnection = NULL;
        }
        catch(PDOException) {
            http_response_code(500);
            die("Ocurrio un error");
        }

        foreach ($_SESSION["userData"]["tasks"] as &$task) {
            if($task["task_id"] == $taskId) {
                $task["title"] = $title;
                $task["description"] = $description;
            }
        }
        return [
            "title" => $title,
            "description" => $description
        ];
    }

    public function changeTaskState($taskId, $taskState, $userId) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";
        $newTaskState = 0;

        ($taskState == 0) ? $newTaskState = 1 : $newTaskState = 0;

        try {
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sqlRequest = "UPDATE tasks SET state = :state WHERE task_id = :taskId AND user_id = :userId";

            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":taskId", $taskId);
            $dataBaseQuery->bindParam(":state", $newTaskState);
            $dataBaseQuery->bindParam(":userId", $userId);
            $dataBaseQuery->execute();
        }
        catch(PDOException) {
            http_response_code(500);
            die("Ocurrio un error");
        }

        foreach ($_SESSION["userData"]["tasks"] as &$task) {
            $task["state"] = (int)$newTaskState;
        }

        return (int)$newTaskState;
    }

    public function deleteTask($taskId, $userId) {
        $server = "localhost";
        $dbUser = "root";
        $server_pass = "";

        try {
            $dbConnection = new PDO("mysql:mysql:host=$server;dbname=to_do_list", $dbUser, $server_pass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sqlRequest = "DELETE FROM tasks WHERE task_id = :taskId AND user_id = :userId";

            $dataBaseQuery = $dbConnection->prepare($sqlRequest);
            $dataBaseQuery->bindParam(":taskId", $taskId);
            $dataBaseQuery->bindParam(":userId", $userId);
            $dataBaseQuery->execute();
            $dbConnection = NULL;
        }
        catch(PDOException) {
            http_response_code(500);
            die("Ocurrio un error");
        }
    }
}