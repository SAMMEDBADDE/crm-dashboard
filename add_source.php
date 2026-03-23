<?php
include 'db.php';

$name = $_POST['source_name'];
$status = $_POST['status'];

// Check duplicate
$check = mysqli_query($conn, "SELECT * FROM sources WHERE source_name='$name'");

if(mysqli_num_rows($check) == 0){
    mysqli_query($conn, "INSERT INTO sources (source_name, status)
    VALUES ('$name','$status')");
}

header("Location: manage-list.php");
?>