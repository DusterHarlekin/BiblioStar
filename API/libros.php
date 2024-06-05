<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros WHERE /*cod_serie*/autor=".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_libros)>0){
            
           $cota = mysqli_fetch_all($sql_libros , MYSQLI_ASSOC);
            echo json_encode($libros);
         

        }else{

            echo json_encode(["success"=>0]);

        }

        exit();

    }


        //CONSULTAR TODAS LAS CIUDADES
        $sql_libros  = mysqli_query($conexion_bd, "SELECT * FROM libros");
            
    if(mysqli_num_rows($sql_libros)>0){

            $libros = mysqli_fetch_all($sql_libros , MYSQLI_ASSOC);
            echo json_encode($libros);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();