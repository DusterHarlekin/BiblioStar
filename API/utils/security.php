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

function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

//FUNCION PARA AUTORIZACION
function isAuthorized($data, $conexion_bd, $isLibAuth = false, $isGuestAuth = false)
{
    if (!isset($data->session_user_name) || trim($data->session_user_name) == "" || !isset($data->session_user_role) || trim($data->session_user_role) == "") {

        echo json_encode(["error" => "invalid session"]);

        exit();
    }

    $sql_usuario = mysqli_query($conexion_bd, "SELECT usuario, rol FROM usuarios WHERE usuario='" . $data->session_user_name . "' AND rol='" . $data->session_user_role . "'");



    switch ($data->session_user_role) {
        case "admin":
            if (mysqli_num_rows($sql_usuario) <= 0) {

                return false;
            }
            return true;

        case "librarian":
            if (!$isLibAuth || mysqli_num_rows($sql_usuario) <= 0) {
                return false;
            }

            return true;

        case "guest":
            if ($isGuestAuth) {
                return true;
            }
            return false;


        default:

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
