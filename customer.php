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
    if (isset($_POST["load"])) {
        $sql = "SELECT TOP 100 * FROM customers";
        search($conn, $sql);
    }

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

    if (isset($_POST["filter"])) {
        $sql = "SELECT TOP 100 * FROM customers WHERE " . validate($_POST["selectedItem"]) . " LIKE '%" . validate($_POST["searchKey"]) . "%'";
        search($conn, $sql);
    }

    if (isset($_POST["edit"])) {
        $sql = "UPDATE customers SET name='".validate($_POST["name"])."',phoneno='". validate($_POST["no"]) ."' WHERE customerid='" . validate($_POST["id"]) . "'";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["delete"])) {
        $sql = "DELETE FROM customers WHERE customerid=" . validate($_POST["id"]);
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    sqlsrv_close($conn);
}