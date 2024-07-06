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
