<?php

    include "conexion.php";
    
    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if($_SERVER["REQUEST_METHOD"] == 'GET'){
        if(isset($_GET["id"])){

            //VALIDACIÓN DE USUARIO
    
            //Integrar filtros
            
            $sql_idiomas = mysqli_query($conexion_bd, "SELECT * FROM idiomas WHERE cod_idioma =".$_GET["id"]);
            
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

    }
   
    //POST REGISTRAR

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
        $data = json_decode(file_get_contents("php://input"));

        if(trim($data->cod_idioma) == ""){
            echo json_encode(["error"=>"los campos no pueden estar vacíos"]);
        }
        else{
            mysqli_query($conexion_bd, "INSERT INTO `idiomas`(`cod_idioma`, `descripcion`) VALUES ('$data->cod_idioma','$data->descripcion')");
            echo json_encode(["success"=>"datos registrados"]);
        }
        exit();

    }


       