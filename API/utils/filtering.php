<?php
include "../utils/security.php";
//error_reporting(0);
function filtrarBusqueda($getArray, $table, $pagQuery = null)
{

    //CONSULTA CON FILTROS
    $query = "SELECT * FROM " . $table;

    $filtered_get = array_filter($getArray); // removes empty values from $_GET


    unset($filtered_get["page"]);
    unset($filtered_get["session_user_name"]);
    unset($filtered_get["session_user_role"]);
    $dateQuery = $filtered_get["dateQuery"] ? $filtered_get["dateQuery"] : null;

    $dates = [];

    if (count($filtered_get)) {
        foreach ($filtered_get as $key => $value) {
            if (validateDate($value)) {
                $dates[$key] = $value;


                unset($filtered_get[$key]);
            }
        }
    }



    if (count($filtered_get)) { // not empty
        $query .= " WHERE";



        foreach ($filtered_get as $key => $value) {

            if ($key == "dateQuery") {
                unset($filtered_get[$key]);
                continue;
            }



            $query .= " $key LIKE '%$value%'";  // $filtered_get keyname = $filtered_get['keyname'] value
            if (count($filtered_get) > 1 && (count($filtered_get) - 1 > array_search($key, array_keys($filtered_get)))) { // more than one search filter, and not the last
                $query .= " AND";
            }
        }


        if (count($dates) && $dateQuery) {

            $query .= count($filtered_get) ? " AND" : '';
            $dateValues = array_values($dates);
            if (count($dates) > 1) {

                $query .= " STR_TO_DATE(" . $dateQuery . ", '%d/%m/%Y') BETWEEN STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y') AND STR_TO_DATE('" . $dateValues[1] . "', '%d/%m/%Y') OR STR_TO_DATE(" . $dateQuery . ", '%d/%c/%Y') BETWEEN DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%d/%c/%Y') AND DATE_FORMAT(STR_TO_DATE('" . $dateValues[1] . "', '%d/%m/%Y'), '%d/%c/%Y') OR STR_TO_DATE(" . $dateQuery . ", '%Y%m%d') BETWEEN DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%Y%m%d') AND DATE_FORMAT(STR_TO_DATE('" . $dateValues[1] . "', '%d/%m/%Y'), '%Y%m%d')";
            } else {
                $query .= " STR_TO_DATE(" . $dateQuery . ", '%d/%m/%Y') BETWEEN STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y') AND STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y') OR STR_TO_DATE(" . $dateQuery . ", '%d/%c/%Y') BETWEEN DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%d/%c/%Y') AND DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%d/%c/%Y') OR STR_TO_DATE(" . $dateQuery . ", '%Y%m%d') BETWEEN DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%Y%m%d') AND DATE_FORMAT(STR_TO_DATE('" . $dateValues[0] . "', '%d/%m/%Y'), '%Y%m%d')";
            }
        }
    }



    if ($pagQuery) {
        $query .= " " . $pagQuery;
    }

    $query .= ";";

    return $query;
}
