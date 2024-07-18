<?php

include "conexion.php";
include "utils/security.php";

$conexion_bd = connect();

//Consulta de información general


//Selección de id (ROGER)   
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $data = json_decode(json_encode($_GET));
    if (isAuthorized($data, $conexion_bd, true)) {


        $sql_info = mysqli_query($conexion_bd, "SELECT * FROM informacion_general");

        if (mysqli_num_rows($sql_info) > 0) {

            $informacion = mysqli_fetch_all($sql_info, MYSQLI_ASSOC);
            echo json_encode($informacion);
        } else {

            echo json_encode(["success" => 0]);
        }

        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}


//PUT EDITAR
if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (isAuthorized($data, $conexion_bd)) {

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
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}
