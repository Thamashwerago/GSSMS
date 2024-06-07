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
    if (isset($_POST["month"])) {
        $sql = "SELECT FORMAT(date,'yyyy-MM-dd') AS date FROM bills WHERE date LIKE '%" . validate($_POST["month"]) . "%'";
        search($conn, $sql);
    }

    sqlsrv_close($conn);
}