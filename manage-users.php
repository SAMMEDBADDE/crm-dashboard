<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

// Add user directly
if(isset($_POST['add_user'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $error = "Email already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO users (name, email, phone, password, role, status) 
            VALUES ('$name','$email','$phone','$password','$role','active')");
        $success = "User added successfully!";
    }
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$total_users = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-user-gear me-2 text-primary"></i>Manage Users</h4>
                <small class="text-muted">Total <?php echo $total_users; ?> users in system</small>
            </div>
            <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-users me-1"></i> User Management
            </span>
        </div>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php } ?>
        <?php if(isset($error)){ ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-xmark"></i> <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- ADD USER FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Add New User
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Rahul" required>
                        </div>
                        <div class="col-md-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="col-md-2">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="10 digit number" required>
                        </div>
                        <div class="col-md-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="col-md-2">
                            <label>Role</label>
                            <select name="role" class="form-select">
                                <option value="counselor">Counselor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_user" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-list"></i> User List
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    mysqli_data_seek($result, 0);
                    if($total_users > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $roleBadge = $row['role'] == 'admin' ? 'danger' : 'info';
                            $statusBadge = $row['status'] == 'active' ? 'success' : 'secondary';
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px; height:32px; background:#1d4ed8; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px;">
                                        <?php echo strtoupper(substr($row['name'],0,1)); ?>
                                    </div>
                                    <strong><?php echo $row['name']; ?></strong>
                                </div>
                            </td>
                            <td><?php echo $row['email']; ?></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><span class="badge bg-<?php echo $roleBadge; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                            <td><span class="badge bg-<?php echo $statusBadge; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users fa-2x mb-2 d-block"></i>
                                No users found
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>
</body>
</html>