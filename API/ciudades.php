<?php

include "conexion.php";

include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true, true)) {

        //Selección de id (ROGER)  
        if (isset($_GET["codigo_ciudad"]) && !isset($_GET["page"])) {

            //VALIDACIÓN DE USUARIO

            $sql_ciudad = mysqli_query($conexion_bd, "SELECT * FROM ciudades WHERE codigo_ciudad='" . $_GET["codigo_ciudad"] . "'");

            if (mysqli_num_rows($sql_ciudad) > 0) {
                $ciudad = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);

                echo json_encode($ciudad);
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

        if (trim($data->codigo_ciudad) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);
            //Limpieza de datos a almacenar MODIFICADOS
            $data->codigo_pais = secureData($data->codigo_pais);
            $data->ciudad = secureData($data->ciudad);

            mysqli_query($conexion_bd, "UPDATE ciudades SET codigo_pais ='" . $data->codigo_pais . "', ciudad ='" . $data->ciudad . "' WHERE codigo_ciudad ='" . $data->codigo_ciudad . "'");
            echo json_encode([
                "success" => "La ciudad fue actualizada exitosamente",
                "codigo_ciudad" => $data->codigo_ciudad,
                "codigo_pais" => $data->codigo_pais,
                "ciudad" => $data->ciudad
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

        if (trim($data->codigo_ciudad) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM ciudades WHERE codigo_ciudad='" . $data->codigo_ciudad . "'");

            echo json_encode(["success" => "La ciudad fue eliminada exitosamente"]);
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

        if (trim($data->codigo_ciudad) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "INSERT INTO `ciudades`(`codigo_pais`, `codigo_ciudad`, `ciudad`) VALUES ('$data->codigo_pais','$data->codigo_ciudad','$data->ciudad')");

            echo json_encode(["success" => "La ciudad fue registrada exitosamente"]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}





//CONSULTAR TODAS LAS CIUDADES


$query = filtrarBusqueda($_GET, 'ciudades');

if (isAuthorized($data, $conexion_bd, true, true)) {

    $sql_ciudad = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_ciudad, $_GET, 'ciudades', "codigo_ciudad");

    $sql_ciudad = mysqli_query($conexion_bd, $resData["query"]);


    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_ciudad);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $ciudad["data"] = mysqli_fetch_all($sql_ciudad, MYSQLI_ASSOC);


        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $ciudad["pagination"] = $resData["pagination"];

        echo json_encode($ciudad);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
