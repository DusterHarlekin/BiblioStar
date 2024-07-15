<?php

include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true, true)) {

        //Selección de id (ROGER)   
        if (isset($_GET["cod_isbn"])) {

            //VALIDACIÓN DE USUARIO

            $sql_prestamo = mysqli_query($conexion_bd, "SELECT * FROM prestamos_lib WHERE cod_isbn=" . $_GET["cod_isbn"]);

            if (mysqli_num_rows($sql_prestamo) > 0) {

                $sql_prestamo = mysqli_fetch_all($sql_prestamo, MYSQLI_ASSOC);
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


















//CONSULTAR TODAS LAS COTAS
$data = json_decode(json_encode($_GET));
if (isAuthorized($data, $conexion_bd, true, true)) {
    $query = filtrarBusqueda($_GET, 'lib_prestamos');

    $sql_prestamo = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_prestamo, $_GET, 'lib_prestamos');

    $sql_prestamo = mysqli_query($conexion_bd, $resData["query"]);


    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_prestamo);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $cota["data"] = mysqli_fetch_all($sql_prestamo, MYSQLI_ASSOC);


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
