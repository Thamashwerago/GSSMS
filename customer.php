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
    if (isset($_POST["phoneNo"])) {
        $sql = "INSERT INTO customers(phoneno,name) VALUES('" . validate($_POST["phoneNo"]) . "','" . validate($_POST["name"]) . "')";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["phoneno"])) {
        $sql = "SELECT TOP 3 * FROM customers WHERE phoneno LIKE '%" . validate($_POST["phoneno"]) . "%'";
        search($conn, $sql);
    }

    if (isset($_POST["customerid"])) {
        $sql = "SELECT IDENT_CURRENT('customers') as customerid;";
        search($conn, $sql);
    }

    sqlsrv_close($conn);
}