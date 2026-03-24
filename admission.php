<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.html");
    exit();
}
include 'db.php';

// ADD ADMISSION
if(isset($_POST['add_admission'])){
    $eid = $_POST['enquiry_id'];
    $fees = $_POST['fees'];
    $fees_paid = $_POST['fees_paid'];
    $payment_status = $_POST['payment_status'];
    $payment_type = $_POST['payment_type'];
    $installment = $_POST['installment'];
    $date = date('Y-m-d');

    $check = mysqli_query($conn, "SELECT * FROM admissions WHERE enquiry_id='$eid'");
    if(mysqli_num_rows($check) > 0){
        $error = "Admission already exists for this student!";
    } else {
        mysqli_query($conn, "INSERT INTO admissions (enquiry_id, fees, fees_paid, payment_status, payment_type, installment, admission_date) 
            VALUES ('$eid','$fees','$fees_paid','$payment_status','$payment_type','$installment','$date')");
        mysqli_query($conn, "UPDATE enquiries SET status='Converted' WHERE enquiry_id='$eid'");
        $success = "Admission added successfully!";
    }
}

// DELETE ADMISSION
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM admissions WHERE admission_id='$id'");
    header("Location: admission.php");
    exit();
}

$enquiries = mysqli_query($conn, "SELECT * FROM enquiries WHERE status != 'Converted' ORDER BY student_name ASC");
$admissions = mysqli_query($conn, "SELECT a.*, e.student_name, e.phone, e.course_interested, u.name as counselor_name
    FROM admissions a 
    JOIN enquiries e ON a.enquiry_id = e.enquiry_id
    LEFT JOIN users u ON e.assigned_to = u.id
    ORDER BY a.admission_id DESC");
$total = mysqli_num_rows($admissions);

$total_fees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(fees) as t FROM admissions"))['t'];
$total_paid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(fees_paid) as t FROM admissions"))['t'];
$paid_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM admissions WHERE payment_status='Paid'"))['t'];
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM admissions WHERE payment_status='Pending'"))['t'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admissions</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-graduation-cap me-2 text-success"></i>Admission System</h4>
                <small class="text-muted">Total <?php echo $total; ?> admissions recorded</small>
            </div>
            <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-circle-check me-1"></i> Admission Panel
            </span>
        </div>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php } ?>
        <?php if(isset($error)){ ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-xmark"></i> <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- QUICK STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $total; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Admissions</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                    <h2 class="fw-bold mb-0">₹<?php echo number_format($total_paid); ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Total Collected</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-circle-check"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $paid_count; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Fully Paid</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-clock"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $pending_count; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Payment Pending</small>
                </div>
            </div>
        </div>

        <!-- ADD ADMISSION FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-user-graduate"></i> Convert Lead to Admission
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label>Select Student</label>
                            <select name="enquiry_id" class="form-select" required>
                                <option value="">-- Select Lead --</option>
                                <?php
                                while($row = mysqli_fetch_assoc($enquiries)){
                                    echo "<option value='{$row['enquiry_id']}'>{$row['student_name']} - {$row['phone']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Total Fees</label>
                            <input type="number" name="fees" class="form-control" placeholder="e.g. 50000" required>
                        </div>
                        <div class="col-md-2">
                            <label>Fees Paid</label>
                            <input type="number" name="fees_paid" class="form-control" placeholder="e.g. 25000" required>
                        </div>
                        <div class="col-md-1">
                            <label>Installments</label>
                            <input type="number" name="installment" class="form-control" placeholder="3">
                        </div>
                        <div class="col-md-2">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Pay Type</label>
                            <select name="payment_type" class="form-select">
                                <option value="Cash">Cash</option>
                                <option value="Online">Online</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_admission" class="btn btn-success w-100">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ADMISSIONS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-list"></i> All Admissions
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
                            <th>Total Fees</th>
                            <th>Fees Paid</th>
                            <th>Payment Status</th>
                            <th>Pay Type</th>
                            <th>Installments</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    mysqli_data_seek($admissions, 0);
                    if($total > 0){
                        while($row = mysqli_fetch_assoc($admissions)){
                            $badgeColor = match($row['payment_status']){
                                'Paid' => 'success',
                                'Partial' => 'warning',
                                'Pending' => 'danger',
                                default => 'secondary'
                            };
                            $typeIcon = match($row['payment_type']){
                                'Cash' => 'fa-money-bill',
                                'Online' => 'fa-globe',
                                'UPI' => 'fa-mobile-screen',
                                default => 'fa-credit-card'
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
                                    : '<span class="badge bg-secondary">N/A</span>'; ?>
                            </td>
                            <td><strong>₹<?php echo number_format($row['fees']); ?></strong></td>
                            <td>₹<?php echo number_format($row['fees_paid']); ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['payment_status']; ?></span></td>
                            <td><i class="fa-solid <?php echo $typeIcon; ?> me-1 text-muted"></i><?php echo $row['payment_type']; ?></td>
                            <td><?php echo $row['installment']; ?></td>
                            <td><?php echo $row['admission_date']; ?></td>
                            <td>
                                <a href="admission.php?delete=<?php echo $row['admission_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this admission?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted py-5">
                                <i class="fa-solid fa-graduation-cap fa-2x mb-2 d-block"></i>
                                No admissions yet
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