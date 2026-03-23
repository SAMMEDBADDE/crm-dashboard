<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "crm_system";

$conn = mysqli_connect($host,$user,$password,$database,3307);

if(!$conn){
    die("Database connection failed");
}

?>