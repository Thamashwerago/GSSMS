<?php

include "connection.php";

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function search($conn, $sql)
{
    $data = array();
    $result = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        array_push($data, $row);
    }
    echo json_encode($data);
    sqlsrv_free_stmt($result);
}

if ($conn) {
    if (isset($_POST["vehicleNo"])) {
        $sql = "INSERT INTO vehicles(vehicleno,vehicletype,brand) VALUES('" . validate($_POST["vehicleNo"]) . "','" . validate($_POST["vehicleType"]) . "','" . validate($_POST["vehicleBrand"]) . "')";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["vehicleno"])) {
        $sql = "SELECT TOP 3 * FROM vehicles WHERE vehicleno LIKE '%" . validate($_POST["vehicleno"]) . "%'";
        search($conn, $sql);
    }

    sqlsrv_close($conn);
}