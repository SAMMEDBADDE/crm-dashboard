<?php
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "INSERT INTO users (name, email, phone, password, role, status) 
VALUES ('$name', '$email', '$phone', '$password', '$role', 'active')";

if(mysqli_query($conn, $sql)){
    echo "User Added Successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>