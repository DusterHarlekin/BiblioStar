<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        $sql_serie = mysqli_query($conexion_bd, "SELECT * FROM serie WHERE /*cod_serie*/N=".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_serie)>0){
            
           $cota = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);
            echo json_encode($serie);
         

        }else{

            echo json_encode(["success"=>0]);

        }

        exit();

    }


        //CONSULTAR TODAS LAS CIUDADES
        $sql_serie = mysqli_query($conexion_bd, "SELECT * FROM serie");
            
    if(mysqli_num_rows($sql_serie)>0){

            $serie = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);
            echo json_encode($serie);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();