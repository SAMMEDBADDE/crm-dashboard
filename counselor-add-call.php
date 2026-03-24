<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

if(isset($_POST['save_call'])){
    $eid = $_POST['enquiry_id'];
    $date = $_POST['call_date'];
    $status = $_POST['call_status'];
    $remarks = $_POST['remarks'];
    mysqli_query($conn, "INSERT INTO call_records (enquiry_id, counselor_id, call_date, call_status, remarks) 
        VALUES ('$eid','$uid','$date','$status','$remarks')");
    mysqli_query($conn, "UPDATE enquiries SET status='$status' WHERE enquiry_id='$eid'");
    header("Location: counselor-add-call.php");
    exit();
}

$preSelected = isset($_GET['enquiry_id']) ? $_GET['enquiry_id'] : '';
$myLeads = mysqli_query($conn, "SELECT enquiry_id, student_name, phone FROM enquiries WHERE assigned_to='$uid'");
$callRecords = mysqli_query($conn, "SELECT c.*, e.student_name, e.phone FROM call_records c 
    JOIN enquiries e ON c.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid' ORDER BY c.call_id DESC");

$total_calls = mysqli_num_rows($callRecords);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Call Records</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-phone me-2 text-info"></i>Call Records</h4>
                <small class="text-muted">Total <?php echo $total_calls; ?> calls logged</small>
            </div>
            <span class="badge bg-info px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('l, d M Y'); ?>
            </span>
        </div>

        <!-- ADD CALL FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-phone-volume"></i> Add Call Record
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label>Select Student</label>
                            <select name="enquiry_id" class="form-select" required>
                                <option value="">-- Select --</option>
                                <?php
                                while($l = mysqli_fetch_assoc($myLeads)){
                                    $sel = ($preSelected == $l['enquiry_id']) ? 'selected' : '';
                                    echo "<option value='{$l['enquiry_id']}' $sel>{$l['student_name']} - {$l['phone']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Call Date</label>
                            <input type="date" name="call_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label>Call Status</label>
                            <select name="call_status" class="form-select" required>
                                <option value="Called">Called</option>
                                <option value="CNR">CNR (Not Reachable)</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Interested">Interested</option>
                                <option value="Not Interested">Not Interested</option>
                                <option value="Converted">Converted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="save_call" class="btn btn-primary w-100">
                                <i class="fa-solid fa-save"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- CALL RECORDS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> My Call History
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if($total_calls > 0){
                        mysqli_data_seek($callRecords, 0);
                        while($row = mysqli_fetch_assoc($callRecords)){
                            $badgeColor = match($row['call_status']){
                                'Called' => 'info',
                                'Interested' => 'success',
                                'Converted' => 'success',
                                'CNR' => 'danger',
                                'Not Interested' => 'danger',
                                'Follow-up' => 'warning',
                                default => 'secondary'
                            };
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $row['student_name']; ?></strong></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['call_date']; ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['call_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-phone-slash fa-2x mb-2 d-block"></i>
                                No call records yet
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