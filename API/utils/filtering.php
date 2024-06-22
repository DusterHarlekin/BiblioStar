<?php

//error_reporting(0);
function filtrarBusqueda($getArray, $table, $pagQuery = null){
    
    //CONSULTA CON FILTROS
    $query = "SELECT * FROM ".$table;

    $filtered_get = array_filter($getArray); // removes empty values from $_GET

    
    unset($filtered_get["page"]);

    if (count($filtered_get)) { // not empty
        $query .= " WHERE";

        foreach($filtered_get as $key => $value)
        {
            $query .= " $key LIKE '$value%'";  // $filtered_get keyname = $filtered_get['keyname'] value
            if (count($filtered_get) > 1 && (count($filtered_get)-1 > array_search($key,array_keys($filtered_get)))) { // more than one search filter, and not the last
                $query .= " AND";
            }
        }
       
    }

    if($pagQuery){
        $query .= " ".$pagQuery;
    }
    
    $query .= ";";
  
    return $query;
}