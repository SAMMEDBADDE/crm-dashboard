<?php
include 'db.php';

$enquiry_id = $_POST['enquiry_id'];
$date = $_POST['followup_date'];
$status = $_POST['followup_status'];
$remarks = $_POST['remarks'];

mysqli_query($conn, "INSERT INTO followups 
(enquiry_id, followup_date, followup_status, remarks)
VALUES ('$enquiry_id','$date','$status','$remarks')");

header("Location: followup.php");
?>
