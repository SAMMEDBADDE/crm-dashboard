<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

// ADD COURSE
if(isset($_POST['add_course'])){
    $name = $_POST['course_name'];
    $fees = $_POST['fees'];
    $check = mysqli_query($conn, "SELECT * FROM courses WHERE course_name='$name'");
    if(mysqli_num_rows($check) > 0){
        $error = "Course already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO courses (course_name, fees) VALUES ('$name','$fees')");
        $success = "Course added successfully!";
    }
}

// DELETE COURSE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM courses WHERE course_id='$id'");
    header("Location: manage-courses.php");
    exit();
}

// EDIT COURSE
if(isset($_POST['update_course'])){
    $id = $_POST['course_id'];
    $name = $_POST['course_name'];
    $fees = $_POST['fees'];
    mysqli_query($conn, "UPDATE courses SET course_name='$name', fees='$fees' WHERE course_id='$id'");
    $success = "Course updated successfully!";
}

$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_id DESC");
$total = mysqli_num_rows($courses);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-book me-2 text-primary"></i>Manage Courses</h4>
                <small class="text-muted">Total <?php echo $total; ?> courses available</small>
            </div>
            <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                <i class="fa-solid fa-graduation-cap me-1"></i> Course Management
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

        <div class="row g-4">

            <!-- ADD COURSE FORM -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Add New Course
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="course_name" class="form-control"
                                    placeholder="e.g. Java Full Stack" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Fees (₹)</label>
                                <input type="number" name="fees" class="form-control"
                                    placeholder="e.g. 50000" required>
                            </div>
                            <button type="submit" name="add_course" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-1"></i> Add Course
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <small class="text-muted d-block mb-1">Total Courses</small>
                            <h2 class="fw-bold text-primary"><?php echo $total; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COURSES TABLE -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list"></i> All Courses
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th>
                                    <th>Fees</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            mysqli_data_seek($courses, 0);
                            if($total > 0){
                                while($row = mysqli_fetch_assoc($courses)){
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <i class="fa-solid fa-book me-2 text-primary"></i>
                                        <strong><?php echo $row['course_name']; ?></strong>
                                    </td>
                                    <td><strong>₹<?php echo number_format($row['fees']); ?></strong></td>
                                    <td>
                                        <!-- INLINE EDIT FORM -->
                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#edit<?php echo $row['course_id']; ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <a href="manage-courses.php?delete=<?php echo $row['course_id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this course?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                        <!-- EDIT ROW -->
                                        <div class="collapse mt-2" id="edit<?php echo $row['course_id']; ?>">
                                            <form method="POST" class="d-flex gap-2">
                                                <input type="hidden" name="course_id" value="<?php echo $row['course_id']; ?>">
                                                <input type="text" name="course_name" class="form-control form-control-sm"
                                                    value="<?php echo $row['course_name']; ?>" required>
                                                <input type="number" name="fees" class="form-control form-control-sm"
                                                    value="<?php echo $row['fees']; ?>" required>
                                                <button type="submit" name="update_course" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php }} else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fa-solid fa-book fa-2x mb-2 d-block"></i>
                                        No courses added yet
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
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>