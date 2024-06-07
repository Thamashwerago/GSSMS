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
        $sql = "SELECT TOP 100 * FROM products";
        search($conn, $sql);
    }

    if (isset($_POST["id"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE productid LIKE '%" . validate(strtolower($_POST["id"])) . "%'";
        search($conn, $sql);
    } elseif (isset($_POST["name"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE productname LIKE '%" . validate(strtolower($_POST["name"])) . "%'";
        search($conn, $sql);
    } elseif (isset($_POST["category"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE category LIKE '%" . validate(strtolower($_POST["category"])) . "%'";
        search($conn, $sql);
    } elseif (isset($_POST["brand"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE brand LIKE '%" . validate(strtolower($_POST["brand"])) . "%'";
        search($conn, $sql);
    } elseif (isset($_POST["partNo"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE partno LIKE '%" . validate(strtolower($_POST["partNo"])) . "%'";
        search($conn, $sql);
    } elseif (isset($_POST["meNo"])) {
        $sql = "SELECT TOP 100 * FROM products WHERE meno LIKE '%" . validate(strtolower($_POST["meNo"])) . "%'";
        search($conn, $sql);
    }

    if (isset($_POST["selectedid"])) {
        $sql = "SELECT * FROM products WHERE productid='" . validate(strtolower($_POST["selectedid"])) . "'";
        search($conn, $sql);
    }

    if (isset($_POST["editid"])) {
        $sql = "UPDATE products SET productname='" . validate($_POST["name"]) . "',category='" . validate($_POST["category"]) . "',brand='" . validate($_POST["brand"]) . "',partno='" . validate($_POST["partno"]) . "',meno='" . validate($_POST["meno"]) . "',price=" . validate($_POST["price"]) . ",quantity=" . validate($_POST["quantity"]) . " WHERE productid='" . validate(strtolower($_POST["editid"])) . "'";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["productname"])) {
        $sql = "INSERT INTO products(productname,category,brand,partno,meno,price,quantity) VALUES('" . validate($_POST["productname"]) . "','" . validate($_POST["category"]) . "','" . validate($_POST["brand"]) . "','" . validate($_POST["partno"]) . "','" . validate($_POST["meno"]) . "'," . validate($_POST["price"]) . "," . validate($_POST["quantity"]) . ")";
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["deleteid"])) {
        $sql = "DELETE FROM products WHERE productid=". validate($_POST["deleteid"]);
        if (sqlsrv_query($conn, $sql)) {
            echo true;
        } else {
            echo false;
        }
    }

    if (isset($_POST["low"])) {
        $sql = "SELECT * FROM products WHERE quantity<10";
        search($conn, $sql);
    }


    sqlsrv_close($conn);
}