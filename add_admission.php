<?php
include 'db.php';

$enquiry_id = $_POST['enquiry_id'];
$fees = $_POST['fees'];
$fees_paid = $_POST['fees_paid'];
$status = $_POST['payment_status'];
$type = $_POST['payment_type'];
$installment = $_POST['installment'];

mysqli_query($conn, "INSERT INTO admissions 
(enquiry_id, fees, fees_paid, payment_status, payment_type, installment)
VALUES ('$enquiry_id','$fees','$fees_paid','$status','$type','$installment')");

header("Location: admission.php");
?>