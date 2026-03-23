<?php
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 p-4">

<h3>Edit User</h3>

<form action="update_user.php" method="POST">

  <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

  <input type="text" name="name" value="<?php echo $user['name']; ?>" class="form-control mb-2" required>

  <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control mb-2" required>

  <input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="form-control mb-2" required>

  <input type="text" name="password" value="<?php echo $user['password']; ?>" class="form-control mb-2" required>

  <select name="role" class="form-control mb-2">
    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
    <option value="counselor" <?php if($user['role']=='counselor') echo 'selected'; ?>>Counselor</option>
  </select>

  <button type="submit" class="btn btn-success">Update</button>

</form>

</body>
</html>