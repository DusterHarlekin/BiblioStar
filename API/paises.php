<?php

include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de país
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    //Selección de id (ROGER)  
    if (isset($_GET["codigo"]) && !isset($_GET["page"])) {

        //VALIDACIÓN DE USUARIO

        $sql_pais = mysqli_query($conexion_bd, "SELECT * FROM paises WHERE codigo='" . $_GET["codigo"] . "'");

        if (mysqli_num_rows($sql_pais) > 0) {
            $pais = mysqli_fetch_all($sql_pais, MYSQLI_ASSOC);

            echo json_encode($pais);
        } else {
            echo json_encode(["success" => 0]);
        }

        exit();
    }
}
//CAMBIOS EN PUT
//PUT EDITAR
if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->codigo) == "") {
        echo json_encode(["error" => "El campo clave no puede esta vacío"]);
    } else {
        validateFields($data);
        //Limpieza de datos a almacenar MODIFICADOS
        $data->pais = secureData($data->pais);

        mysqli_query($conexion_bd, "UPDATE paises SET pais ='" . $data->pais . "' WHERE codigo ='" . $data->codigo . "'");
        echo json_encode([
            "success" => "datos actualizados",
            "codigo" => $data->codigo,
            "pais" => $data->pais,
        ]);
    }
    exit();
}

//DELETE ELIMINAR

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->codigo) == "") {

        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {

        mysqli_query($conexion_bd, "DELETE FROM paises WHERE codigo='" . $data->codigo. "'");

        echo json_encode(["success" => "El país fue eliminado de forma exitosa"]);
    }
    exit();
}

//POST REGISTRAR

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->codigo) == "") {

        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {

        mysqli_query($conexion_bd, "INSERT INTO `paises`(`codigo`, `pais`) VALUES ('$data->codigo','$data->pais')");

        echo json_encode(["success" => "datos registrados"]);
    }
    exit();
}





//CONSULTAR TODOS LOS PAÍSES

// echo json_encode(["success" => 0]);

$query = filtrarBusqueda($_GET, 'paises');

$sql_pais = mysqli_query($conexion_bd, $query);

$resData = paginar($sql_pais, $_GET, 'paises', 'codigo');

$sql_pais = mysqli_query($conexion_bd, $resData["query"]);


//GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
$rows = mysqli_num_rows($sql_pais);

$resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

if ($rows > 0) {

    $pais["data"] = mysqli_fetch_all($sql_pais, MYSQLI_ASSOC);


    //AGREGAR INFORMACION UTIL PARA EL FRONTEND

    $pais["pagination"] = $resData["pagination"];

    echo json_encode($pais);
} else {

    echo json_encode(["success" => 0]);
}

exit();
