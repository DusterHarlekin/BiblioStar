<?php

include "conexion.php";
include "utils/filtering.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

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
}

//PUT EDITAR

if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->N) == "") {
        echo json_encode(["error" => "El campo clave no puede esta vacío"]);
    } else {
        mysqli_query($conexion_bd, "UPDATE libros SET cota ='" . $data->cota . "', cod_isbn ='" . $data->cod_isbn . "', autor ='" . $data->autor . "', titulo ='" . $data->titulo . "', pais ='" . $data->pais . "', editorial ='" . $data->editorial . "', edicion ='" . $data->edicion . "', ciudad ='" . $data->ciudad . "', anio ='" . $data->anio . "', tomo ='" . $data->tomo . "', pag ='" . $data->pag . "', descripcion ='" . $data->descripcion . "', cod_sala ='" . $data->cod_sala . "', cod_referencia ='" . $data->cod_referencia . "', costo ='" . $data->costo . "', fecha_ing ='" . $data->fecha_ing . "', idioma ='" . $data->idioma . "', participante ='" . $data->participante . "', impresion ='" . $data->impresion . "', observacion ='" . $data->observacion . "', cutter ='" . $data->cutter . "', cota_completa ='" . $data->cota_completa . "' WHERE N ='" . $data->N . "'");
        echo json_encode([
            "success" => "datos actualizados",
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
}


//POST REGISTRAR

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    if (trim($data->autor) == "") {
        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {
        mysqli_query($conexion_bd, "INSERT INTO `libros`(`N`, `cota`, `cod_isbn`, `autor`, `titulo`, `pais`, `editorial`, `edicion`, `ciudad`, `anio`, `tomo`, `pag`, `descripcion`, `cod_sala`, `cod_referencia`, `costo`, `fecha_ing`, `idioma`, `participante`, `impresion`, `observacion`, `cutter`, `cota_completa`) VALUES ('$data->N','$data->cota','$data->cod_isbn','$data->autor','$data->titulo','$data->pais','$data->editorial','$data->edicion','$data->ciudad','$data->anio','$data->tomo','$data->pag','$data->descripcion','$data->cod_sala','$data->cod_referencia','$data->costo','$data->fecha_ing','$data->idioma','$data->participante','$data->impresion','$data->observacion','$data->cutter','$data->cota_completa')");

        echo json_encode(["success" => "datos registrados"]);
    }
    exit();
}


//CONSULTAR TODOS LOS LIBROS
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

exit();
