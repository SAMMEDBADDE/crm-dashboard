<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$role = $_POST['role'];

mysqli_query($conn, "UPDATE users SET 
name='$name', 
email='$email', 
phone='$phone', 
password='$password', 
role='$role' 
WHERE id=$id");

header("Location: manage-users.php");
?>