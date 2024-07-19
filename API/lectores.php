<?php

include "conexion.php";
include "utils/pagination.php";


$conexion_bd = connect();

//Prueba consulta de lector
$data = json_decode(json_encode($_GET));

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    if (isAuthorized($data, $conexion_bd, true, true)) {


        //Selección de id (ROGER)
        if (isset($_GET["N"]) && !isset($_GET["page"])) {

            //VALIDACIÓN DE USUARIO

            $sql_lectores = mysqli_query($conexion_bd, "SELECT * FROM lect_prestamos WHERE N ='" . $_GET["N"] . "'");

            if (mysqli_num_rows($sql_lectores) > 0) {

                $lectores = mysqli_fetch_all($sql_lectores, MYSQLI_ASSOC);
                echo json_encode($lectores);
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

        if (trim($data->cedula) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);


            mysqli_query($conexion_bd, "UPDATE `lect_prestamos` SET `cedula` = '$data->cedula', `nombre` = '$data->nombre', `apellido`= '$data->apellido', `direccion` = '$data->direccion', `telefono` = '$data->telefono', `correo` = '$data->correo', `edad` = '$data->edad', `sexo` = '$data->sexo' WHERE `N` = '$data->N'");

            echo json_encode([
                "success" => "El lector se ha editado exitosamente",
                "cedula" => $data->cedula,
                "descripcion" => $data->descripcion,
                "sexo" => $data->sexo,
                "edad" => $data->edad,
                "telefono" => $data->telefono,
                "direccion" => $data->direccion,
                "correo" => $data->correo,
                "apellido" => $data->apellido,
                "nombre" => $data->nombre
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

            mysqli_query($conexion_bd, "DELETE FROM lect_prestamos WHERE N='" . $data->N . "'");

            echo json_encode(["success" => "El lector fue eliminado exitosamente"]);
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
        if (trim($data->cedula) == "") {
            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {
            try {
                mysqli_query($conexion_bd, "INSERT INTO `lect_prestamos`(`cedula`, `nombre`, `apellido`, `direccion`, `telefono`, `correo`, `edad`, `sexo`) VALUES ('$data->cedula','$data->nombre','$data->apellido','$data->direccion','$data->telefono','$data->correo','$data->edad','$data->sexo')");
            } catch (Exception $e) {
                if (mysqli_errno($conexion_bd) == 1062) {
                    echo json_encode(["error" => "No se pueden registrar lectores con la misma cédula"]);
                    exit();
                } else {
                    echo json_encode(["error" => $e->getMessage()]);
                }
            }

            echo json_encode([
                "success" => "El lector se ha registrado exitosamente",
                "cedula" => $data->cedula,
                "descripcion" => $data->descripcion
            ]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}



//CONSULTAR TODOS LOS lectores
$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'lect_prestamos');

    $sql_lectores = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_lectores, $_GET, 'lect_prestamos', 'cedula');

    $sql_lectores = mysqli_query($conexion_bd, $resData["query"]);

    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_lectores);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $lectores["data"] = mysqli_fetch_all($sql_lectores, MYSQLI_ASSOC);

        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $lectores["pagination"] = $resData["pagination"];

        echo json_encode($lectores);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
