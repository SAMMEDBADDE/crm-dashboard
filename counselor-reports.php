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
                <h4 class="mb-0"><i class="fa-solid fa-chart-bar me-2 text-primary"></i>My Performance</h4>
                <small class="text-muted">Welcome back, <?php echo $_SESSION['name']; ?> 👋</small>
            </div>
            <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('l, d M Y'); ?>
            </span>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="card text-white bg-primary shadow p-3 text-center h-100">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-users"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total_leads; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Leads</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-info shadow p-3 text-center h-100">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-phone"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total_calls; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Calls Made</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-warning shadow p-3 text-center h-100">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-calendar-check"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total_followups; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Follow-Ups</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-success shadow p-3 text-center h-100">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total_admissions; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Admissions</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-dark shadow p-3 text-center h-100">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-percent"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $conversion; ?>%</h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Conversion Rate</small>
                </div>
            </div>
        </div>

        <!-- PROGRESS BAR -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Conversion Progress
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-600">Leads → Admissions</small>
                    <small><?php echo $total_admissions; ?> / <?php echo $total_leads; ?></small>
                </div>
                <div class="progress" style="height:12px; border-radius:10px;">
                    <div class="progress-bar bg-success" style="width:<?php echo $conversion; ?>%; border-radius:10px;">
                        <?php if($conversion > 10) echo $conversion.'%'; ?>
                    </div>
                </div>
                <small class="text-muted mt-1 d-block">
                    <?php if($conversion >= 50){ echo "🔥 Excellent performance!"; }
                    elseif($conversion >= 25){ echo "👍 Good progress, keep going!"; }
                    else { echo "💪 Keep pushing, you can do it!"; } ?>
                </small>
            </div>
        </div>

        <!-- STATUS BREAKDOWN TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-pie"></i> Leads by Status
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(mysqli_num_rows($statusReport) > 0){
                        while($row = mysqli_fetch_assoc($statusReport)){
                            $badgeColor = match($row['status']){
                                'New' => 'primary',
                                'Called' => 'info',
                                'Follow-up' => 'warning',
                                'Converted' => 'success',
                                'CNR' => 'danger',
                                'Closed' => 'secondary',
                                default => 'secondary'
                            };
                            $percent = $total_leads > 0 ? round(($row['total']/$total_leads)*100) : 0;
                    ?>
                        <tr>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['status']; ?></span></td>
                            <td><strong><?php echo $row['total']; ?></strong></td>
                            <td style="width:40%;">
                                <div class="progress" style="height:8px; border-radius:10px;">
                                    <div class="progress-bar bg-<?php echo $badgeColor; ?>" style="width:<?php echo $percent; ?>%;"></div>
                                </div>
                                <small class="text-muted"><?php echo $percent; ?>%</small>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-bar fa-2x mb-2 d-block"></i>
                            No data available
                        </td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>