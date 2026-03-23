<?php
include 'db.php';

// Fetch enquiries for dropdown
$enquiries = mysqli_query($conn, "SELECT * FROM enquiries");

// Fetch call records
$calls = mysqli_query($conn, "SELECT * FROM call_records");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Call Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Call Records</h3>

<!-- ADD CALL FORM -->
<form action="add_call.php" method="POST" class="card p-3 mb-4">

  <label>Select Enquiry</label>
  <select name="enquiry_id" class="form-control mb-2">
    <?php while($row = mysqli_fetch_assoc($enquiries)) { ?>
      <option value="<?php echo $row['enquiry_id']; ?>">
        <?php echo $row['student_name']; ?>
      </option>
    <?php } ?>
  </select>

  <input type="datetime-local" name="call_date" class="form-control mb-2" required>

  <select name="call_status" class="form-control mb-2">
    <option value="Not Reachable">Not Reachable</option>
    <option value="Interested">Interested</option>
    <option value="Not Interested">Not Interested</option>
    <option value="Call Later">Call Later</option>
  </select>

  <textarea name="remarks" placeholder="Remarks" class="form-control mb-2"></textarea>

  <button type="submit" class="btn btn-primary">Add Call Record</button>

</form>

<!-- CALL RECORDS TABLE -->
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Enquiry ID</th>
      <th>Date</th>
      <th>Status</th>
      <th>Remarks</th>
    </tr>
  </thead>

  <tbody>
    <?php while($row = mysqli_fetch_assoc($calls)) { ?>
      <tr>
        <td><?php echo $row['call_id']; ?></td>
        <td><?php echo $row['enquiry_id']; ?></td>
        <td><?php echo $row['call_date']; ?></td>
        <td><?php echo $row['call_status']; ?></td>
        <td><?php echo $row['remarks']; ?></td>
      </tr>
    <?php } ?>
  </tbody>

</table>

</body>
</html>