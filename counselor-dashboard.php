<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
    exit();
}
include 'db.php';

$uid = $_SESSION['user_id'];
$today = date('Y-m-d');

$leads = mysqli_query($conn, "SELECT COUNT(*) as total FROM enquiries WHERE assigned_to='$uid'");
$leads_count = mysqli_fetch_assoc($leads)['total'];

$followups = mysqli_query($conn, "SELECT COUNT(*) as total FROM followups f 
    JOIN enquiries e ON f.enquiry_id = e.enquiry_id 
    WHERE f.followup_date='$today' AND e.assigned_to='$uid'");
$follow_count = mysqli_fetch_assoc($followups)['total'];

$calls = mysqli_query($conn, "SELECT COUNT(*) as total FROM call_records c 
    JOIN enquiries e ON c.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid'");
$calls_count = mysqli_fetch_assoc($calls)['total'];

$admissions = mysqli_query($conn, "SELECT COUNT(*) as total FROM admissions a 
    JOIN enquiries e ON a.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid'");
$admission_count = mysqli_fetch_assoc($admissions)['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Counselor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4" style="background:#f1f5f9; min-height:100vh;">
        <h3 class="mb-1">Welcome, <?php echo $_SESSION['name']; ?> 👋</h3>
        <p class="text-muted">Here's your today's summary</p>

        <div class="row mt-3">

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary shadow p-3">
                    <h6>My Leads</h6>
                    <h2><?php echo $leads_count; ?></h2>
                    <a href="my-leads.php" class="text-white small">View All →</a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning shadow p-3">
                    <h6>Today's Follow-Ups</h6>
                    <h2><?php echo $follow_count; ?></h2>
                    <a href="followups.php" class="text-white small">View →</a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info shadow p-3">
                    <h6>Total Calls Made</h6>
                    <h2><?php echo $calls_count; ?></h2>
                    <a href="counselor-add-call.php" class="text-white small">View →</a>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success shadow p-3">
                    <h6>Admissions Done</h6>
                    <h2><?php echo $admission_count; ?></h2>
                    <a href="admission.php" class="text-white small">View →</a>
                </div>
            </div>

        </div>

        <!-- Today's Follow-ups Table -->
        <div class="card mt-3 shadow-sm">
            <div class="card-header bg-dark text-white">Today's Follow-Ups</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Follow-Up Date</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $todayList = mysqli_query($conn, "SELECT e.student_name, e.phone, e.course_interested, f.followup_date, f.remarks 
                        FROM followups f 
                        JOIN enquiries e ON f.enquiry_id = e.enquiry_id 
                        WHERE f.followup_date='$today' AND e.assigned_to='$uid'");
                    if(mysqli_num_rows($todayList) > 0){
                        while($row = mysqli_fetch_assoc($todayList)){
                    ?>
                        <tr>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><?php echo $row['followup_date']; ?></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="5" class="text-center text-muted">No follow-ups for today</td></tr>
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