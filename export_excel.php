<?php
include 'db.php';

// Excel headers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=crm_report.xls");

// Column headings
echo "ID\tName\tPhone\tEmail\tCity\tCourse\tSource\tStatus\n";

// Fetch data
$result = mysqli_query($conn, "SELECT * FROM enquiries");

while($row = mysqli_fetch_assoc($result)) {
    echo $row['enquiry_id'] . "\t" .
         $row['student_name'] . "\t" .
         $row['phone'] . "\t" .
         $row['email'] . "\t" .
         $row['city'] . "\t" .
         $row['course_interested'] . "\t" .
         $row['source'] . "\t" .
         $row['status'] . "\n";
}
?>