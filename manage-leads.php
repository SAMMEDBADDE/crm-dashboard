<?php
include 'db.php';

// Fetch leads


session_start();

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

if($role == 'admin'){
    $result = mysqli_query($conn, "SELECT * FROM enquiries");
}else{
    $result = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$user_id'");
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Manage Leads</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Manage Leads</h3>

<form action="add_lead.php" method="POST" class="mb-3">

  <input type="text" name="student_name" placeholder="Student Name" class="form-control mb-2" required>

  <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" required>

  <input type="email" name="email" placeholder="Email" class="form-control mb-2">

  <input type="text" name="city" placeholder="City" class="form-control mb-2">

  <input type="text" name="course" placeholder="Course Interested" class="form-control mb-2">

  <!-- ✅ DYNAMIC SOURCE -->
  <select name="source" class="form-control mb-2">
    <?php
    $sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
    while($s = mysqli_fetch_assoc($sources)) {
    ?>
      <option value="<?php echo $s['source_name']; ?>">
        <?php echo $s['source_name']; ?>
      </option>
    <?php } ?>
  </select>

  <select name="status" class="form-control mb-2">
    <option value="New">New</option>
    <option value="Called">Called</option>
    <option value="Follow-up">Follow-up</option>
    <option value="Converted">Converted</option>
  </select>

  <button type="submit" class="btn btn-success">Add Lead</button>

</form>

<hr>

<table class="table table-bordered">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>City</th>
    <th>Course</th>
    <th>Source</th>
    <th>Status</th>
    <th>Action</th>
  </tr>

  <?php while($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?php echo $row['enquiry_id']; ?></td>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['city']; ?></td>
    <td><?php echo $row['course_interested']; ?></td>
    <td><?php echo $row['source']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
      <a href="edit_lead.php?id=<?php echo $row['enquiry_id']; ?>" class="btn btn-warning btn-sm">Edit</a>

<a href="delete_lead.php?id=<?php echo $row['enquiry_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this lead?')">Delete</a>
    </td>
  </tr>
  <?php } ?>

</table>

</body>
</html>