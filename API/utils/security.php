<?php
function secureData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function hashPass($pass)
{
    return password_hash($pass, PASSWORD_DEFAULT);
}

//FUNCION PARA AUTORIZACION
function isAuthorized($data, $conexion_bd, $isLibAuth = false, $isGuestAuth = false)
{
    if (!isset($data->session_user_name) || trim($data->session_user_name) == "" || !isset($data->session_user_role) || trim($data->session_user_role) == "") {


        return false;
    }

    $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol FROM usuarios WHERE usuario='" . $data->session_user_name . "' AND rol='" . $data->session_user_role . "'");

    if (mysqli_num_rows($sql_usuario) <= 0) {

        return false;
    }

    switch ($data->session_user_role) {
        case "admin":
            return true;

        case "librarian":
            if ($isLibAuth) {
                return true;
            }
            echo json_encode(["error" => "No estás autorizado", "code" => 401]);
            return false;

        case "guest":
            if ($isGuestAuth) {
                return true;
            }
            echo json_encode(["error" => "No estás autorizado", "code" => 401]);
            return false;


        default:
            echo json_encode(["error" => "No estás autorizado", "code" => 401]);
            return false;
    }
}

function validateFields($data)
{
    foreach ($data as $clave => $valor) {
        if ((!isset($valor) || trim($valor) == "") && gettype($valor) != "boolean") {
            echo json_encode(["error" => "Los campos no pueden estar vacios"]);
            exit();
        }
    }
}
