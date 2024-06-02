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
        $sql = "SELECT TOP 100 bills.billid,bills.vehicleno,vehicles.vehicletype,FORMAT(bills.date,'yyyy-MM-dd') AS date,customers.phoneno,bills.presentmeter,bills.nextmeter,bills.paymentmethod,bills.amount FROM bills,vehicles,customers WHERE bills.vehicleno=vehicles.vehicleno AND bills.customerid=customers.customerid";
        search($conn, $sql);
    }

    if (isset($_POST["filter"])) {
        $sql = "SELECT TOP 100 bills.billid,bills.vehicleno,vehicles.vehicletype,FORMAT(bills.date,'yyyy-MM-dd') AS date,customers.phoneno,bills.presentmeter,bills.nextmeter,bills.paymentmethod,bills.amount FROM bills,vehicles,customers WHERE bills.vehicleno=vehicles.vehicleno AND bills.customerid=customers.customerid AND " . validate($_POST["selectedItem"]) . " LIKE '%" . validate($_POST["searchKey"]) . "%'";
        search($conn, $sql);
    }

    sqlsrv_close($conn);
}