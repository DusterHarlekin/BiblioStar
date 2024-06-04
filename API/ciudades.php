<?php
//Api rest
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");

header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
    include "conexion.php";
    
    $conexion_bd=connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        $sql_ciudad = mysqli_query($conexion_bd, "SELECT * FROM ciudades WHERE codigo_ciudad=".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_ciudad)>0){
           $ciudad = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);
            echo json_encode($ciudad);
         

        }else{
            echo json_encode(["success"=>0]);
        }

        exit();

    }


    //CONSULTAR TODAS LAS CIUDADES
    $sql_ciudad = mysqli_query($conexion_bd, "SELECT * FROM ciudades");
        
    if(mysqli_num_rows($sql_ciudad)>0){
        $ciudad = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);
        echo json_encode($ciudad);

    }else{
        echo json_encode(["success"=>0]);
    }

    exit();