<?php
include 'db.php';

$enquiry_id = $_POST['enquiry_id'];
$date = $_POST['call_date'];
$status = $_POST['call_status'];
$remarks = $_POST['remarks'];

mysqli_query($conn, "INSERT INTO call_records 
(enquiry_id, call_date, call_status, remarks)
VALUES ('$enquiry_id','$date','$status','$remarks')");

header("Location: call-records.php");
?>