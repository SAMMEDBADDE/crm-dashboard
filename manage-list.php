<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.html");
    exit();
}
include 'db.php';

// ADD SOURCE
if(isset($_POST['add_source'])){
    $name = $_POST['source_name'];
    $status = $_POST['status'];
    $check = mysqli_query($conn, "SELECT * FROM sources WHERE source_name='$name'");
    if(mysqli_num_rows($check) > 0){
        $error = "Source already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO sources (source_name, status) VALUES ('$name','$status')");
        $success = "Source added successfully!";
    }
}

// DELETE SOURCE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM sources WHERE source_id='$id'");
    header("Location: manage-list.php");
    exit();
}

// TOGGLE STATUS
if(isset($_GET['toggle'])){
    $id = $_GET['toggle'];
    $current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM sources WHERE source_id='$id'"))['status'];
    $new = ($current == 'Active') ? 'Inactive' : 'Active';
    mysqli_query($conn, "UPDATE sources SET status='$new' WHERE source_id='$id'");
    header("Location: manage-list.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM sources ORDER BY source_id DESC");
$total = mysqli_num_rows($result);
$active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM sources WHERE status='Active'"))['t'];
$inactive = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM sources WHERE status='Inactive'"))['t'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage List</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-list me-2 text-primary"></i>Manage List</h4>
                <small class="text-muted">Lead sources — Meta, Google, Instagram, Walk-in etc.</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-success px-3 py-2"><?php echo $active; ?> Active</span>
                <span class="badge bg-secondary px-3 py-2"><?php echo $inactive; ?> Inactive</span>
            </div>
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

        <div class="row g-4">

            <!-- ADD SOURCE FORM -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Add New Source
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Source Name</label>
                                <input type="text" name="source_name" class="form-control" placeholder="e.g. Instagram, Google" required>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" name="add_source" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-1"></i> Add Source
                            </button>
                        </form>

                        <!-- QUICK STATS -->
                        <hr>
                        <div class="text-center">
                            <small class="text-muted d-block mb-2">Total Sources</small>
                            <h2 class="fw-bold text-primary"><?php echo $total; ?></h2>
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-6">
                                <div class="p-2 bg-success text-white rounded">
                                    <small>Active</small>
                                    <h5 class="mb-0"><?php echo $active; ?></h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-secondary text-white rounded">
                                    <small>Inactive</small>
                                    <h5 class="mb-0"><?php echo $inactive; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SOURCES TABLE -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-table-list"></i> All Sources
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Source Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            mysqli_data_seek($result, 0);
                            if($total > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    $statusBadge = $row['status'] == 'Active' ? 'success' : 'secondary';
                                    $sourceIcon = match(strtolower($row['source_name'])){
                                        'instagram' => 'fa-instagram',
                                        'google' => 'fa-google',
                                        'facebook', 'meta' => 'fa-facebook',
                                        'walk-in', 'walkin' => 'fa-person-walking',
                                        'whatsapp' => 'fa-whatsapp',
                                        default => 'fa-globe'
                                    };
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <i class="fa-brands <?php echo $sourceIcon; ?> me-2 text-primary"></i>
                                        <strong><?php echo $row['source_name']; ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $statusBadge; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td style="white-space:nowrap;">
                                        <a href="manage-list.php?toggle=<?php echo $row['source_id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fa-solid fa-toggle-on"></i>
                                        </a>
                                        <a href="manage-list.php?delete=<?php echo $row['source_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this source?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }} else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fa-solid fa-list fa-2x mb-2 d-block"></i>
                                        No sources added yet
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
</div>
</div>