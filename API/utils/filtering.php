<?php

function filtrarBusqueda($getArray, $table){
    //CONSULTA CON FILTROS
    $query = "SELECT * FROM ".$table;

    $filtered_get = array_filter($getArray); // removes empty values from $_GET

    if (count($filtered_get)) { // not empty
        $query .= " WHERE";

        $keynames = array_keys($filtered_get); // make array of key names from $filtered_get

        foreach($filtered_get as $key => $value)
        {
            $query .= " $key LIKE '$value%'";  // $filtered_get keyname = $filtered_get['keyname'] value
            if (count($filtered_get) > 1 && (count($filtered_get)-1 > $key)) { // more than one search filter, and not the last
                $query .= " AND";
            }
        }
    }
    $query .= ";";

    return $query;
}