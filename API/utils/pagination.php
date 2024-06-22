<?php
    include "./filtering.php";
    function paginar ($sqlReq, $getArray, $table){
        //OBTENER LA CANTIDAD TOTAL DE REGISTROS

        $numRegistros =  mysqli_num_rows($sqlReq);

        $registros = 10;

        $page = $getArray["page"];

        if(is_numeric($page)){
            $start = (($page -1) * $registros);
        }
        else{
            $start = 0;
        }

        //VOLVER A ARMAR LA QUERY

        $query = filtrarBusqueda($getArray, $table, "LIMIT $start,$registros");


        $data["pagination"] = [
            "perPage"=>$registros,
            "total"=>$numRegistros,
            "start"=> $start + 1,
            "totalPages" => ceil($numRegistros / $registros)
        ];

        $data["query"] = $query;

        return $data;


    }