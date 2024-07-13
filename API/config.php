<?php

include "conexion.php";
include "utils/security.php";

$conexion_bd = connect();

//Prueba consulta de información general
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    //Selección de id (ROGER)
    if (isset($_GET["N"])) {


        $sql_info = mysqli_query($conexion_bd, "SELECT * FROM informacion_general WHERE N ='" . $_GET["N"] . "'");

        if (mysqli_num_rows($sql_info) > 0) {

            $informacion = mysqli_fetch_all($sql_info, MYSQLI_ASSOC);
            echo json_encode($informacion);
        } else {

            echo json_encode(["success" => 0]);
        }

        exit();
    }
}

//PUT EDITAR
if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->N) == "") {
        echo json_encode(["error" => "El campo clave no puede esta vacío"]);
    } else {

        //Limpieza de datos a almacenar MODIFICADOS
        $data->nombre_organizacion = secureData($data->nombre_organizacion);
        $data->RIF = secureData($data->RIF);
        $data->direccion = secureData($data->direccion);


        mysqli_query($conexion_bd, "UPDATE informacion_general SET nombre_organizacion ='" . $data->nombre_organizacion . "', RIF ='" . $data->RIF . "', direccion ='" . $data->direccion . "' WHERE N ='" . $data->N . "'");
        echo json_encode([
            "success" => "datos actualizados",
            "N" => $data->N,
            "nombre_organizacion" => $data->nombre_organizacion,
            "RIF" => $data->RIF,
            "direccion" => $data->direccion
        ]);
    }
    exit();
}
