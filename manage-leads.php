<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

// ADD LEAD
if(isset($_POST['add_lead'])){
    $name = $_POST['student_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $course = $_POST['course'];
    $source = $_POST['source'];
    $status = $_POST['status'];
    $assigned = $_POST['assigned_to'];
    $date = date('Y-m-d');
    mysqli_query($conn, "INSERT INTO enquiries (student_name, phone, email, city, course_interested, source, status, assigned_to, enquiry_date) 
        VALUES ('$name','$phone','$email','$city','$course','$source','$status','$assigned','$date')");
    $success = "Lead added successfully!";
}

// ASSIGN LEAD TO COUNSELOR
if(isset($_POST['assign_lead'])){
    $eid = $_POST['enquiry_id'];
    $cid = $_POST['counselor_id'];
    mysqli_query($conn, "UPDATE enquiries SET assigned_to='$cid' WHERE enquiry_id='$eid'");
    $success = "Lead assigned successfully!";
}

// DELETE LEAD
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM enquiries WHERE enquiry_id='$id'");
    header("Location: manage-leads.php");
    exit();
}

$result = mysqli_query($conn, "SELECT e.*, u.name as counselor_name FROM enquiries e LEFT JOIN users u ON e.assigned_to = u.id ORDER BY e.enquiry_id DESC");
$total = mysqli_num_rows($result);
$counselors = mysqli_query($conn, "SELECT id, name FROM users WHERE role='counselor' AND status='active'");
$sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Leads</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Manage Leads</h4>
                <small class="text-muted">Total <?php echo $total; ?> leads in system</small>
            </div>
            <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-list me-1"></i> Lead Management
            </span>
        </div>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php } ?>

        <!-- ADD LEAD FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Add New Lead
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label>Student Name</label>
                            <input type="text" name="student_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-2">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="col-md-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-1">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="col-md-2">
                            <label>Course</label>
                            <input type="text" name="course" class="form-control" placeholder="Course">
                        </div>
                        <div class="col-md-1">
                            <label>Source</label>
                            <select name="source" class="form-select">
                                <?php
                                mysqli_data_seek($sources, 0);
                                while($s = mysqli_fetch_assoc($sources)){
                                    echo "<option value='{$s['source_name']}'>{$s['source_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="New">New</option>
                                <option value="Called">Called</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Converted">Converted</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Assign To</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">None</option>
                                <?php
                                mysqli_data_seek($counselors, 0);
                                while($c = mysqli_fetch_assoc($counselors)){
                                    echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" name="add_lead" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- LEADS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-table-list"></i> All Leads
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Assign To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    mysqli_data_seek($result, 0);
                    if($total > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $badgeColor = match($row['status']){
                                'New' => 'primary',
                                'Called' => 'info',
                                'Follow-up' => 'warning',
                                'Converted' => 'success',
                                'CNR' => 'danger',
                                default => 'secondary'
                            };
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $row['student_name']; ?></strong></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><?php echo $row['source']; ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['status']; ?></span></td>
                            <td>
                                <!-- ✅ ASSIGN COUNSELOR DROPDOWN - THIS WAS MISSING! -->
                                <form method="POST" class="d-flex gap-1">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $row['enquiry_id']; ?>">
                                    <select name="counselor_id" class="form-select form-select-sm" style="font-size:12px; width:120px;">
                                        <option value="">Unassigned</option>
                                        <?php
                                        mysqli_data_seek($counselors, 0);
                                        while($c = mysqli_fetch_assoc($counselors)){
                                            $sel = ($row['assigned_to'] == $c['id']) ? 'selected' : '';
                                            echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="assign_lead" class="btn btn-sm btn-success">✓</button>
                                </form>
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="edit_lead.php?id=<?php echo $row['enquiry_id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="manage-leads.php?delete=<?php echo $row['enquiry_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this lead?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users fa-2x mb-2 d-block"></i>
                                No leads found
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