<?php
include "conexion.php";
include "utils/security.php";
include "utils/pagination.php";

$conexion_bd = connect();

//Prueba consulta de ciudad
if ($_SERVER["REQUEST_METHOD"] == 'GET') {

    $data = json_decode(json_encode($_GET));

    if (isAuthorized($data, $conexion_bd, true)) {

        //Selección de id (ROGER)  

        $sql_libros = mysqli_query($conexion_bd, "SELECT * FROM libros");
        $sql_salas = mysqli_query($conexion_bd, "SELECT * FROM salas");

        $query = filtrarBusqueda($_GET, 'lib_prestamos');

        $sql_prestamo = mysqli_query($conexion_bd, $query);

        $resData = paginar($sql_prestamo, $_GET, 'lib_prestamos', 'cod_isbn');

        $sql_prestamo = mysqli_query($conexion_bd, $resData["query"]);


        //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
        $rows = mysqli_num_rows($sql_prestamo);


        $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

        if ($rows > 0) {

            $response["data"] = mysqli_fetch_all($sql_prestamo, MYSQLI_ASSOC);

            //AGREGAR INFORMACION UTIL PARA EL FRONTEND

            $response["pagination"] = $resData["pagination"];
        }

        $response["cantLibros"] = mysqli_num_rows($sql_libros);
        $response["cantSalas"] = mysqli_num_rows($sql_salas);
        $response["cantPrestamos"] = $rows;
        echo json_encode($response);
        exit();
    } else {

        echo json_encode(["error" => "No estás autorizado", "code" => 401]);

        exit();
    }
}
