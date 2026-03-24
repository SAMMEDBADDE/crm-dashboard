<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.php");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

if(isset($_POST['save_followup'])){
    $eid = $_POST['enquiry_id'];
    $fdate = $_POST['followup_date'];
    $remarks = $_POST['remarks'];
    $status = $_POST['status'];
    mysqli_query($conn, "INSERT INTO followups (enquiry_id, followup_date, followup_status, remarks) 
        VALUES ('$eid','$fdate','$status','$remarks')");
    header("Location: followups.php");
    exit();
}

$preSelected = isset($_GET['enquiry_id']) ? $_GET['enquiry_id'] : '';
$myLeads = mysqli_query($conn, "SELECT enquiry_id, student_name, phone FROM enquiries WHERE assigned_to='$uid'");
$followupList = mysqli_query($conn, "SELECT f.*, e.student_name, e.phone FROM followups f 
    JOIN enquiries e ON f.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid' ORDER BY f.followup_date ASC");
$today = date('Y-m-d');
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

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-calendar-check me-2 text-warning"></i>Follow-Ups</h4>
                <small class="text-muted">Schedule and track your follow-ups</small>
            </div>
            <span class="badge bg-warning px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('l, d M Y'); ?>
            </span>
        </div>

        <!-- SCHEDULE FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-calendar-plus"></i> Schedule Follow-Up
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label>Select Student</label>
                            <select name="enquiry_id" class="form-select" required>
                                <option value="">-- Select --</option>
                                <?php
                                if(mysqli_num_rows($myLeads) > 0){
                                    while($l = mysqli_fetch_assoc($myLeads)){
                                        $sel = ($preSelected == $l['enquiry_id']) ? 'selected' : '';
                                        echo "<option value='{$l['enquiry_id']}' $sel>{$l['student_name']} - {$l['phone']}</option>";
                                    }
                                } else {
                                    echo "<option disabled>No leads assigned</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Follow-Up Date</label>
                            <input type="date" name="followup_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Done">Done</option>
                                <option value="Pending">Pending</option>
                                <option value="Rescheduled">Rescheduled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="save_followup" class="btn btn-primary w-100">
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
                            <th>Follow-Up Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if(mysqli_num_rows($followupList) > 0){
                        while($row = mysqli_fetch_assoc($followupList)){
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
                            <td>
                                <?php echo $row['followup_date']; ?>
                                <?php if($isToday){ ?>
                                    <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Today</span>
                                <?php } ?>
                            </td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['followup_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
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