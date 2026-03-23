<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 bg-dark text-white p-3" style="min-height:100vh;">
    <h5 class="text-center">Counselor Panel</h5>
    <hr>
    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-dashboard.php') echo 'bg-primary'; ?>" href="counselor-dashboard.php">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='my-leads.php') echo 'bg-primary'; ?>" href="my-leads.php">My Leads</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-add-call.php') echo 'bg-primary'; ?>" href="counselor-add-call.php">Call Records</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='followups.php') echo 'bg-primary'; ?>" href="followups.php">Follow-Ups</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-admission.php') echo 'bg-primary'; ?>" href="counselor-admission.php">Admission</a>
        </li>

        <li class="nav-item">
<a class="nav-link text-white <?php if($currentPage=='counselor-reports.php') echo 'bg-primary'; ?>" href="counselor-reports.php">My Performance</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="login.html">Logout</a>
        </li>

    </ul>
</div>