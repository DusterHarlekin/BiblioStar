<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
     if($_SERVER["REQUEST_METHOD"] == 'GET'){
     
     //Selección de id (ROGER)  
     if(isset($_GET["cod_sala"])){

        //VALIDACIÓN DE USUARIO

        //Integrar filtros

        $sql_salas = mysqli_query($conexion_bd, "SELECT * FROM salas WHERE N=".$_GET["cod_sala"]);
        
        if(mysqli_num_rows($sql_salas)>0){
            
           $cota = mysqli_fetch_all($sql_salas, MYSQLI_ASSOC);
            echo json_encode($salas);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }
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
           //POST REGISTRAR

   if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
    $data = json_decode(file_get_contents("php://input"));

    if(trim($data->N) == ""){

        echo json_encode(["error"=>"los campos no pueden estar vacíos"]);
    }
    else{

        mysqli_query($conexion_bd, "INSERT INTO `salas`(`N`, `cod_sala`, `DESCRIPCION`) VALUES ('$data->N','$data->cod_sala','$data->DESCRIPCION')");
        
        echo json_encode(["success"=>"datos registrados"]);
    }
    exit();

}
