<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);

// UPDATE USER
if(isset($_POST['update_user'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE users SET name='$name', email='$email', phone='$phone', password='$password', role='$role', status='$status' WHERE id='$id'");
    header("Location: manage-users.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'sidebar.php'; ?>

    <div class="col-md-10 p-4">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-user-pen me-2 text-warning"></i>Edit User</h4>
                <small class="text-muted">Update details for — <strong><?php echo $user['name']; ?></strong></small>
            </div>
            <a href="manage-users.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-gear"></i> Update User Details
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo $user['name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo $user['email']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="<?php echo $user['phone']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" name="password" class="form-control"
                                    value="<?php echo $user['password']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                                    <option value="counselor" <?php if($user['role']=='counselor') echo 'selected'; ?>>Counselor</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?php if($user['status']=='active') echo 'selected'; ?>>Active</option>
                                    <option value="inactive" <?php if($user['status']=='inactive') echo 'selected'; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="update_user" class="btn btn-success w-100">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Update User
                                </button>
                                <a href="manage-users.php" class="btn btn-secondary w-100">
                                    <i class="fa-solid fa-xmark me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
</body>
</html>