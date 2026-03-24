<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

if(!isset($_GET['id'])){
    header("Location: manage-leads.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM enquiries WHERE enquiry_id='$id'");
$data = mysqli_fetch_assoc($result);

if(!$data){
    header("Location: manage-leads.php");
    exit();
}

// UPDATE LEAD
if(isset($_POST['update_lead'])){
    $name = $_POST['student_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $course = $_POST['course'];
    $source = $_POST['source'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE enquiries SET 
        student_name='$name', phone='$phone', email='$email', 
        city='$city', course_interested='$course', 
        source='$source', status='$status' 
        WHERE enquiry_id='$id'");
    header("Location: manage-leads.php");
    exit();
}

$sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Lead</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-user-pen me-2 text-warning"></i>Edit Lead</h4>
                <small class="text-muted">Update details for — <strong><?php echo $data['student_name']; ?></strong></small>
            </div>
            <a href="manage-leads.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-edit"></i> Update Lead Details
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" name="student_name" class="form-control"
                                        value="<?php echo $data['student_name']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?php echo $data['phone']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo $data['email']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control"
                                        value="<?php echo $data['city']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course Interested</label>
                                    <input type="text" name="course" class="form-control"
                                        value="<?php echo $data['course_interested']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lead Source</label>
                                    <select name="source" class="form-select">
                                        <?php while($s = mysqli_fetch_assoc($sources)){
                                            $sel = ($s['source_name'] == $data['source']) ? 'selected' : '';
                                            echo "<option value='{$s['source_name']}' $sel>{$s['source_name']}</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lead Status</label>
                                    <select name="status" class="form-select">
                                        <?php
                                        $statuses = ['New','Called','Follow-up','CNR','Closed','Converted'];
                                        foreach($statuses as $s){
                                            $sel = ($data['status']==$s) ? 'selected' : '';
                                            echo "<option value='$s' $sel>$s</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="update_lead" class="btn btn-success w-100">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Update Lead
                                </button>
                                <a href="manage-leads.php" class="btn btn-secondary w-100">
                                    <i class="fa-solid fa-xmark me-1"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
</body>
</html>