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
    if (isset($_POST["filter"])) {
        $sql = "SELECT TOP 3 * FROM products WHERE " . validate($_POST["filter"]) . " LIKE '%" . validate($_POST["searchKey"]) . "%'";
        search($conn, $sql);
    }

    if(isset($_POST["vehicleType"])) {
        $sql = "SELECT * FROM services WHERE type='" . validate($_POST["vehicleType"]) . "'";
        search($conn, $sql);
    }

    if (isset($_POST["vehicleNo"])) {
        $sql = "INSERT INTO bills(vehicleno,customerid,date,presentmeter,nextmeter,paymentmethod,amount) VALUES('" . validate($_POST["vehicleNo"]) . "'," . validate($_POST["customerId"]) . ",'" . validate($_POST["date"]) . "'," . validate($_POST["presentMeter"]) . "," . validate($_POST["nextMeter"]) . ",'" . validate($_POST["payment"]) . "'," . validate($_POST["total"]) . ")";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["billid"])) {
        $sql = "SELECT IDENT_CURRENT('bills') as billid;";
        search($conn, $sql);
    }

    if (isset($_POST["billId"])) {
        $sql = "INSERT INTO billitem(billid,id,type,description,quantity,price) VALUES('" . validate($_POST["billId"]) . "','" . validate($_POST["itemid"]) . "','" . validate($_POST["type"]) . "','" . validate($_POST["des"]) . "','" . validate($_POST["qty"]) . "','" . validate($_POST["price"]) . "')";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["productid"])) {
        $sql = "UPDATE products SET quantity=quantity-". validate($_POST["qty"])." WHERE productid=". validate($_POST["productid"]);
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    sqlsrv_close($conn);
}