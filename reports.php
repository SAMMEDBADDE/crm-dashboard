<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

// FILTERS
$where = "WHERE 1=1";
if(!empty($_GET['status'])) $where .= " AND e.status='{$_GET['status']}'";
if(!empty($_GET['source'])) $where .= " AND e.source='{$_GET['source']}'";
if(!empty($_GET['counselor'])) $where .= " AND e.assigned_to='{$_GET['counselor']}'";
if(!empty($_GET['date_from'])) $where .= " AND e.enquiry_date>='{$_GET['date_from']}'";
if(!empty($_GET['date_to'])) $where .= " AND e.enquiry_date<='{$_GET['date_to']}'";

$result = mysqli_query($conn, "SELECT e.*, u.name as counselor_name 
    FROM enquiries e 
    LEFT JOIN users u ON e.assigned_to = u.id 
    $where ORDER BY e.enquiry_id DESC");
$total = mysqli_num_rows($result);

// STATS
$total_leads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries"))['t'];
$converted = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries WHERE status='Converted'"))['t'];
$followup = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries WHERE status='Follow-up'"))['t'];
$new_leads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM enquiries WHERE status='New'"))['t'];
$conversion_rate = $total_leads > 0 ? round(($converted/$total_leads)*100, 1) : 0;

// FILTER OPTIONS
$sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
$counselors = mysqli_query($conn, "SELECT id, name FROM users WHERE role='counselor'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-chart-bar me-2 text-primary"></i>Reports & Analytics</h4>
                <small class="text-muted">Showing <?php echo $total; ?> records</small>
            </div>
            <a href="export_excel.php" class="btn btn-success">
                <i class="fa-solid fa-file-excel me-1"></i> Export to Excel
            </a>
        </div>

        <!-- STATS CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="card text-white bg-primary shadow p-3 text-center">
                    <div style="font-size:24px; opacity:0.25;"><i class="fa-solid fa-users"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total_leads; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Leads</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-success shadow p-3 text-center">
                    <div style="font-size:24px; opacity:0.25;"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $converted; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Converted</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-warning shadow p-3 text-center">
                    <div style="font-size:24px; opacity:0.25;"><i class="fa-solid fa-calendar-check"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $followup; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Follow-Up</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-info shadow p-3 text-center">
                    <div style="font-size:24px; opacity:0.25;"><i class="fa-solid fa-folder-open"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $new_leads; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">New Leads</small>
                </div>
            </div>
            <div class="col-md">
                <div class="card text-white bg-dark shadow p-3 text-center">
                    <div style="font-size:24px; opacity:0.25;"><i class="fa-solid fa-percent"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $conversion_rate; ?>%</h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Conversion Rate</small>
                </div>
            </div>
        </div>

        <!-- CONVERSION PROGRESS -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Overall Conversion Progress
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-bold">Leads → Admissions</small>
                    <small><?php echo $converted; ?> / <?php echo $total_leads; ?></small>
                </div>
                <div class="progress" style="height:14px; border-radius:10px;">
                    <div class="progress-bar bg-success" style="width:<?php echo $conversion_rate; ?>%; border-radius:10px;">
                        <?php if($conversion_rate > 5) echo $conversion_rate.'%'; ?>
                    </div>
                </div>
                <small class="text-muted mt-1 d-block">
                    <?php
                    if($conversion_rate >= 50) echo "🔥 Excellent conversion rate!";
                    elseif($conversion_rate >= 25) echo "👍 Good progress!";
                    else echo "💪 Keep pushing the team!";
                    ?>
                </small>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter Reports
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <?php
                                $statuses = ['New','Called','Follow-up','CNR','Converted','Closed'];
                                foreach($statuses as $s){
                                    $sel = (isset($_GET['status']) && $_GET['status']==$s) ? 'selected' : '';
                                    echo "<option value='$s' $sel>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Source</label>
                            <select name="source" class="form-select">
                                <option value="">All Sources</option>
                                <?php while($s = mysqli_fetch_assoc($sources)){
                                    $sel = (isset($_GET['source']) && $_GET['source']==$s['source_name']) ? 'selected' : '';
                                    echo "<option value='{$s['source_name']}' $sel>{$s['source_name']}</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Counselor</label>
                            <select name="counselor" class="form-select">
                                <option value="">All Counselors</option>
                                <?php while($c = mysqli_fetch_assoc($counselors)){
                                    $sel = (isset($_GET['counselor']) && $_GET['counselor']==$c['id']) ? 'selected' : '';
                                    echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-filter"></i>
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="reports.php" class="btn btn-secondary w-100">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RESULTS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-table-list me-2"></i>Lead Report</span>
                <span class="badge bg-primary"><?php echo $total; ?> Records</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Course</th>
                            <th>Source</th>
                            <th>Counselor</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if($total > 0){
                        mysqli_data_seek($result, 0);
                        while($row = mysqli_fetch_assoc($result)){
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
                            <td><?php echo $row['city']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><?php echo $row['source']; ?></td>
                            <td>
                                <?php echo $row['counselor_name']
                                    ? '<span class="badge bg-info">'.$row['counselor_name'].'</span>'
                                    : '<span class="badge bg-secondary">Unassigned</span>'; ?>
                            </td>
                            <td><?php echo $row['enquiry_date'] ?? 'N/A'; ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['status']; ?></span></td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fa-solid fa-chart-bar fa-2x mb-2 d-block"></i>
                                No records found for selected filters
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