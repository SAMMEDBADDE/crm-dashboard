<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$name = $_POST['student_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$city = $_POST['city'];
$course = $_POST['course'];
$source = $_POST['source'];
$status = $_POST['status'];
$uid = $_SESSION['user_id'];
$date = date('Y-m-d');

mysqli_query($conn, "INSERT INTO enquiries 
(student_name, phone, email, city, course_interested, source, status, assigned_to, enquiry_date)
VALUES ('$name','$phone','$email','$city','$course','$source','$status','$uid','$date')");

// ✅ Redirect based on role
if($_SESSION['role'] == 'admin'){
    header("Location: manage-leads.php");
} else {
    header("Location: my-leads.php");
}
exit();
?>