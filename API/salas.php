<?php

include "conexion.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true, true)) {
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
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}

//PUT EDITAR
if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));
    if (isAuthorized($data, $conexion_bd, true)) {

        if (trim($data->N) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);
            //Limpieza de datos a almacenar MODIFICADOS
            $data->DESCRIPCION = secureData($data->DESCRIPCION);
            $data->cod_sala = secureData($data->cod_sala);

            mysqli_query($conexion_bd, "UPDATE salas SET cod_sala ='" . $data->cod_sala . "', DESCRIPCION ='" . $data->DESCRIPCION . "' WHERE N ='" . $data->N . "'");
            echo json_encode([
                "success" => "La sala se ha editado exitosamente",
                "N" => $data->N,
                "cod_sala" => $data->cod_sala,
                "DESCRIPCION" => $data->DESCRIPCION
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

        if (trim($data->N) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM salas WHERE N='" . $data->N . "'");

            echo json_encode(["success" => "La sala fue eliminada de forma exitosa"]);
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

    if (isAuthorized($data, $conexion_bd)) {

        validateFields($data);

        mysqli_query($conexion_bd, "INSERT INTO `salas`(`cod_sala`, `DESCRIPCION`) VALUES ('$data->cod_sala','$data->DESCRIPCION')");

        echo json_encode(["success" => "La sala se ha registrado exitosamente"]);
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}




//CONSULTAR TODAS LAS SALAS
$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd, true, true)) {

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
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
