<?php

    include "conexion.php";
    
    $conexion_bd = connect();

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