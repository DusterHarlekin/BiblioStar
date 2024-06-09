<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
     if($_SERVER["REQUEST_METHOD"] == 'GET'){

      //Selección de id (ROGER)   
     if(isset($_GET["cota_completa"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        
        $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=".$_GET["cota_completa"]);
        
        if(mysqli_num_rows($sql_cota)>0){
            
           $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
            echo json_encode($cota);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }
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

    //POST REGISTRAR

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
        $data = json_decode(file_get_contents("php://input"));
    
        if(trim($data->N) == ""){
    
            echo json_encode(["error"=>"los campos no pueden estar vacíos"]);
        }
        else{
            
            mysqli_query($conexion_bd, "INSERT INTO `cota`(`N`, `cod_isbn`, `cota`, `cutter`, `volumen`, `ejemplar`, `fecha_ing`, `cota_completa`) VALUES ('$data->N','$data->cod_isbn','$data->cota','$data->cutter','$data->volumen','$data->ejemplar','$data->fecha_ing','$data->cota_completa')"); 
            
            echo json_encode(["success"=>"datos registrados"]);
        }
        exit();
    
    }
    