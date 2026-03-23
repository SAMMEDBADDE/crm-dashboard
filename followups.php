<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
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

// ✅ Query runs AFTER post handling so result is always fresh
$myLeads = mysqli_query($conn, "SELECT enquiry_id, student_name, phone FROM enquiries WHERE assigned_to='$uid'");
$followupList = mysqli_query($conn, "SELECT f.*, e.student_name, e.phone FROM followups f 
    JOIN enquiries e ON f.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid' ORDER BY f.followup_date ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Follow-Ups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4" style="background:#f1f5f9; min-height:100vh;">
        <h3>Follow-Ups</h3>

        <!-- ADD FOLLOWUP FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">Schedule Follow-Up</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-2 mb-3">
                            <label>Follow-Up Date</label>
                            <input type="date" name="followup_date" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Done">Done</option>
                                <option value="Pending">Pending</option>
                                <option value="Rescheduled">Rescheduled</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" name="save_followup" class="btn btn-primary w-100">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- FOLLOWUPS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">All Follow-Ups</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
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
                    $today = date('Y-m-d');
                    if(mysqli_num_rows($followupList) > 0){
                        while($row = mysqli_fetch_assoc($followupList)){
                            $highlight = ($row['followup_date'] == $today) ? 'table-warning' : '';
                    ?>
                        <tr class="<?php echo $highlight; ?>">
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['followup_date']; ?></td>
                            <td><span class="badge bg-primary"><?php echo $row['followup_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">No follow-ups scheduled yet</td></tr>
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