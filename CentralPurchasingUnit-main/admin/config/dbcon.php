<?php
$host = "localhost:3306";
$username = "root";
$password = "";
$database = "cpu_db";

$con = mysqli_connect($host, $username, $password, $database);

if(!$con){
    header("Location: ../errors/dberror.php");
    die();
}
?>