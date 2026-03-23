<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM sources WHERE source_id=$id");

header("Location: manage-list.php");
?>