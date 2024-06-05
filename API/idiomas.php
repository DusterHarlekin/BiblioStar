<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if(isset($_GET["consultar"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        
        $sql_idiomas = mysqli_query($conexion_bd, "SELECT * FROM idiomas WHERE cod_idioma =".$_GET["consultar"]);
        
        if(mysqli_num_rows($sql_idiomas)>0){
            
           $idiomas = mysqli_fetch_all($sql_idiomas, MYSQLI_ASSOC);
            echo json_encode($idiomas);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }


        //CONSULTAR TODAS LAS CIUDADES
        $sql_idiomas = mysqli_query($conexion_bd, "SELECT * FROM idiomas");
            
        if(mysqli_num_rows($sql_idiomas)>0){

            $idiomas = mysqli_fetch_all($sql_idiomas, MYSQLI_ASSOC);
            echo json_encode($idiomas);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();