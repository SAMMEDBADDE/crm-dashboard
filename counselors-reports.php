<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'counselor-sidebar.php'; ?>

<div class="main-content">

    <div class="topbar">
        <div class="topbar-title">My Performance</div>
        <div class="topbar-user">
            <span><?php echo $_SESSION['name']; ?></span>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['name'],0,1)); ?></div>
        </div>
    </div>

    <div class="page-header">
        <h3>Performance Report</h3>
        <p>Welcome back, <?php echo $_SESSION['name']; ?> 👋</p>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
                <div class="stat-value"><?php echo $total_leads; ?></div>
                <div class="stat-label">Total Leads</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card">
                <div class="stat-icon icon-cyan"><i class="fa-solid fa-phone"></i></div>
                <div class="stat-value"><?php echo $total_calls; ?></div>
                <div class="stat-label">Calls Made</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-value"><?php echo $total_followups; ?></div>
                <div class="stat-label">Follow-Ups</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="stat-value"><?php echo $total_admissions; ?></div>
                <div class="stat-label">Admissions</div>
            </div>
        </div>
        <div class="col-md">
            <div class="stat-card">
                <div class="stat-icon icon-purple"><i class="fa-solid fa-percent"></i></div>
                <div class="stat-value"><?php echo $conversion; ?>%</div>
                <div class="stat-label">Conversion Rate</div>
            </div>
        </div>
    </div>

    <!-- STATUS BREAKDOWN -->
    <div class="crm-card">
        <div class="crm-card-header">
            <i class="fa-solid fa-chart-pie"></i> Leads by Status
        </div>
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(mysqli_num_rows($statusReport) > 0){
                while($row = mysqli_fetch_assoc($statusReport)){
                    $badgeClass = 'badge-' . strtolower(str_replace('-','',$row['status']));
            ?>
                <tr>
                    <td><span class="badge-status <?php echo $badgeClass; ?>"><?php echo $row['status']; ?></span></td>
                    <td><strong><?php echo $row['total']; ?></strong></td>
                </tr>
            <?php }} else { ?>
                <tr><td colspan="2" style="text-align:center; padding:20px; color:#94a3b8;">No data available</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>