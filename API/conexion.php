<?php
//Api rest

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


