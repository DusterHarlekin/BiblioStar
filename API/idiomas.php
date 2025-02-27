<?php

include "conexion.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de idioma
$data = json_decode(json_encode($_GET));

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    if (isAuthorized($data, $conexion_bd, true, true)) {


        //Selección de id (ROGER)
        if (isset($_GET["cod_idioma"]) && !isset($_GET["page"])) {

            //VALIDACIÓN DE USUARIO

            $sql_idiomas = mysqli_query($conexion_bd, "SELECT * FROM idiomas WHERE cod_idioma ='" . $_GET["cod_idioma"] . "'");

            if (mysqli_num_rows($sql_idiomas) > 0) {

                $idiomas = mysqli_fetch_all($sql_idiomas, MYSQLI_ASSOC);
                echo json_encode($idiomas);
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

        if (trim($data->cod_idioma) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);
            //Limpieza de datos a almacenar MODIFICADOS
            $data->descripcion = secureData($data->descripcion);


            mysqli_query($conexion_bd, "UPDATE idiomas SET descripcion ='" . $data->descripcion . "' WHERE cod_idioma ='" . $data->cod_idioma . "'");
            echo json_encode([
                "success" => "El idioma se ha editado exitosamente",
                "cod_idioma" => $data->cod_idioma,
                "descripcion" => $data->descripcion
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


        if (trim($data->cod_idioma) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM idiomas WHERE cod_idioma='" . $data->cod_idioma . "'");

            echo json_encode(["success" => "El idioma fue eliminado exitosamente"]);
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
        validateFields($data);
        if (trim($data->cod_idioma) == "") {
            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {
            mysqli_query($conexion_bd, "INSERT INTO `idiomas`(`cod_idioma`, `descripcion`) VALUES ('$data->cod_idioma','$data->descripcion')");
            echo json_encode([
                "success" => "El idioma se ha registrado exitosamente",
                "cod_idioma" => $data->cod_idioma,
                "descripcion" => $data->descripcion
            ]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}



//CONSULTAR TODOS LOS IDIOMAS
$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'idiomas');

    $sql_idiomas = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_idiomas, $_GET, 'idiomas', 'cod_idioma');

    $sql_idiomas = mysqli_query($conexion_bd, $resData["query"]);

    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_idiomas);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $idiomas["data"] = mysqli_fetch_all($sql_idiomas, MYSQLI_ASSOC);

        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $idiomas["pagination"] = $resData["pagination"];

        echo json_encode($idiomas);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
