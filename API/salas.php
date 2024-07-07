<?php

include "conexion.php";
include "utils/pagination.php";
include "utils/security.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    //Selección de id (ROGER)  
    if (isset($_GET["N"])) {

        //VALIDACIÓN DE USUARIO

        $sql_salas = mysqli_query($conexion_bd, "SELECT * FROM salas WHERE N=" . $_GET["N"]);

        if (mysqli_num_rows($sql_salas) > 0) {

            $salas = mysqli_fetch_all($sql_salas, MYSQLI_ASSOC);
            echo json_encode($salas);
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
        $data->DESCRIPCION = secureData($data->DESCRIPCION);
        $data->cod_sala = secureData($data->cod_sala);

        mysqli_query($conexion_bd, "UPDATE salas SET cod_sala ='" . $data->cod_sala . "', DESCRIPCION ='" . $data->DESCRIPCION . "' WHERE N ='" . $data->N . "'");
        echo json_encode([
            "success" => "datos actualizados",
            "N" => $data->N,
            "cod_sala" => $data->cod_sala,
            "DESCRIPCION" => $data->DESCRIPCION
        ]);
    }
    exit();
}

//DELETE ELIMINAR

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->N) == "") {

        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {

        mysqli_query($conexion_bd, "DELETE FROM salas WHERE N=" . $data->N);

        echo json_encode(["success" => "La sala fue eliminada de forma exitosa"]);
    }
    exit();
}

//POST REGISTRAR

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->N) == "") {

        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {

        mysqli_query($conexion_bd, "INSERT INTO `salas`(`N`, `cod_sala`, `DESCRIPCION`) VALUES ('$data->N','$data->cod_sala','$data->DESCRIPCION')");

        echo json_encode(["success" => "datos registrados"]);
    }
    exit();
}




//CONSULTAR TODAS LAS CIUDADES
$query = filtrarBusqueda($_GET, 'salas');

$sql_salas = mysqli_query($conexion_bd, $query);

$resData = paginar($sql_salas, $_GET, 'salas');

$sql_salas = mysqli_query($conexion_bd, $resData["query"]);

//GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
$rows = mysqli_num_rows($sql_salas);

$resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

if ($rows > 0) {

    $salas["data"] = mysqli_fetch_all($sql_salas, MYSQLI_ASSOC);


    //AGREGAR INFORMACION UTIL PARA EL FRONTEND

    $salas["pagination"] = $resData["pagination"];

    echo json_encode($salas);
} else {

    echo json_encode(["success" => 0]);
}

exit();
