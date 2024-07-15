<?php

include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $data = json_decode(json_encode($_GET));
    if (isAuthorized($data, $conexion_bd, true, true)) {
        //Selección de id (ROGER)    
        if (isset($_GET["N"])) {

            //VALIDACIÓN DE USUARIO
            $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros WHERE N=" . $_GET["N"]);

            if (mysqli_num_rows($sql_libros) > 0) {

                $libros = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);
                echo json_encode($libros);
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

            if (!$data->isNewQuote) {
                $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=" . $data->cota_n);

                if (mysqli_num_rows($sql_cota) > 0) {

                    $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
                    $data->cota = $cota[0]["cota"];
                    $data->cod_isbn = $cota[0]["cod_isbn"];
                    $data->cutter = $cota[0]["cutter"];
                    $data->volumen = $cota[0]["volumen"];
                    $data->ejemplar = $cota[0]["ejemplar"];
                    $data->fecha_ing = $cota[0]["fecha_ing"];
                    $data->cota_completa = $cota[0]["cota_completa"];
                } else {

                    echo json_encode(["error" => "Error al obtener cota existente"]);
                    exit();
                }
            } else {

                mysqli_query($conexion_bd, "INSERT INTO `cota`(`cod_isbn`, `cota`, `cutter`, `volumen`, `ejemplar`, `fecha_ing`, `cota_completa`) VALUES ('$data->cod_isbn','$data->cota','$data->cutter','$data->volumen','$data->ejemplar','$data->fecha_ing','$data->cota_completa')");
            }

            //Limpieza de datos a almacenar MODIFICADOS
            $data->cota = secureData($data->cota);
            $data->autor = secureData($data->autor);
            $data->titulo = secureData($data->titulo);
            $data->pais = secureData($data->pais);
            $data->editorial = secureData($data->editorial);
            $data->edicion = secureData($data->edicion);
            $data->ciudad = secureData($data->ciudad);
            $data->anio = secureData($data->anio);
            $data->tomo = secureData($data->tomo);
            $data->pag = secureData($data->pag);
            $data->descripcion = secureData($data->descripcion);
            $data->cod_sala = secureData($data->cod_sala);
            $data->cod_referencia = secureData($data->cod_referencia);
            $data->costo = secureData($data->costo);
            $data->fecha_ing = secureData($data->fecha_ing);
            $data->idioma = secureData($data->idioma);
            $data->participante = secureData($data->participante);
            $data->impresion = secureData($data->impresion);
            $data->observacion = secureData($data->observacion);
            $data->cutter = secureData($data->cutter);
            $data->cota_completa = secureData($data->cota_completa);



            mysqli_query($conexion_bd, "UPDATE libros SET cota ='" . $data->cota . "', cod_isbn ='" . $data->cod_isbn . "', autor ='" . $data->autor . "', titulo ='" . $data->titulo . "', pais ='" . $data->pais . "', editorial ='" . $data->editorial . "', edicion ='" . $data->edicion . "', ciudad ='" . $data->ciudad . "', anio ='" . $data->anio . "', tomo ='" . $data->tomo . "', pag ='" . $data->pag . "', descripcion ='" . $data->descripcion . "', cod_sala ='" . $data->cod_sala . "', cod_referencia ='" . $data->cod_referencia . "', costo ='" . $data->costo . "', fecha_ing ='" . $data->fecha_ing . "', idioma ='" . $data->idioma . "', participante ='" . $data->participante . "', impresion ='" . $data->impresion . "', observacion ='" . $data->observacion . "', cutter ='" . $data->cutter . "', cota_completa ='" . $data->cota_completa . "' WHERE N ='" . $data->N . "'");
            echo json_encode([
                "success" => "El libro se ha editado exitosamente",
                "N" => $data->N,
                "cota" => $data->cota,
                "autor" => $data->autor,
                "titulo" => $data->titulo,
                "pais" => $data->pais,
                "editorial" => $data->editorial,
                "edicion" => $data->edicion,
                "ciudad" => $data->ciudad,
                "anio" => $data->anio,
                "tomo" => $data->tomo,
                "pag" => $data->pag,
                "descripcion" => $data->descripcion,
                "cod_sala" => $data->cod_sala,
                "cod_referencia" => $data->cod_referencia,
                "costo" => $data->costo,
                "fecha_ing" => $data->fecha_ing,
                "idioma" => $data->idioma,
                "participante" => $data->participante,
                "impresion" => $data->impresion,
                "observacion" => $data->observacion,
                "cutter" => $data->cutter,
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

            echo json_encode(["error" => "los campos no pueden estar vacíos"]);
        } else {

            mysqli_query($conexion_bd, "DELETE FROM libros WHERE N=" . $data->N);

            echo json_encode(["success" => "El libro fue eliminado de forma exitosa"]);
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

        if (!$data->isNewQuote) {
            $sql_cota = mysqli_query($conexion_bd, "SELECT * FROM cota WHERE N=" . $data->cota_n);

            if (mysqli_num_rows($sql_cota) > 0) {

                $cota = mysqli_fetch_all($sql_cota, MYSQLI_ASSOC);
                $data->cota = $cota[0]["cota"];
                $data->cod_isbn = $cota[0]["cod_isbn"];
                $data->cutter = $cota[0]["cutter"];
                $data->volumen = $cota[0]["volumen"];
                $data->ejemplar = $cota[0]["ejemplar"];
                $data->fecha_ing = $cota[0]["fecha_ing"];
            } else {

                echo json_encode(["error" => "Error al obtener cota existente"]);
                exit();
            }
        } else {

            mysqli_query($conexion_bd, "INSERT INTO `cota`(`cod_isbn`, `cota`, `cutter`, `volumen`, `ejemplar`, `fecha_ing`, `cota_completa`) VALUES ('$data->cod_isbn','$data->cota','$data->cutter','$data->volumen','$data->ejemplar','$data->fecha_ing','$data->cota_completa')");
        }

        mysqli_query($conexion_bd, "INSERT INTO `libros`(`cota`, `cod_isbn`, `autor`, `titulo`, `pais`, `editorial`, `edicion`, `ciudad`, `anio`, `tomo`, `pag`, `descripcion`, `cod_sala`, `cod_referencia`, `costo`, `fecha_ing`, `idioma`, `participante`, `impresion`, `observacion`, `cutter`, `cota_completa`) VALUES ('$data->cota','$data->cod_isbn','$data->autor','$data->titulo','$data->pais','$data->editorial','$data->edicion','$data->ciudad','$data->anio','$data->tomo','$data->pag','$data->descripcion','$data->cod_sala','$data->cod_referencia','$data->costo','$data->fecha_ing','$data->idioma','$data->participante','$data->impresion','$data->observacion','$data->cutter','$data->cota_completa')");

        echo json_encode(["success" => "El libro se ha registrado exitosamente"]);
        exit();
    } else {
        json_encode(["error" => "No estás autorizado", "code" => 401]);
        exit();
    }
}


//CONSULTAR TODOS LOS LIBROS
$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'libros');

    $sql_libros = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_libros, $_GET, 'libros');

    $sql_libros = mysqli_query($conexion_bd, $resData["query"]);

    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_libros);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $libros["data"] = mysqli_fetch_all($sql_libros, MYSQLI_ASSOC);

        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $libros["pagination"] = $resData["pagination"];
        echo json_encode($libros);
    } else {

        echo json_encode(["success" => 0]);
    }
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}

exit();
