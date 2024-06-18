<?php

    include "conexion.php";
    include "utils/filtering.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
     if($_SERVER["REQUEST_METHOD"] == 'GET'){
     
     //Selección de id (ROGER)  
     if(isset($_GET["codigo_ciudad"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        
        $sql_ciudad = mysqli_query($conexion_bd, "SELECT * FROM ciudades WHERE codigo_ciudad=".$_GET["ciudad"]);
        
        if(mysqli_num_rows($sql_ciudad)>0){
           $ciudad = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);
            echo json_encode($ciudad);
         

        }else{
            echo json_encode(["success"=>0]);
        }

        exit();

    }
}//


    //CONSULTAR TODAS LAS CIUDADES

    
    $query = filtrarBusqueda($_GET, 'ciudades');

    $sql_ciudad = mysqli_query($conexion_bd, $query);
        
    if(mysqli_num_rows($sql_ciudad)>0){
        $ciudad = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);
        echo json_encode($ciudad);

    }else{
        echo json_encode(["success"=>0]);
    }

    exit();

        //POST REGISTRAR

        if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
            $data = json_decode(file_get_contents("php://input"));
        
            if(trim($data->codigo_ciudad) == ""){
        
                echo json_encode(["error"=>"los campos no pueden estar vacíos"]);
                
            }
            else{
                
                mysqli_query($conexion_bd, "INSERT INTO `ciudades`(`codigo_pais`, `codigo_ciudad`, `ciudad`) VALUES ('$data->codigo_pais','$data->codigo_ciudad','$data->ciudad')"); 
                
                echo json_encode(["success"=>"datos registrados"]);
            }
            exit();
        
        }
        