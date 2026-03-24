<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.html");
    exit();
}
include 'db.php';

$total_leads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries"))['t'];
$open_leads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries WHERE status='New'"))['t'];
$followups = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups"))['t'];
$admissions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM admissions"))['t'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='counselor'"))['t'];
$today = date('Y-m-d');
$today_followups = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM followups WHERE followup_date='$today'"))['t'];

$recentLeads = mysqli_query($conn, "SELECT e.*, u.name as counselor_name FROM enquiries e 
    LEFT JOIN users u ON e.assigned_to = u.id 
    ORDER BY e.enquiry_id DESC LIMIT 8");

$counselorStats = mysqli_query($conn, "SELECT u.name, 
    COUNT(e.enquiry_id) as total_leads,
    SUM(CASE WHEN e.status='Converted' THEN 1 ELSE 0 END) as converted
    FROM users u 
    LEFT JOIN enquiries e ON u.id = e.assigned_to 
    WHERE u.role='counselor' 
    GROUP BY u.id, u.name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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

        <!-- TOP WELCOME BAR -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0">Welcome, <?php echo $_SESSION['name']; ?> 👋</h4>
                <small class="text-muted">Admin Dashboard — <?php echo date('l, d M Y'); ?></small>
            </div>
            <span class="badge bg-danger px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-shield-halved me-1"></i> Admin
            </span>
        </div>

        <!-- STAT CARDS ROW 1 -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Leads</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $total_leads; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <a href="manage-leads.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View All →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">New / Open</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $open_leads; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-folder-open"></i></div>
                    </div>
                    <a href="manage-leads.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Follow-Ups</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $followups; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-calendar-check"></i></div>
                    </div>
                    <a href="followup.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Admissions</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $admissions; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-graduation-cap"></i></div>
                    </div>
                    <a href="admission.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
        </div>

        <!-- STAT CARDS ROW 2 -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-info shadow p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Counselors</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $total_users; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-user-tie"></i></div>
                    </div>
                    <a href="manage-users.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">Manage →</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white shadow p-3 h-100" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:12px; opacity:0.85; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Today's Follow-Ups</div>
                            <div style="font-size:32px; font-weight:700; line-height:1.2;"><?php echo $today_followups; ?></div>
                        </div>
                        <div style="font-size:32px; opacity:0.25;"><i class="fa-solid fa-bell"></i></div>
                    </div>
                    <a href="followup.php" class="text-white mt-2 d-block" style="font-size:12px; opacity:0.85;">View →</a>
                </div>
            </div>
        </div>

        <!-- BOTTOM SECTION -->
        <div class="row g-3">

            <!-- RECENT LEADS -->
            <div class="col-md-7">
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
                                    <th>Source</th>
                                    <th>Assigned To</th>
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
                                    <td><?php echo $row['source']; ?></td>
                                    <td>
                                        <?php echo $row['counselor_name'] 
                                            ? '<span class="badge bg-info">'.$row['counselor_name'].'</span>' 
                                            : '<span class="badge bg-secondary">Unassigned</span>'; ?>
                                    </td>
                                    <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['status']; ?></span></td>
                                </tr>
                            <?php }} else { ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No leads yet</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COUNSELOR PERFORMANCE -->
            <div class="col-md-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-ranking-star"></i> Counselor Performance
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Counselor</th>
                                    <th>Leads</th>
                                    <th>Converted</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(mysqli_num_rows($counselorStats) > 0){
                                while($row = mysqli_fetch_assoc($counselorStats)){ ?>
                                <tr>
                                    <td><strong><?php echo $row['name']; ?></strong></td>
                                    <td><span class="badge bg-primary"><?php echo $row['total_leads']; ?></span></td>
                                    <td><span class="badge bg-success"><?php echo $row['converted']; ?></span></td>
                                </tr>
                            <?php }} else { ?>
                                <tr><td colspan="3" class="text-center text-muted py-4">No counselors yet</td></tr>
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
</html>