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
        $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros WHERE /*cod_serie*/N=".$_GET["titulo"]);
        
        if(mysqli_num_rows($sql_libros)>0){
            
           $cota = mysqli_fetch_all($sql_libros , MYSQLI_ASSOC);
            echo json_encode($libros);
         

        }else{

            echo json_encode(["success"=>0]);

        }

        exit();

    }
}

        //CONSULTAR TODAS LAS CIUDADES
        $query = filtrarBusqueda($_GET, 'libros');

        $sql_libros = mysqli_query($conexion_bd, $query);

            
    if(mysqli_num_rows($sql_libros)>0){

            $libros = mysqli_fetch_all($sql_libros , MYSQLI_ASSOC);
            echo json_encode($libros);

        }else{

            echo json_encode(["success"=>0]);
        }

        exit();
   //POST REGISTRAR

   if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
    $data = json_decode(file_get_contents("php://input"));

    if(trim($data->autor) == ""){
        echo json_encode(["error"=>"los campos no pueden estar vacíos"]);
    }
    else{
        mysqli_query($conexion_bd, "INSERT INTO `libros`(`N`, `cota`, `cod_isbn`, `autor`, `titulo`, `pais`, `editorial`, `edicion`, `ciudad`, `anio`, `tomo`, `pag`, `descripcion`, `cod_sala`, `cod_referencia`, `costo`, `fecha_ing`, `idioma`, `participante`, `impresion`, `observacion`, `cutter`, `cota_completa`) VALUES ('$data->N','$data->cota','$data->cod_isbn','$data->autor','$data->titulo','$data->pais','$data->editorial','$data->edicion','$data->ciudad','$data->anio','$data->tomo','$data->pag','$data->descripcion','$data->cod_sala','$data->cod_referencia','$data->costo','$data->fecha_ing','$data->idioma','$data->participante','$data->impresion','$data->observacion','$data->cutter','$data->cota_completa')");

        echo json_encode(["success"=>"datos registrados"]);
    }
    exit();

}
