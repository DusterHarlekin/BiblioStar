<?php

include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de cota
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true, true)) {

        //Selección de id (ROGER)   
        if (isset($_GET["N"])) {

            //VALIDACIÓN DE USUARIO

            $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=" . $_GET["N"]);

            if (mysqli_num_rows($sql_cota) > 0) {

                $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
                echo json_encode($cota);
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
            $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=" . $data->N);

            if (mysqli_num_rows($sql_cota) > 0) {

                $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
                $cota = json_decode(json_encode($cota[0]));
                //VERIFICAMOS QUE NO EXISTA UN LIBRO CON ESA COTA
                $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros WHERE cota = '" . $cota->cota . "' AND cod_isbn = '" . $cota->cod_isbn . "' AND cutter = '" . $cota->cutter . "' AND cota_completa = '" . $cota->cota_completa . "'");


                if (mysqli_num_rows($sql_libros) > 0) {

                    $libros = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);

                    mysqli_query($conexion_bd, "UPDATE libros SET cota = '" . $data->cota . "', cota_completa = '" . $data->cota_completa . "', cutter = '" . $data->cutter . "', cod_isbn = '" . $data->cod_isbn . "' WHERE cota = '" . $cota->cota . "' AND cod_isbn = '" . $cota->cod_isbn . "' AND cutter = '" . $cota->cutter . "' AND cota_completa = '" . $cota->cota_completa . "'");
                }
            } else {
                $cota = [];
                $sql_libros = [];
            }


            //Limpieza de datos a almacenar MODIFICADOS
            $data->cota = secureData($data->cota);
            $data->cod_isbn = secureData($data->cod_isbn);
            $data->cutter = secureData($data->cutter);
            $data->volumen = secureData($data->volumen);
            $data->ejemplar = secureData($data->ejemplar);
            $data->fecha_ing = secureData($data->fecha_ing);
            $data->cota_completa = secureData($data->cota_completa);
            $libros = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);


            mysqli_query($conexion_bd, "UPDATE cota SET cod_isbn ='" . $data->cod_isbn . "', cota ='" . $data->cota . "', cutter ='" . $data->cutter . "', volumen ='" . $data->volumen . "', ejemplar ='" . $data->ejemplar . "', fecha_ing ='" . $data->fecha_ing . "', cota_completa ='" . $data->cota_completa . "' WHERE N ='" . $data->N . "'");
            echo json_encode([
                "success" => "La cota se ha editado exitosamente",
                "N" => $data->N,
                "cod_isbn" => $data->cod_isbn,
                "cota" => $data->cota,
                "cutter" => $data->cutter,
                "volumen" => $data->volumen,
                "ejemplar" => $data->ejemplar,
                "fecha_ing" => $data->fecha_ing,
                "cota_completa" => $data->cota_completa
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

            //VERIFICAMOS QUE NO EXISTA UN LIBRO CON ESA COTA
            $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros WHERE cota='" . $data->cota . "' AND cod_isbn='" . $data->cod_isbn . "' AND cutter='" . $data->cutter . "' AND cota_completa='" . $data->cota_completa . "'");



            if (mysqli_num_rows($sql_libros) > 0) {

                if (!isset($data->confirmed) || !$data->confirmed) {

                    $libros = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);
                    $foundBookTitle = $libros[0]["titulo"];
                    echo json_encode(["bookFound" => true, "foundMessage" => "Esta cota ya tiene un libro asignado ($foundBookTitle). ¿Estás seguro de que deseas eliminarla? El libro no tendrá una cota asignada."]);
                    exit();
                } else {
                    $sql_libros = mysqli_query($conexion_bd, "UPDATE libros SET cota = '', cota_completa = '', cutter = '', cod_isbn = '' WHERE cota = '" . $data->cota . "' AND cod_isbn = '" . $data->cod_isbn . "' AND cutter = '" . $data->cutter . "' AND cota_completa = '" . $data->cota_completa . "'");
                }
            }

            mysqli_query($conexion_bd, "DELETE FROM cota WHERE N='" . $data->N . "'");

            echo json_encode(["success" => "La cota fue eliminada exitosamente"]);
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

        mysqli_query($conexion_bd, "INSERT INTO `cota`(`cod_isbn`, `cota`, `cutter`, `volumen`, `ejemplar`, `fecha_ing`, `cota_completa`) VALUES ('$data->cod_isbn','$data->cota','$data->cutter','$data->volumen','$data->ejemplar','$data->fecha_ing','$data->cota_completa')");

        echo json_encode(["success" => "La cota se ha registrado exitosamente"]);

        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}

//CONSULTAR TODAS LAS COTAS
$data = json_decode(json_encode($_GET));
if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'cota');

    $sql_cota = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_cota, $_GET, 'cota');

    $sql_cota = mysqli_query($conexion_bd, $resData["query"]);


    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_cota);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $cota["data"] = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);


        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $cota["pagination"] = $resData["pagination"];

        echo json_encode($cota);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
