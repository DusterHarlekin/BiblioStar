<?php
//Api rest
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");

header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

    function connect(){
        
        $servername = "localhost";
        $database = "bd_piotamayo";
        $username = "root";
        $password = "";
        // Crear conexión
        $conn = mysqli_connect($servername, $username, $password, $database);
        // Chequeo de conexión
        if (!$conn) {
            
            die("Connection failed: " . mysqli_connect_error());
        }
        
        
        return $conn;        
    }


