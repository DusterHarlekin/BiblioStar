<?php
include "../conexion.php";

include "../utils/pagination.php";

$conexion_bd = connect();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    //REGISTER

    if (isset($data->request) && $data->request == 'Register') {

        if (isAuthorized($data, $conexion_bd)) {

            validateFields($data);

            try {
                mysqli_query($conexion_bd, "INSERT INTO `usuarios`(`usuario`, `cedula`, `nombre`, `apellido`, `clave`, `rol`) VALUES ('$data->usuario','$data->cedula','$data->nombre','$data->apellido','$data->clave','$data->rol')");

                echo json_encode(["success" => "Usuario registrado exitosamente"]);
            } catch (Exception $e) {
                if (mysqli_errno($conexion_bd) == 1062) {
                    echo json_encode(["error" => "No se pueden registrar usuarios con el mismo nombre o cédula"]);
                }
            }

            exit();
        } else {
            echo json_encode(["error" => "No estás autorizado", "code" => 401]);
            exit();
        }
    }



    //LOGIN
    if (isset($data->request) && $data->request == 'Login') {

        if (isset($data->rol) || trim($data->rol) == "guest") {
            if (!isset($data->cedula) || trim($data->cedula) == "") {
                echo json_encode(["error" => "Los campos no pueden estar vacios"]);
                exit();
            }
            echo json_encode(["cedula" => $data->cedula, "rol" => $data->rol]);
            exit();
        } else if (!isset($data->usuario) || trim($data->usuario) == "" || !isset($data->clave) || trim($data->clave) == "") {
            echo json_encode(["error" => "El usuario y contraseña no pueden estar vacíos"]);
            exit();
        }



        $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol, cedula, nombre, apellido FROM usuarios WHERE usuario='" . $data->usuario . "' AND clave='" . $data->clave . "'");

        if (mysqli_num_rows($sql_usuario) > 0) {
            /*$token = bin2hex(openssl_random_pseudo_bytes(16)); //Generar token 32 caracteres
            $c_date = date("d-m-Y");
            echo json_encode(["success" => $c_date]);
            exit();
            mysqli_query($conexion_bd, "INSERT INTO `sessions`(`token`, `token_creation_date`, `token_expiration_date`, `token_expiration_date`) VALUES ('$token','$token','$data->cedula')"); */
            $usuario = mysqli_fetch_all($sql_usuario, MYSQLI_ASSOC);
            echo json_encode($usuario);
        } else {

            echo json_encode(["error" => "Usuario y/o clave incorrectos, asegúrese de que los datos son correctos"]);
        }

        exit();
    }
}


//GET READ


if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $data = json_decode(json_encode($_GET));
    if (isAuthorized($data, $conexion_bd)) {


        //Selección de id (ROGER)
        if (isset($_GET["cedula"]) && !isset($_GET["page"])) {

            //VALIDACIÓN DE USUARIO

            $sql_usuarios = mysqli_query($conexion_bd, "SELECT * FROM usuarios WHERE cedula ='" . $_GET["cedula"] . "'");

            if (mysqli_num_rows($sql_usuarios) > 0) {

                $usuarios = mysqli_fetch_all($sql_usuarios, MYSQLI_ASSOC);
                //eliminar clave para que no se muestre
                unset($usuarios[0]["clave"]);
                echo json_encode($usuarios);
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


    //AUTENTICACION

    if (isAuthorized($data, $conexion_bd)) {

        foreach ($data as $clave => $valor) {
            if (!isset($valor) || trim($valor) == "") {
                if ($clave != "clave") {
                    echo json_encode(["error" => "Los campos no pueden estar vacios"]);
                    exit();
                }
            }
        }

        try {
            if (!isset($data->clave) || trim($data->clave) == "") {
                mysqli_query($conexion_bd, "UPDATE usuarios SET usuario='" . $data->usuario . "', nombre='" . $data->nombre . "', apellido='" . $data->apellido . "' WHERE cedula='" . $data->cedula . "'");
            } else {
                mysqli_query($conexion_bd, "UPDATE usuarios SET usuario='" . $data->usuario . "', nombre='" . $data->nombre . "', apellido='" . $data->apellido . "', clave='" . $data->clave . "' WHERE cedula='" . $data->cedula . "'");
            }

            echo json_encode(["success" => "Usuario editado con éxito"]);
        } catch (Exception $e) {
            if (mysqli_errno($conexion_bd) == 1062) {
                echo json_encode(["error" => "No se pueden registrar usuarios con el mismo nombre"]);
            }
        }

        exit();
    } else {

        exit();
    }
}

//DELETE

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (isAuthorized($data, $conexion_bd)) {

        if (!isset($data->cedula) || trim($data->cedula) == "") {
            echo json_encode(["error" => "Los campos no pueden estar vacios"]);
            exit();
        }

        mysqli_query($conexion_bd, "DELETE FROM usuarios WHERE cedula='" . $data->cedula . "'");

        echo json_encode(["success" => "El usuario fue eliminado de forma exitosa"]);

        exit();
    } else {
        echo json_encode(["error" => "No estás autorizado", "code" => 401]);
        exit();
    }
}

//get all

$data = json_decode(json_encode($_GET));

if (isAuthorized($data, $conexion_bd)) {
    $query = filtrarBusqueda($_GET, 'usuarios');

    $sql_usuarios = mysqli_query($conexion_bd, $query);

    $resData = paginar($sql_usuarios, $_GET, 'usuarios', 'cedula');

    $sql_usuarios = mysqli_query($conexion_bd, $resData["query"]);

    //GUARDAR LA CANTIDAD DE FILAS EN UNA VARIABLE PARA FACILIDAD DE USO
    $rows = mysqli_num_rows($sql_usuarios);

    $resData["pagination"]["end"] = $resData["pagination"]["start"] - 1 + $rows;

    if ($rows > 0) {

        $usuarios["data"] = mysqli_fetch_all($sql_usuarios, MYSQLI_ASSOC);
        foreach ($usuarios["data"] as $key => $value) {
            unset($usuarios["data"][$key]["clave"]); //eliminamos clave para que no se muestre
        }


        //AGREGAR INFORMACION UTIL PARA EL FRONTEND

        $usuarios["pagination"] = $resData["pagination"];

        echo json_encode($usuarios);
    } else {

        echo json_encode(["success" => 0]);
    }

    exit();
} else {

    echo json_encode(["error" => "No estás autorizado", "code" => 401]);

    exit();
}
