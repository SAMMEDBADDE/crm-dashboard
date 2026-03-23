<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 bg-dark text-white p-3" style="min-height:100vh;">
    <h5 class="text-center">Admin Panel</h5>
    <hr>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='admin-dashboard.php') echo 'bg-primary'; ?>" href="admin-dashboard.php">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-users.php') echo 'bg-primary'; ?>" href="manage-users.php">Manage Users</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-leads.php') echo 'bg-primary'; ?>" href="manage-leads.php">Manage Leads</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-list.php') echo 'bg-primary'; ?>" href="manage-list.php">Manage List</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='reports.php') echo 'bg-primary'; ?>" href="reports.php">Reports</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='followup.php') echo 'bg-primary'; ?>" href="followup.php">Follow-Ups</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='admission.php') echo 'bg-primary'; ?>" href="admission.php">Admission</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='call-records.php') echo 'bg-primary'; ?>" href="call-records.php">Call Records</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="login.html">Logout</a>
        </li>

    </ul>
</div>