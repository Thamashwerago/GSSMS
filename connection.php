<?php

$serverName = "LAPTOP-FUQOP6H0";
$database = "GSSMS";
$uid = "";
$password = "";

$connection = [
"Database" => $database,
"Uid" => $uid,
"PWD" => $password
];

$conn = sqlsrv_connect($serverName,$connection);

if(!$conn){
    die(print_r(sqlsrv_errors(),true));
}