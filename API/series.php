<?php

    include "conexion.php";
    include "utils/filtering.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if($_SERVER["REQUEST_METHOD"] == 'GET'){

    //Selección de id (ROGER)   
    if(isset($_GET["N"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        $sql_serie = mysqli_query($conexion_bd, "SELECT * FROM serie WHERE /*cod_serie*/N=".$_GET["cod_serie"]);
        
        if(mysqli_num_rows($sql_serie)>0){
            
           $cota = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);
            echo json_encode($serie);
         

        }else{

            echo json_encode(["success"=>0]);

        }

        exit();

    }
     }

        //CONSULTAR TODAS LAS CIUDADES
        $query = filtrarBusqueda($_GET, 'serie');

        $sql_serie = mysqli_query($conexion_bd, $query);
            
    if(mysqli_num_rows($sql_serie)>0){

            $serie = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);
            echo json_encode($serie);

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
        
        mysqli_query($conexion_bd, "INSERT INTO `serie`(`N`, `cod_serie`, `DESCRIPCION`) VALUES ('$data->N','$data->cod_serie','$data->DESCRIPCION')"); 
        
        echo json_encode(["success"=>"datos registrados"]);
    }
    exit();

}
