<?php
include 'db.php';

// Default query
$query = "SELECT * FROM enquiries";

// Filter apply
if(isset($_GET['status'])) {
    $status = $_GET['status'];
    if($status != "") {
        $query .= " WHERE status='$status'";
    }
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Reports</h3>

<!-- FILTER -->
<form method="GET" class="mb-3">

  <select name="status" class="form-control mb-2">
    <option value="">All Status</option>
    <option value="New">New</option>
    <option value="Called">Called</option>
    <option value="Follow-up">Follow-up</option>
    <option value="Converted">Converted</option>
  </select>

  <button type="submit" class="btn btn-primary">Filter</button>

</form>
<a href="export_excel.php" class="btn btn-success mb-3">Export to Excel</a>

<!-- TABLE -->
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Phone</th>
      <th>City</th>
      <th>Course</th>
      <th>Source</th>
      <th>Status</th>
    </tr>
  </thead>

  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['enquiry_id']; ?></td>
        <td><?php echo $row['student_name']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['city']; ?></td>
        <td><?php echo $row['course_interested']; ?></td>
        <td><?php echo $row['source']; ?></td>
        <td><?php echo $row['status']; ?></td>
      </tr>
    <?php } ?>
  </tbody>

</table>

</body>
</html>