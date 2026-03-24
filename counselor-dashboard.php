<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.php");
    exit();
}
include 'db.php';

$uid = $_SESSION['user_id'];
$today = date('Y-m-d');

$leads_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enquiries WHERE assigned_to='$uid'"))['total'];
$follow_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM followups f JOIN enquiries e ON f.enquiry_id=e.enquiry_id WHERE f.followup_date='$today' AND e.assigned_to='$uid'"))['total'];
$calls_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM call_records c JOIN enquiries e ON c.enquiry_id=e.enquiry_id WHERE e.assigned_to='$uid'"))['total'];
$admission_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM admissions a JOIN enquiries e ON a.enquiry_id=e.enquiry_id WHERE e.assigned_to='$uid'"))['total'];

$todayList = mysqli_query($conn, "SELECT e.student_name, e.phone, e.course_interested, f.followup_date, f.remarks, f.followup_status
    FROM followups f JOIN enquiries e ON f.enquiry_id=e.enquiry_id
    WHERE f.followup_date='$today' AND e.assigned_to='$uid'");

$recentLeads = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$uid' ORDER BY enquiry_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Counselor Dashboard</title>
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

        <!-- TOP WELCOME BAR -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0">Welcome back, <?php echo $_SESSION['name']; ?> 👋</h4>
                <small class="text-muted">Here's your summary for today — <?php echo date('l, d M Y'); ?></small>
            </div>
            <div class="text-end">
                <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                    <i class="fa-solid fa-circle-user me-1"></i> Counselor
                </span>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">My Leads</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $leads_count; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.3;"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <a href="my-leads.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View All →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Today's Follow-Ups</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $follow_count; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.3;"><i class="fa-solid fa-calendar-check"></i></div>
                    </div>
                    <a href="followups.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Calls</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $calls_count; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.3;"><i class="fa-solid fa-phone"></i></div>
                    </div>
                    <a href="counselor-add-call.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Admissions Done</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $admission_count; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.3;"><i class="fa-solid fa-graduation-cap"></i></div>
                    </div>
                    <a href="counselor-admission.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
        </div>

        <!-- BOTTOM SECTION -->
        <div class="row g-3">

            <!-- TODAY'S FOLLOWUPS TABLE -->
            <div class="col-md-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-calendar-day"></i> Today's Follow-Ups
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(mysqli_num_rows($todayList) > 0){
                                while($row = mysqli_fetch_assoc($todayList)){ ?>
                                <tr>
                                    <td><strong><?php echo $row['student_name']; ?></strong></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['course_interested']; ?></td>
                                    <td><?php echo $row['remarks']; ?></td>
                                </tr>
                            <?php }} else { ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-calendar-check fa-2x mb-2 d-block text-success"></i>
                                    No follow-ups for today 🎉
                                </td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RECENT LEADS -->
            <div class="col-md-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i> Recent Leads
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(mysqli_num_rows($recentLeads) > 0){
                                while($row = mysqli_fetch_assoc($recentLeads)){
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
                                    <td><strong><?php echo $row['student_name']; ?></strong></td>
                                    <td><?php echo $row['course_interested']; ?></td>
                                    <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['status']; ?></span></td>
                                </tr>
                            <?php }} else { ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">No leads yet</td></tr>
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
</body>
</html