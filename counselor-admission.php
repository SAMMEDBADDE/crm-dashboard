<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

// Save admission
if(isset($_POST['save_admission'])){
    $eid = $_POST['enquiry_id'];
    $fees = $_POST['fees'];
    $fees_paid = $_POST['fees_paid'];
    $payment_status = $_POST['payment_status'];
    $payment_type = $_POST['payment_type'];
    $installment = $_POST['installment'];
    $date = date('Y-m-d');

    // Check if admission already exists
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

// Only counselor's assigned leads
$enquiries = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$uid' AND status != 'Converted'");

// Only counselor's admissions
$admissions = mysqli_query($conn, "SELECT a.*, e.student_name, e.phone, e.course_interested 
    FROM admissions a 
    JOIN enquiries e ON a.enquiry_id = e.enquiry_id 
    WHERE e.assigned_to='$uid' 
    ORDER BY a.admission_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4" style="background:#f1f5f9; min-height:100vh;">
        <h3>Admission</h3>

        <?php if(isset($success)){ ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if(isset($error)){ ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <!-- ADD ADMISSION FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">Convert Lead to Admission</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-2 mb-3">
                            <label>Total Fees</label>
                            <input type="number" name="fees" class="form-control" placeholder="e.g. 50000" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Fees Paid</label>
                            <input type="number" name="fees_paid" class="form-control" placeholder="e.g. 25000" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-select">
                                <option value="Cash">Cash</option>
                                <option value="Online">Online</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Installments</label>
                            <input type="number" name="installment" class="form-control" placeholder="e.g. 3">
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" name="save_admission" class="btn btn-success w-100">🎓 Convert to Admission</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ADMISSIONS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">My Admissions</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
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
                    if(mysqli_num_rows($admissions) > 0){
                        while($row = mysqli_fetch_assoc($admissions)){
                            $badgeColor = $row['payment_status'] == 'Paid' ? 'success' : ($row['payment_status'] == 'Partial' ? 'warning' : 'danger');
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td>₹<?php echo number_format($row['fees']); ?></td>
                            <td>₹<?php echo number_format($row['fees_paid']); ?></td>
                            <td><span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $row['payment_status']; ?></span></td>
                            <td><?php echo $row['payment_type']; ?></td>
                            <td><?php echo $row['installment']; ?></td>
                            <td><?php echo $row['admission_date']; ?></td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="10" class="text-center text-muted py-3">No admissions yet</td></tr>
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