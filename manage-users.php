<?php
include 'db.php';

// Fetch users
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .container-box { max-width: 900px; margin: auto; }
  </style>
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<div class="container-box">

  <h3 class="mb-3">Manage Users</h3>

  <!-- ADD USER FORM -->
  <form action="add_user.php" method="POST" class="card p-3 shadow-sm">

    <input type="text" name="name" placeholder="Name" class="form-control mb-2" required>
    <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
    <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" required>
    <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>

    <select name="role" class="form-control mb-2">
      <option value="admin">Admin</option>
      <option value="counselor">Counselor</option>
    </select>

    <button type="submit" class="btn btn-primary">Add User</button>

  </form>

  <!-- USERS TABLE -->
  <div class="card mt-4 p-3 shadow-sm">

    <h5>User List</h5>

    <table class="table table-bordered mt-3">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Role</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
              <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>

    </table>

  </div>

</div>

</body>
</html>