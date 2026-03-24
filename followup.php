<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.html");
    exit();
}
include 'db.php';

// ADD FOLLOWUP
if(isset($_POST['add_followup'])){
    $eid = $_POST['enquiry_id'];
    $fdate = $_POST['followup_date'];
    $status = $_POST['followup_status'];
    $remarks = $_POST['remarks'];
    mysqli_query($conn, "INSERT INTO followups (enquiry_id, followup_date, followup_status, remarks) 
        VALUES ('$eid','$fdate','$status','$remarks')");
    $success = "Follow-up added successfully!";
}

// DELETE FOLLOWUP
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM followups WHERE followup_id='$id'");
    header("Location: followup.php");
    exit();
}

$enquiries = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY student_name ASC");
$followups = mysqli_query($conn, "SELECT f.*, e.student_name, e.phone, e.course_interested 
    FROM followups f 
    JOIN enquiries e ON f.enquiry_id = e.enquiry_id 
    ORDER BY f.followup_date ASC");
$total = mysqli_num_rows($followups);
$today = date('Y-m-d');
$today_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups WHERE followup_date='$today'"))['t'];
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups WHERE followup_status='Pending'"))['t'];
$done_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups WHERE followup_status='Done'"))['t'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Follow-Ups</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-calendar-check me-2 text-warning"></i>Follow-Up System</h4>
                <small class="text-muted">Total <?php echo $total; ?> follow-ups scheduled</small>
            </div>
            <span class="badge bg-warning px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('l, d M Y'); ?>
            </span>
        </div>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php } ?>

        <!-- QUICK STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-calendar-day"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $today_count; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Today's Follow-Ups</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-clock"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $pending_count; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Pending</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-circle-check"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $done_count; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Done</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-list"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Follow-Ups</small>
                </div>
            </div>
        </div>

        <!-- ADD FOLLOWUP FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-calendar-plus"></i> Add Follow-Up
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label>Select Lead</label>
                            <select name="enquiry_id" class="form-select" required>
                                <option value="">-- Select Student --</option>
                                <?php
                                mysqli_data_seek($enquiries, 0);
                                while($row = mysqli_fetch_assoc($enquiries)){
                                    echo "<option value='{$row['enquiry_id']}'>{$row['student_name']} - {$row['phone']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Follow-Up Date</label>
                            <input type="date" name="followup_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Status</label>
                            <select name="followup_status" class="form-select">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Pending">Pending</option>
                                <option value="Done">Done</option>
                                <option value="Rescheduled">Rescheduled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_followup" class="btn btn-primary w-100">
                                <i class="fa-solid fa-save"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- FOLLOWUPS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check"></i> All Follow-Ups
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Follow-Up Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    mysqli_data_seek($followups, 0);
                    if($total > 0){
                        while($row = mysqli_fetch_assoc($followups)){
                            $isToday = ($row['followup_date'] == $today);
                            $rowStyle = $isToday ? 'style="background:#fffbeb;"' : '';
                            $badgeColor = match($row['followup_status']){
                                'Scheduled' => 'primary',
                                'Done' => 'success',
                                'Pending' => 'danger',
                                'Rescheduled' => 'warning',
                                default => 'secondary'
                            };
                    ?>
                        <tr <?php echo $rowStyle; ?>>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $row['student_name']; ?></strong></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td>
                                <?php echo $row['followup_date']; ?>
                                <?php if($isToday){ ?>
                                    <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Today</span>
                                <?php } ?>
                            </td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['followup_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                            <td>
                                <a href="followup.php?delete=<?php echo $row['followup_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this follow-up?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-calendar-xmark fa-2x mb-2 d-block"></i>
                                No follow-ups scheduled yet
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