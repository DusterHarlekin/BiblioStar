<?php

    include "conexion.php";
    include "utils/filtering.php";

    $conexion_bd = connect();

     //Prueba consulta de ciudad
    if($_SERVER["REQUEST_METHOD"] == 'GET'){
        
        //Selección de id (ROGER)
        if(isset($_GET["cod_idioma"])){

            //VALIDACIÓN DE USUARIO
    
            //Integrar filtros
            
            $sql_idiomas = mysqli_query($conexion_bd, "SELECT * FROM idiomas WHERE cod_idioma =".$_GET["descripcion"]);
            
            if(mysqli_num_rows($sql_idiomas)>0){
                
               $idiomas = mysqli_fetch_all($sql_idiomas, MYSQLI_ASSOC);
                echo json_encode($idiomas);
             
    
            }else{
    
                echo json_encode(["success"=>0]);
            }
    
            exit();
    
        }

         //CONSULTAR TODAS LAS CIUDADES
         $query = filtrarBusqueda($_GET, 'idiomas');

         $sql_idiomas = mysqli_query($conexion_bd, $query);

           
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


       