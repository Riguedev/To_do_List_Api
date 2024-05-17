<?php

class DataBaseOperation{
    public function saveNewUser($name, $email, $encriptPass) {
        $server = "localhost";
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

            echo "El registro se completo";

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
            $dataBaseQuery = NULL;

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
        
            $sql_request = "INSERT INTO tasks (title, description, state, user_id) VALUES (:title, :description, :state, :user_id)";
        
            $dataBaseQuery = $dbConnection->prepare($sql_request);
            $dataBaseQuery->bindParam(":title", $title);
            $dataBaseQuery->bindParam(":description", $description);
            $dataBaseQuery->bindParam("state", $state);
            $dataBaseQuery->bindParam(":user_id", $userId);
            $dataBaseQuery->execute(); 
        }
        catch(PDOException $e){
            http_response_code(500);
            die("Ocurrio un error");
        }
    }
}