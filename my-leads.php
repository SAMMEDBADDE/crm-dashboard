<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'counselor'){
    header("Location: login.html");
    exit();
}
include 'db.php';
$uid = $_SESSION['user_id'];

if(isset($_POST['update_status'])){
    $eid = $_POST['enquiry_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE enquiries SET status='$status' WHERE enquiry_id='$eid'");
    header("Location: my-leads.php");
    exit();
}

$leads = mysqli_query($conn, "SELECT * FROM enquiries WHERE assigned_to='$uid' ORDER BY enquiry_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
<div class="row">

    <?php include 'counselor-sidebar.php'; ?>

    <div class="col-md-10 p-4" style="background:#f1f5f9; min-height:100vh;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>My Leads</h3>
            <a href="add_lead.php" class="btn btn-primary">+ Add New Lead</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    if(mysqli_num_rows($leads) > 0){
                        while($row = mysqli_fetch_assoc($leads)){
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['course_interested']; ?></td>
                            <td><?php echo $row['source']; ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-1">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $row['enquiry_id']; ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <?php
                                        $statuses = ['New','Called','Follow-up','CNR','Closed','Converted'];
                                        foreach($statuses as $s){
                                            $sel = ($row['status']==$s) ? 'selected' : '';
                                            echo "<option value='$s' $sel>$s</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-success">✓</button>
                                </form>
                            </td>
                            <td>
                                <a href="counselor-add-call.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-info text-white">📞 Call</a>
                                <a href="followups.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-warning">📅 Follow-up</a>
                                <a href="counselor-admission.php?enquiry_id=<?php echo $row['enquiry_id']; ?>" class="btn btn-sm btn-success">🎓 Admit</a>
                            </td>
                        </tr>
                    <?php }} else { ?>
                        <tr><td colspan="8" class="text-center text-muted py-3">No leads assigned yet</td></tr>
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