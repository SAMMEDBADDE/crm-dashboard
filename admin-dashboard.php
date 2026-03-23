<?php
include 'db.php';

// Dynamic Counts
$total_leads = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM enquiries"));
$followups = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM followups"));
$admissions = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admissions"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .nav-link:hover {
            background-color: #0d6efd;
        }
    </style>
</head>

<body>
<div class="container-fluid">
  <div class="row" style="margin:0;">

            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white p-3" style="min-height:100vh;">
                <h5 class="text-center">Admin Panel</h5>
                <hr>
                <ul class="nav flex-column">

                    <!-- FIX: .html → .php -->
                    <li class="nav-item"><a class="nav-link text-white" href="admin-dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link text-white" href="manage-users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="manage-leads.php">Manage Leads</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="manage-list.php">Manage List</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="reports.php">Reports</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="login.html">Logout</a></li>

                    <!-- FIX: Nested li hata diya (structure correct kiya) -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="followup.php">Follow-Ups</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="admission.php">Admission</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="call-records.php">Call Records</a>
                    </li>

                </ul>
            </div>

            <!-- Main Content -->
<div class="col-md-10 p-4">
    <h3>Dashboard</h3>

    <div class="row mt-4">

        <!-- Total Leads -->
        <div class="col-md-3">
            <a href="manage-leads.php" style="text-decoration:none;">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5>Total Leads</h5>
                        <h3><?php echo $total_leads; ?></h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Open (Static for now) -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>Open</h5>
                    <h3>45</h3>
                </div>
            </div>
        </div>

        <!-- Follow-ups -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>Follow-up</h5>
                    <h3><?php echo $followups; ?></h3>
                </div>
            </div>
        </div>

        <!-- Converted (Admissions) -->
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5>Converted</h5>
                    <h3><?php echo $admissions; ?></h3>
                </div>
            </div>
        </div>

    </div>
</div>

    </div>
</div>

</body>
</html>