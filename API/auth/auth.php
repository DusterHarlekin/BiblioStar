<?php
include "../conexion.php";
include "../utils/security.php";

$conexion_bd = connect();

//POST REGISTRAR

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $data = json_decode(file_get_contents("php://input"));


    //REGISTER
    if (isset($data->request) && $data->request == 'Register') {
        foreach ($data as $clave => $valor) {
            if (!isset($valor) || trim($valor) == "") {
                echo json_encode(["error" => "Los campos no pueden estar vacios"]);
                exit();
            }
        }

        try {
            mysqli_query($conexion_bd, "INSERT INTO `usuarios`(`usuario`, `cedula`, `nombre`, `apellido`, `clave`, `rol`) VALUES ('$data->usuario','$data->cedula','$data->nombre','$data->apellido','$data->clave','$data->rol')");

            echo json_encode(["success" => "Usuario registrado"]);
        } catch (Exception $e) {
            if (mysqli_errno($conexion_bd) == 1062) {
                echo json_encode(["error" => "No se pueden registrar usuarios con el mismo nombre o cédula"]);
            }
        }

        exit();
    }

    //LOGIN
    if (isset($data->request) && $data->request == 'Login') {
        if (!isset($data->usuario) || trim($data->usuario) == "" || !isset($data->clave) || trim($data->clave) == "") {
            echo json_encode(["error" => "El usuario y contraseña no pueden estar vacíos"]);
            exit();
        }

        $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol FROM usuarios WHERE usuario='" . $data->usuario . "' AND clave='" . $data->clave . "'");

        if (mysqli_num_rows($sql_usuario) > 0) {

            $usuario = mysqli_fetch_all($sql_usuario, MYSQLI_ASSOC);
            echo json_encode($usuario);
        } else {

            echo json_encode(["error" => "Usuario y/o clave incorrectos, asegúrese de que los datos son correctos"]);
        }

        exit();
    }
}

//PUT EDITAR

if ($_SERVER["REQUEST_METHOD"] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));


    //AUTENTICACION

    if (!isset($data->session_user_name) || trim($data->session_user_name) == "" || !isset($data->session_user_role) || trim($data->session_user_role) == "") {
        echo json_encode(["empty_session" => true]);
        exit();
    }

    if ($data->session_user_role == 'admin' || $data->session_user_role == 'librarian') {

        $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol FROM usuarios WHERE usuario='" . $data->session_user_name . "' AND rol='" . $data->session_user_role . "'");

        if (mysqli_num_rows($sql_usuario) <= 0) {
            echo json_encode(["error" => "No estás autorizado"]);
            exit();
        }

        foreach ($data as $clave => $valor) {
            if (!isset($valor) || trim($valor) == "") {
                echo json_encode(["error" => "Los campos no pueden estar vacios"]);
                exit();
            }
        }

        try {
            mysqli_query($conexion_bd, "UPDATE usuarios SET usuario='" . $data->usuario . "', nombre='" . $data->nombre . "', apellido='" . $data->apellido . "', clave='" . $data->clave . "' WHERE cedula='" . $data->cedula . "'");

            echo json_encode(["success" => "Usuario editado con éxito"]);
        } catch (Exception $e) {
            if (mysqli_errno($conexion_bd) == 1062) {
                echo json_encode(["error" => "No se pueden registrar usuarios con el mismo nombre"]);
            }
        }

        exit();
    } else {
        echo json_encode(["error" => "No estás autorizado", "code" => 401]);
        exit();
    }
}

//DELETE

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->session_user_name) || trim($data->session_user_name) == "" || !isset($data->session_user_role) || trim($data->session_user_role) == "") {
        echo json_encode(["empty_session" => true]);
        exit();
    }

    if ($data->session_user_role == 'admin') {

        $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol FROM usuarios WHERE usuario='" . $data->session_user_name . "' AND rol='" . $data->session_user_role . "'");

        if (mysqli_num_rows($sql_usuario) <= 0) {
            echo json_encode(["error" => "No estás autorizado"]);
            exit();
        }


        if (!isset($data->cedula) || trim($data->cedula) == "") {
            echo json_encode(["error" => "Los campos no pueden estar vacios"]);
            exit();
        }



        mysqli_query($conexion_bd, "DELETE FROM usuarios WHERE cedula=" . $data->cedula);

        echo json_encode(["success" => "El usuario fue eliminado de forma exitosa"]);

        exit();
    } else {
        echo json_encode(["error" => "No estás autorizado", "code" => 401]);
        exit();
    }



    if (trim($data->N) == "") {

        echo json_encode(["error" => "los campos no pueden estar vacíos"]);
    } else {

        mysqli_query($conexion_bd, "DELETE FROM serie WHERE N=" . $data->N);

        echo json_encode(["success" => "La sala fue eliminada de forma exitosa"]);
    }
    exit();
}
