<?php
include 'db.php';

// Fetch enquiries
$enquiries = mysqli_query($conn, "SELECT * FROM enquiries");

// Fetch admissions
$admissions = mysqli_query($conn, "SELECT * FROM admissions");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admissions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Admission System</h3>

<!-- ADD ADMISSION FORM -->
<form action="add_admission.php" method="POST" class="card p-3 mb-4">

  <label>Select Lead</label>
  <select name="enquiry_id" class="form-control mb-2">
    <?php while($row = mysqli_fetch_assoc($enquiries)) { ?>
      <option value="<?php echo $row['enquiry_id']; ?>">
        <?php echo $row['student_name']; ?>
      </option>
    <?php } ?>
  </select>

  <input type="number" name="fees" placeholder="Total Fees" class="form-control mb-2" required>

  <input type="number" name="fees_paid" placeholder="Fees Paid" class="form-control mb-2" required>

  <select name="payment_status" class="form-control mb-2">
    <option value="Pending">Pending</option>
    <option value="Partial">Partial</option>
    <option value="Paid">Paid</option>
  </select>

  <select name="payment_type" class="form-control mb-2">
    <option value="Cash">Cash</option>
    <option value="Online">Online</option>
    <option value="UPI">UPI</option>
  </select>

  <input type="number" name="installment" placeholder="Installments" class="form-control mb-2">

  <button type="submit" class="btn btn-success">Convert to Admission</button>

</form>

<!-- ADMISSION TABLE -->
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Enquiry ID</th>
      <th>Fees</th>
      <th>Paid</th>
      <th>Status</th>
      <th>Payment Type</th>
      <th>Installments</th>
    </tr>
  </thead>

  <tbody>
    <?php while($row = mysqli_fetch_assoc($admissions)) { ?>
      <tr>
        <td><?php echo $row['admission_id']; ?></td>
        <td><?php echo $row['enquiry_id']; ?></td>
        <td><?php echo $row['fees']; ?></td>
        <td><?php echo $row['fees_paid']; ?></td>
        <td><?php echo $row['payment_status']; ?></td>
        <td><?php echo $row['payment_type']; ?></td>
        <td><?php echo $row['installment']; ?></td>
      </tr>
    <?php } ?>
  </tbody>

</table>

</body>
</html>