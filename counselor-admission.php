<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.php");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

if(isset($_POST['save_admission'])){
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
        $success = "Admission saved successfully!";
    }
}

$enquiries = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$uid' AND status != 'Converted'");
$admissions = mysqli_query($conn, "SELECT a.*, e.student_name, e.phone, e.course_interested 
    FROM admissions a 
    JOIN enquiries e ON a.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid' 
    ORDER BY a.admission_id DESC");
$total_admissions = mysqli_num_rows($admissions);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admission</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-graduation-cap me-2 text-success"></i>Admission</h4>
                <small class="text-muted"><?php echo $total_admissions; ?> admissions converted so far</small>
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

        <!-- FORM -->
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
                                if(mysqli_num_rows($enquiries) > 0){
                                    while($row = mysqli_fetch_assoc($enquiries)){
                                        echo "<option value='{$row['enquiry_id']}'>{$row['student_name']} - {$row['phone']}</option>";
                                    }
                                } else {
                                    echo "<option disabled>No pending leads</option>";
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
                            <button type="submit" name="save_admission" class="btn btn-success w-100">
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
                <i class="fa-solid fa-list"></i> My Admissions
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Total Fees</th>
                            <th>Fees Paid</th>
                            <th>Payment Status</th>
                            <th>Payment Type</th>
                            <th>Installments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if($total_admissions > 0){
                        mysqli_data_seek($admissions, 0);
                        while($row = mysqli_fetch_assoc($admissions)){
                            $badgeColor = match($row['payment_status']){
                                'Paid' => 'success',
                                'Partial' => 'warning',
                                'Pending' => 'danger',
                                default => 'secondary'
                            };
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $row['student_name']; ?></strong></td>
                            <td><i class="fa-solid fa-phone fa-xs text-muted me-1"></i><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><strong>₹<?php echo number_format($row['fees']); ?></strong></td>
                            <td>₹<?php echo number_format($row['fees_paid']); ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['payment_status']; ?></span></td>
                            <td>
                                <?php
                                $typeIcon = match($row['payment_type']){
                                    'Cash' => 'fa-money-bill',
                                    'Online' => 'fa-globe',
                                    'UPI' => 'fa-mobile-screen',
                                    default => 'fa-credit-card'
                                };
                                ?>
                                <i class="fa-solid <?php echo $typeIcon; ?> me-1 text-muted"></i><?php echo $row['payment_type']; ?>
                            </td>
                            <td><?php echo $row['installment']; ?></td>
                            <td><?php echo $row['admission_date']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
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