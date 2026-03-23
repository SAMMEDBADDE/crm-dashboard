<?php
include 'db.php';

// Fetch enquiries
$enquiries = mysqli_query($conn, "SELECT * FROM enquiries");

// Fetch followups
$followups = mysqli_query($conn, "SELECT * FROM followups");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Follow-Ups</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Follow-Up System</h3>

<!-- ADD FOLLOWUP FORM -->
<form action="add_followup.php" method="POST" class="card p-3 mb-4">

  <label>Select Lead</label>
  <select name="enquiry_id" class="form-control mb-2">
    <?php while($row = mysqli_fetch_assoc($enquiries)) { ?>
      <option value="<?php echo $row['enquiry_id']; ?>">
        <?php echo $row['student_name']; ?>
      </option>
    <?php } ?>
  </select>

  <input type="datetime-local" name="followup_date" class="form-control mb-2" required>

  <select name="followup_status" class="form-control mb-2">
    <option value="Pending">Pending</option>
    <option value="Done">Done</option>
  </select>

  <textarea name="remarks" placeholder="Remarks" class="form-control mb-2"></textarea>

  <button type="submit" class="btn btn-success">Add Follow-Up</button>

</form>

<!-- FOLLOWUP TABLE -->
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
    <?php while($row = mysqli_fetch_assoc($followups)) { ?>
      <tr>
        <td><?php echo $row['followup_id']; ?></td>
        <td><?php echo $row['enquiry_id']; ?></td>
        <td><?php echo $row['followup_date']; ?></td>
        <td><?php echo $row['followup_status']; ?></td>
        <td><?php echo $row['remarks']; ?></td>
      </tr>
    <?php } ?>
  </tbody>

</table>

</body>
</html>