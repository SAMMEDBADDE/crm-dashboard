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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Call Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4">
        <h3>Call Records</h3>

        <!-- ADD CALL FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">Add Call Record</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Select Student</label>
                            <select name="enquiry_id" class="form-select" required>
                                <option value="">-- Select --</option>
                                <?php
                                mysqli_data_seek($myLeads, 0);
                                while($l = mysqli_fetch_assoc($myLeads)){
                                    $sel = ($preSelected == $l['enquiry_id']) ? 'selected' : '';
                                    echo "<option value='{$l['enquiry_id']}' $sel>{$l['student_name']} - {$l['phone']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Call Date</label>
                            <input type="date" name="call_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" name="save_call" class="btn btn-primary w-100">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- CALL RECORDS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">My Call History</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
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
                    if(mysqli_num_rows($callRecords) > 0){
                        while($row = mysqli_fetch_assoc($callRecords)){
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['call_date']; ?></td>
                            <td><span class="badge bg-info"><?php echo $row['call_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="6" class="text-center text-muted">No call records yet</td></tr>
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