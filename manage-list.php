<?php
include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM sources");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Sources</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Manage Lead Sources</h3>

<form action="add_source.php" method="POST" class="mb-3">

  <input type="text" name="source_name" placeholder="Source Name" class="form-control mb-2" required>

  <select name="status" class="form-control mb-2">
    <option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
  </select>

  <button type="submit" class="btn btn-primary">Add New Source</button>

</form>

<table class="table table-bordered">
  <tr>
    <th>ID</th>
    <th>Source Name</th>
    <th>Status</th>
    <th>Action</th>
  </tr>

  <?php while($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?php echo $row['source_id']; ?></td>
    <td><?php echo $row['source_name']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
      <a href="delete_source.php?id=<?php echo $row['source_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
    </td>
  </tr>
  <?php } ?>

</table>

</body>
</html>