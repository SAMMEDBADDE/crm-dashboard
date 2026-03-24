<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.html");
    exit();
}
include 'db.php';

// ADD CALL
if(isset($_POST['add_call'])){
    $eid = $_POST['enquiry_id'];
    $date = $_POST['call_date'];
    $status = $_POST['call_status'];
    $remarks = $_POST['remarks'];
    $cid = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO call_records (enquiry_id, counselor_id, call_date, call_status, remarks) 
        VALUES ('$eid','$cid','$date','$status','$remarks')");
    mysqli_query($conn, "UPDATE enquiries SET status='$status' WHERE enquiry_id='$eid'");
    $success = "Call record added successfully!";
}

// DELETE CALL
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM call_records WHERE call_id='$id'");
    header("Location: call-records.php");
    exit();
}

$enquiries = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY student_name ASC");
$calls = mysqli_query($conn, "SELECT c.*, e.student_name, e.phone, e.course_interested, u.name as counselor_name
    FROM call_records c
    JOIN enquiries e ON c.enquiry_id = e.enquiry_id
    LEFT JOIN users u ON c.counselor_id = u.id
    ORDER BY c.call_id DESC");
$total = mysqli_num_rows($calls);

$interested = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM call_records WHERE call_status='Interested'"))['t'];
$not_interested = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM call_records WHERE call_status='Not Interested'"))['t'];
$cnr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM call_records WHERE call_status='CNR'"))['t'];
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

    <?php include 'sidebar.php'; ?>

    <div class="col-md-10 p-4">

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm">
            <div>
                <h4 class="mb-0"><i class="fa-solid fa-phone me-2 text-info"></i>Call Records</h4>
                <small class="text-muted">Total <?php echo $total; ?> calls logged</small>
            </div>
            <span class="badge bg-info px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('l, d M Y'); ?>
            </span>
        </div>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php } ?>

        <!-- QUICK STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-phone"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Calls</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-thumbs-up"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $interested; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Interested</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-thumbs-down"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $not_interested; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Not Interested</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-phone-slash"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $cnr; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">CNR</small>
                </div>
            </div>
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
                                while($row = mysqli_fetch_assoc($enquiries)){
                                    echo "<option value='{$row['enquiry_id']}'>{$row['student_name']} - {$row['phone']}</option>";
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
                            <select name="call_status" class="form-select">
                                <option value="Called">Called</option>
                                <option value="CNR">CNR (Not Reachable)</option>
                                <option value="Interested">Interested</option>
                                <option value="Not Interested">Not Interested</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Converted">Converted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="Enter remarks">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_call" class="btn btn-primary w-100">
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
                <i class="fa-solid fa-clock-rotate-left"></i> All Call Records
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Counselor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    mysqli_data_seek($calls, 0);
                    if($total > 0){
                        while($row = mysqli_fetch_assoc($calls)){
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
                            <td><?php echo $row['course_interested']; ?></td>
                            <td>
                                <?php echo $row['counselor_name']
                                    ? '<span class="badge bg-info">'.$row['counselor_name'].'</span>'
                                    : '<span class="badge bg-secondary">Admin</span>'; ?>
                            </td>
                            <td><?php echo $row['call_date']; ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['call_status']; ?></span></td>
                            <td><?php echo $row['remarks']; ?></td>
                            <td>
                                <a href="call-records.php?delete=<?php echo $row['call_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
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