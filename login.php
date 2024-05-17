<?php
session_start();

include "connection.php";

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($conn) {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = validate(strtolower($_POST['username']));
        $password = validate($_POST['password']);

        $sql = "SELECT userid,username,password FROM users WHERE username='$username' AND password='$password'";

        $result = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));
        if (sqlsrv_num_rows($result) == 1) {
            $data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            $_SESSION["userid"] = $data["userid"];
            echo true;
        } else {
            echo false;
        }
        sqlsrv_free_stmt($result);
    }
    sqlsrv_close($conn);
}