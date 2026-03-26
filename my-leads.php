<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.php");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

// UPDATE STATUS
if(isset($_POST['update_status'])){
    $eid = $_POST['enquiry_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE enquiries SET status='$status' WHERE enquiry_id='$eid'");
    header("Location: my-leads.php");
    exit();
}

// ADD NEW LEAD
if(isset($_POST['add_lead'])){
    $name = $_POST['student_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $course = $_POST['course'];
    $source = $_POST['source'];
    $date = date('Y-m-d');

    // Check duplicate phone
    $check = mysqli_query($conn, "SELECT * FROM enquiries WHERE phone='$phone'");
    if(mysqli_num_rows($check) > 0){
        $error = "Lead with this phone number already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO enquiries 
            (student_name, phone, email, city, course_interested, source, status, assigned_to, enquiry_date) 
            VALUES ('$name','$phone','$email','$city','$course','$source','New','$uid','$date')");
        $success = "Lead added successfully!";
    }
}

$leads = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$uid' ORDER BY enquiry_id DESC");
$total = mysqli_num_rows($leads);
$sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>My Leads</h4>
                <small class="text-muted">Total <?php echo $total; ?> leads assigned to you</small>
            </div>
            <!-- ✅ BUTTON NOW OPENS MODAL -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                <i class="fa-solid fa-plus me-1"></i> Add New Lead
            </button>
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

        <!-- LEADS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-list"></i> Lead List
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if($total > 0){
                        while($row = mysqli_fetch_assoc($leads)){
                            $badgeColor = match($row['status']){
                                'New' => 'primary',
                                'Called' => 'info',
                                'Follow-up' => 'warning',
                                'Converted' => 'success',
                                'CNR' => 'danger',
                                'Closed' => 'secondary',
                                default => 'secondary'
                            };
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $row['student_name']; ?></strong></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><?php echo $row['source']; ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-1 align-items-center">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $row['enquiry_id']; ?>">
                                    <select name="status" class="form-select form-select-sm bg-<?php echo $badgeColor; ?> text-white" style="font-size:12px; width:120px; font-weight:600;">
                                        <?php
                                        $statuses = ['New','Called','Follow-up','CNR','Closed','Converted'];
                                        foreach($statuses as $s){
                                            $sel = ($row['status']==$s) ? 'selected' : '';
                                            echo "<option value='$s' $sel>$s</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-dark">✓</button>
                                </form>
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="counselor-add-call.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-info text-white" title="Add Call">
                                    <i class="fa-solid fa-phone"></i>
                                </a>
                                <a href="followups.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-warning" title="Follow-up">
                                    <i class="fa-solid fa-calendar"></i>
                                </a>
                                <a href="counselor-admission.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-success" title="Admit">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users fa-2x mb-2 d-block"></i>
                                No leads assigned yet
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

<!-- ✅ ADD LEAD MODAL -->
<div class="modal fade" id="addLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header bg-dark text-white" style="border-radius:16px 16px 0 0;">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user-plus me-2"></i> Add New Lead
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Student Name <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="10 digit number" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Course Interested</label>
                            <select name="course" class="form-select">
                                <option value="">-- Select Course --</option>
                                <?php
                                mysqli_data_seek($courses, 0);
                                while($c = mysqli_fetch_assoc($courses)){
                                    echo "<option value='{$c['course_name']}'>{$c['course_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lead Source</label>
                            <select name="source" class="form-select">
                                <option value="">-- Select Source --</option>
                                <?php
                                mysqli_data_seek($sources, 0);
                                while($s = mysqli_fetch_assoc($sources)){
                                    echo "<option value='{$s['source_name']}'>{$s['source_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancel
                    </button>
                    <button type="submit" name="add_lead" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-1"></i> Add Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>