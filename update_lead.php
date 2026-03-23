<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['student_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$city = $_POST['city'];
$course = $_POST['course'];
$source = $_POST['source'];
$status = $_POST['status'];

mysqli_query($conn, "UPDATE enquiries SET 
student_name='$name',
phone='$phone',
email='$email',
city='$city',
course_interested='$course',
source='$source',
status='$status'
WHERE enquiry_id=$id");

header("Location: manage-leads.php");
?>