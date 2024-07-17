<?php

include "conexion.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de país
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true, true)) {

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
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}
//CAMBIOS EN PUT
//PUT EDITAR
if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (isAuthorized($data, $conexion_bd, true)) {

        if (trim($data->codigo) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);
            //Limpieza de datos a almacenar MODIFICADOS
            $data->pais = secureData($data->pais);

            mysqli_query($conexion_bd, "UPDATE paises SET pais ='" . $data->pais . "' WHERE codigo ='" . $data->codigo . "'");
            echo json_encode([
                "success" => "El país se ha editado exitosamente",
                "codigo" => $data->codigo,
                "pais" => $data->pais,
            ]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}

//DELETE ELIMINAR

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (isAuthorized($data, $conexion_bd, true)) {

        if (trim($data->codigo) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM paises WHERE codigo='" . $data->codigo . "'");

            echo json_encode(["success" => "El país fue eliminado de forma exitosa"]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}

//POST REGISTRAR

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (isAuthorized($data, $conexion_bd, true)) {

        if (trim($data->codigo) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "INSERT INTO `paises`(`codigo`, `pais`) VALUES ('$data->codigo','$data->pais')");

            echo json_encode(["success" => "El país se ha registrado exitosamente"]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}





//CONSULTAR TODOS LOS PAÍSES

// echo json_encode(["success" => 0]);

$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd, true, true)) {

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
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
