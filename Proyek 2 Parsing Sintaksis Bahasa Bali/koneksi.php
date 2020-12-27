<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "u5030462_tbo";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

if (!$conn){
    die("Connection failed : ".mysqli_connect_error());
}
?>