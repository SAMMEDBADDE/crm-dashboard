<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM enquiries WHERE enquiry_id=$id");

header("Location: manage-leads.php");
?>