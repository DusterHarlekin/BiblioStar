<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        
        $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_cota)>0){
            
           $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
            echo json_encode($cota);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }


        //CONSULTAR TODAS LAS CIUDADES
        $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota");
            
        if(mysqli_num_rows($sql_cota)>0){

            $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
            echo json_encode($cota);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();