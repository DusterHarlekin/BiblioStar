<?php
//Api rest
header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

header("Access-Control-Allow-Methods: GET");

header("Allow: GET, POST, OPTIONS, PUT, DELETE");

header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
    include "conexion.php";
    
    $conexion_bd=connect();

     //Prueba consulta de libros
    if(/*isset($_GET["consultar"])*/true){
        $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM ciudades");
        
        if(mysqli_num_rows($sql_libros)>0){
           $libros = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);
            echo json_encode($libros);
            exit();

        }else{
            echo json_encode(["success"=>0]);
        }
 }