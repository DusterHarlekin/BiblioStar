<?php

    include "conexion.php";
    include "utils/filtering.php";
    include "utils/pagination.php";

    $conexion_bd = connect();

     //Prueba consulta de ciudad
     if($_SERVER["REQUEST_METHOD"] == 'GET'){

      //Selección de id (ROGER)   
     if(isset($_GET["N"])){

        //VALIDACIÓN DE USUARIO
        
        $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=".$_GET["N"]);
        
        if(mysqli_num_rows($sql_cota)>0){
            
           $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
            echo json_encode($cota);
         

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();

    }
}

     //PUT EDITAR

 if($_SERVER["REQUEST_METHOD"] == 'PUT'){
        
    $data = json_decode(file_get_contents("php://input"));
  
    if(trim($data->N) == ""){
        echo json_encode(["error"=>"El campo clave no puede esta vacío"]);
    }
    else{
        mysqli_query($conexion_bd, "UPDATE cota SET cod_isbn ='".$data->cod_isbn."', cota ='".$data->cota."', cutter ='".$data->cutter."', volumen ='".$data->volumen."', ejemplar ='".$data->ejemplar."', fecha_ing ='".$data->fecha_ing."', cota_completa ='".$data->cota_completa."' WHERE N ='".$data->N."'");
        echo json_encode([
            "success"=>"datos actualizados",
            "N"=>$data->N,
            "cod_isbn"=>$data->cod_isbn,
            "cota"=>$data->cota,
            "cutter"=>$data->cutter,
            "volumen"=>$data->volumen,
            "ejemplar"=>$data->ejemplar,
            "fecha_ing"=>$data->fecha_ing,
            "cota_completa"=>$data->cota_completa
        ]);
    }
    exit();

}


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
    
        //CONSULTAR TODAS LAS COTAS
        $query = filtrarBusqueda($_GET, 'cota');

        $sql_cota = mysqli_query($conexion_bd, $query);
    
        $resData = paginar($sql_cota, $_GET, 'cota');
    
        $sql_cota = mysqli_query($conexion_bd, $resData["query"]);
    
    
        //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
        $rows = mysqli_num_rows($sql_cota);
    
        $resData["pagination"]["end"] = $resData["pagination"]["start"]-1 + $rows;
                
        if($rows > 0){
    
            $cota["data"] = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
            
    
            //AGREGAR INFORMACION UTIL PARA EL FRONTEND
    
            $cota["pagination"] = $resData["pagination"];
    
            echo json_encode($cota);
    
         
    
        }else{
    
            echo json_encode(["success"=>0]);
        }
    
        exit();