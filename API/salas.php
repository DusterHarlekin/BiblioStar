<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros

        $sql_salas = mysqli_query($conexion_bd, "SELECT * FROM salas WHERE N=".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_salas)>0){
            
           $cota = mysqli_fetch_all($sql_salas, MYSQLI_ASSOC);
            echo json_encode($salas);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }


        //CONSULTAR TODAS LAS CIUDADES
        $sql_salas = mysqli_query($conexion_bd, "SELECT * FROM salas");
            
        if(mysqli_num_rows($sql_salas)>0){

            $salas = mysqli_fetch_all($sql_salas, MYSQLI_ASSOC);
            echo json_encode($salas);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();