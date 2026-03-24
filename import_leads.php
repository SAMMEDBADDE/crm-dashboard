<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include 'db.php';

$success = 0;
$duplicates = 0;
$errors = 0;
$error_msg = '';

if(isset($_POST['import_csv'])){
    if($_FILES['csv_file']['error'] == 0){
        $file = $_FILES['csv_file']['tmp_name'];
        $source = $_POST['source'];
        $assigned_to = $_POST['assigned_to'];

        $handle = fopen($file, 'r');

        // Skip header row
        $header = fgetcsv($handle);

        while(($row = fgetcsv($handle)) !== false){

            // Skip empty rows
            if(empty(array_filter($row))) continue;

            // Get values safely
            $name  = isset($row[0]) ? mysqli_real_escape_string($conn, trim($row[0])) : '';
            $email = isset($row[1]) ? mysqli_real_escape_string($conn, trim($row[1])) : '';
            $phone = isset($row[2]) ? mysqli_real_escape_string($conn, trim($row[2])) : '';
            $course = isset($row[3]) ? mysqli_real_escape_string($conn, trim($row[3])) : '';
            $city  = isset($row[4]) ? mysqli_real_escape_string($conn, trim($row[4])) : '';

            // Skip if no name or phone
            if(empty($name) || empty($phone)) {
                $errors++;
                continue;
            }

            // Check duplicate by phone
            $check = mysqli_query($conn, "SELECT enquiry_id FROM enquiries WHERE phone='$phone'");
            if(mysqli_num_rows($check) > 0){
                $duplicates++;
                continue;
            }

            // Insert
            $date = date('Y-m-d');
            $insert = mysqli_query($conn, "INSERT INTO enquiries 
                (student_name, email, phone, course_interested, city, source, status, assigned_to, enquiry_date) 
                VALUES ('$name','$email','$phone','$course','$city','$source','New','$assigned_to','$date')");

            if($insert) $success++;
            else $errors++;
        }

        fclose($handle);

    } else {
        $error_msg = "Please select a valid CSV file!";
    }
}

$counselors = mysqli_query($conn, "SELECT id, name FROM users WHERE role='counselor' AND status='active'");
$sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Leads</title>
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
                <h4 class="mb-0"><i class="fa-solid fa-file-import me-2 text-success"></i>Import Leads</h4>
                <small class="text-muted">Upload CSV file to bulk import leads</small>
            </div>
            <a href="manage-leads.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Leads
            </a>
        </div>

        <!-- RESULTS -->
        <?php if($success > 0 || $duplicates > 0 || $errors > 0){ ?>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-success shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-circle-check"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $success; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Imported Successfully</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-copy"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $duplicates; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Duplicates Skipped</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger shadow p-3 text-center">
                    <div style="font-size:28px; opacity:0.25;"><i class="fa-solid fa-circle-xmark"></i></div>
                    <h2 class="fw-bold mb-0"><?php echo $errors; ?></h2>
                    <small style="opacity:0.85; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">Errors / Skipped</small>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if(!empty($error_msg)){ ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-xmark"></i> <?php echo $error_msg; ?>
            </div>
        <?php } ?>

        <div class="row g-4">

            <!-- UPLOAD FORM -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-upload"></i> Upload CSV File
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label">Select CSV File</label>
                                <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                                <small class="text-muted">Only .csv files supported</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lead Source</label>
                                <select name="source" class="form-select" required>
                                    <option value="">-- Select Source --</option>
                                    <?php while($s = mysqli_fetch_assoc($sources)){
                                        echo "<option value='{$s['source_name']}'>{$s['source_name']}</option>";
                                    } ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Assign To Counselor</label>
                                <select name="assigned_to" class="form-select">
                                    <option value="">-- Unassigned --</option>
                                    <?php while($c = mysqli_fetch_assoc($counselors)){
                                        echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                    } ?>
                                </select>
                            </div>

                            <button type="submit" name="import_csv" class="btn btn-success w-100">
                                <i class="fa-solid fa-file-import me-1"></i> Import Leads
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- FORMAT GUIDE -->
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i> CSV Format Guide
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3" style="font-size:13.5px;">
                            Your CSV file must follow this exact column order:
                        </p>
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Column</th>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">1</span></td>
                                    <td>Name</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>Rahul Sharma</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">2</span></td>
                                    <td>Email</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>rahul@email.com</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">3</span></td>
                                    <td>Phone</td>
                                    <td><span class="badge bg-danger">Required</span></td>
                                    <td>9876543210</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">4</span></td>
                                    <td>Course</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>Java Full Stack</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">5</span></td>
                                    <td>City</td>
                                    <td><span class="badge bg-secondary">Optional</span></td>
                                    <td>Kolhapur</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-warning d-flex gap-2 mt-3" style="font-size:13px;">
                            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                            <div>
                                <strong>Important Notes:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>First row must be the header row</li>
                                    <li>Duplicate phone numbers will be automatically skipped</li>
                                    <li>Rows missing Name or Phone will be skipped</li>
                                    <li>All imported leads get status <strong>New</strong></li>
                                </ul>
                            </div>
                        </div>

                        <!-- SAMPLE DOWNLOAD -->
                        <a href="sample_leads.csv" download class="btn btn-outline-primary w-100 mt-2">
                            <i class="fa-solid fa-download me-1"></i> Download Sample CSV
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</body>
</html>