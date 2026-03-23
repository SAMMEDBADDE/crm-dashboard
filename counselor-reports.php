<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

$total_leads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries WHERE assigned_to='$uid'"))['t'];
$total_calls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM call_records c JOIN enquiries e ON c.enquiry_id=e.enquiry_id WHERE e.assigned_to='$uid'"))['t'];
$total_followups = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups f JOIN enquiries e ON f.enquiry_id=e.enquiry_id WHERE e.assigned_to='$uid'"))['t'];
$total_admissions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM admissions a JOIN enquiries e ON a.enquiry_id=e.enquiry_id WHERE e.assigned_to='$uid'"))['t'];
$conversion = $total_leads > 0 ? round(($total_admissions/$total_leads)*100, 1) : 0;

$statusReport = mysqli_query($conn, "SELECT status, COUNT(*) as total FROM enquiries WHERE assigned_to='$uid' GROUP BY status");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4" style="background:#f1f5f9; min-height:100vh;">
        <h3>My Performance Report</h3>
        <p class="text-muted">Welcome, <?php echo $_SESSION['name']; ?> 👋</p>

        <div class="row mt-3">
            <div class="col-md-2 mb-3">
                <div class="card bg-primary text-white p-3 text-center shadow">
                    <h6>Total Leads</h6>
                    <h2><?php echo $total_leads; ?></h2>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card bg-info text-white p-3 text-center shadow">
                    <h6>Calls Made</h6>
                    <h2><?php echo $total_calls; ?></h2>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card bg-warning text-white p-3 text-center shadow">
                    <h6>Follow-Ups</h6>
                    <h2><?php echo $total_followups; ?></h2>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card bg-success text-white p-3 text-center shadow">
                    <h6>Admissions</h6>
                    <h2><?php echo $total_admissions; ?></h2>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card bg-dark text-white p-3 text-center shadow">
                    <h6>Conversion %</h6>
                    <h2><?php echo $conversion; ?>%</h2>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="card mt-3 shadow-sm">
            <div class="card-header bg-dark text-white">My Leads by Status</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(mysqli_num_rows($statusReport) > 0){
                        while($row = mysqli_fetch_assoc($statusReport)){
                    ?>
                        <tr>
                            <td><?php echo $row['status']; ?></td>
                            <td><span class="badge bg-secondary fs-6"><?php echo $row['total']; ?></span></td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="2" class="text-center text-muted py-3">No data available</td></tr>
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