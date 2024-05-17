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
    if (isset($_POST["category"])) {
        $sql = "SELECT TOP 3 * FROM " . validate($_POST["category"]) . " WHERE " . validate($_POST["filter"]) . " LIKE '%" . validate($_POST["searchKey"]) . "%'";
        search($conn, $sql);
    }

    if(isset($_POST["id"])) {
        $sql = "SELECT * FROM products WHERE productid='" . validate(strtolower($_POST["id"])) . "'";
        search($conn, $sql);
    }

    sqlsrv_close($conn);
}