<?php

include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));


    if (isAuthorized($data, $conexion_bd, true, true)) {

        //Selección de id (ROGER)   
        if (isset($_GET["cod_isbn"]) && !isset($_GET["page"])) {

            //VALIDACIÓN DE USUARIO
            $sql_prestamo = mysqli_query($conexion_bd, "SELECT * FROM lib_prestamos WHERE cod_isbn='" . $_GET["cod_isbn"] . "'");

            if (mysqli_num_rows($sql_prestamo) > 0) {

                $prestamo = mysqli_fetch_all($sql_prestamo, MYSQLI_ASSOC);
                echo json_encode($prestamo);
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

        if (trim($data->cod_isbn) == "") {
            echo json_encode(["error" => "El campo clave no puede esta vacío"]);
        } else {
            validateFields($data);
            //Limpieza de datos a almacenar MODIFICADOS

            mysqli_query($conexion_bd, "UPDATE lib_prestamos SET cedula ='" . $data->cedula . "', observacion='" . $data->observacion .  "' WHERE cod_isbn ='" . $data->cod_isbn . "'");
            echo json_encode([
                "success" => "El préstamo se ha editado exitosamente",
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

        if (trim($data->cod_isbn) == "") {

            echo json_encode(["error" => "Los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM lib_prestamos WHERE cod_isbn='" . $data->cod_isbn . "'");

            echo json_encode(["success" => "Devolución confirmada correctamente"]);
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

        if (trim($data->cod_isbn) == "") {

            echo json_encode(["error" => "Un libro sin ISBN no puede ser prestado"]);
        } else {
            //asignamos la fecha de hoy en formato D/M/Y
            $data->fecha_s = date("d/m/Y");
            $data->fecha_e = date("d/m/Y");

            //ASIGNAMOS LA HORA ACTUAL EN FORMATO H:i
            $data->hora_s = date("H:i");
            $data->hora_e = date("H:i");

            //AGREGAMOS 3 DIAS A LA FECHA DE PRESTAMO

            $fecha_str = str_replace("/", "-", $data->fecha_e);
            $fecha_timestamp = strtotime($fecha_str);

            $data->fecha_e = strtotime('+3 days', $fecha_timestamp);


            $data->fecha_e = date('d/m/Y', $data->fecha_e);

            mysqli_query($conexion_bd, "INSERT INTO `lib_prestamos`(`cod_isbn`, `titulo`, `cedula`, `observacion`, `fecha_s`, `fecha_e`, `hora_s`, `hora_e`) VALUES ('$data->cod_isbn','$data->titulo','$data->cedula','$data->observacion','$data->fecha_s','$data->fecha_e','$data->hora_s','$data->hora_e')");

            echo json_encode(["success" => "El préstamo se ha registrado exitosamente"]);
        }
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}





//CONSULTAR TODOS LOS PRESTAMOS
$data = json_decode(json_encode($_GET));
if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'lib_prestamos');

    $sql_prestamo = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_prestamo, $_GET, 'lib_prestamos', 'cod_isbn');

    $sql_prestamo = mysqli_query($conexion_bd, $resData["query"]);


    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_prestamo);


    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $prestamo["data"] = mysqli_fetch_all($sql_prestamo, MYSQLI_ASSOC);


        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $prestamo["pagination"] = $resData["pagination"];

        echo json_encode($prestamo);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
