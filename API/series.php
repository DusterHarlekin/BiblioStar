<?php

include "conexion.php";

include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    //Selección de id (ROGER)   
    if (isset($_GET["N"])) {

        //VALIDACIÓN DE USUARIO

        //Integrar filtros
        $sql_serie = mysqli_query($conexion_bd, "SELECT * FROM serie WHERE N=" . $_GET["N"]);

        if (mysqli_num_rows($sql_serie) > 0) {

            $serie = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);
            echo json_encode($serie);
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

        mysqli_query($conexion_bd, "UPDATE serie SET cod_serie ='" . $data->cod_serie . "', DESCRIPCION ='" . $data->DESCRIPCION . "' WHERE N ='" . $data->N . "'");
        echo json_encode([
            "success" => "datos actualizados",
            "N" => $data->N,
            "cod_serie" => $data->cod_serie,
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

        mysqli_query($conexion_bd, "DELETE FROM serie WHERE N=" . $data->N);

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

        mysqli_query($conexion_bd, "INSERT INTO `serie`(`cod_serie`, `DESCRIPCION`) VALUES ('$data->cod_serie','$data->DESCRIPCION')");

        echo json_encode(["success" => "datos registrados"]);
    }
    exit();
}

//CONSULTAR TODAS LAS CIUDADES
$query = filtrarBusqueda($_GET, 'serie');

$sql_serie = mysqli_query($conexion_bd, $query);

$resData = paginar($sql_serie, $_GET, 'serie');

$sql_serie = mysqli_query($conexion_bd, $resData["query"]);

//GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
$rows = mysqli_num_rows($sql_serie);

$resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

if ($rows > 0) {

    $serie["data"] = mysqli_fetch_all($sql_serie, MYSQLI_ASSOC);


    //AGREGAR INFORMACION UTIL PARA EL FRONTEND

    $serie["pagination"] = $resData["pagination"];

    echo json_encode($serie);
} else {

    echo json_encode(["success" => 0]);
}

exit();
