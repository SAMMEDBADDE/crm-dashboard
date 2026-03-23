<?php
include 'db.php';

$name = $_POST['student_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$city = $_POST['city'];
$course = $_POST['course'];
$source = $_POST['source'];
$status = $_POST['status'];

mysqli_query($conn, "INSERT INTO enquiries 
(student_name, phone, email, city, course_interested, source, status)
VALUES ('$name','$phone','$email','$city','$course','$source','$status')");

header("Location: manage-leads.php");
?>