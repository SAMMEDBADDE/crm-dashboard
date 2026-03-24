<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>



<div class="col-md-2 bg-dark text-white p-3" style="min-height:100vh;">
    <h5 class="text-center mb-1" style="font-size:15px; font-weight:700; letter-spacing:0.3px;">🎓 CRM System</h5>
    <p class="text-center text-muted" style="font-size:11px; margin-bottom:16px;">Counselor Panel</p>
    <hr class="mt-0">
    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-dashboard.php') echo 'bg-primary'; ?>" href="counselor-dashboard.php">
                <i class="fa-solid fa-house me-2"></i>Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='my-leads.php') echo 'bg-primary'; ?>" href="my-leads.php">
                <i class="fa-solid fa-users me-2"></i>My Leads
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-add-call.php') echo 'bg-primary'; ?>" href="counselor-add-call.php">
                <i class="fa-solid fa-phone me-2"></i>Call Records
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='followups.php') echo 'bg-primary'; ?>" href="followups.php">
                <i class="fa-solid fa-calendar-check me-2"></i>Follow-Ups
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-admission.php') echo 'bg-primary'; ?>" href="counselor-admission.php">
                <i class="fa-solid fa-graduation-cap me-2"></i>Admission
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='counselor-reports.php') echo 'bg-primary'; ?>" href="counselor-reports.php">
                <i class="fa-solid fa-chart-bar me-2"></i>My Performance
            </a>
        </li>

    </ul>

    <!-- LOGOUT AT BOTTOM -->
<div style="position:absolute; bottom:20px; left:0; right:0; padding:0 8px;">
    <a href="login.php" class="sidebar-logout"
       onclick="return confirm('👋 Are you sure you want to logout?\n\nYour session will be ended.\nSee you soon! 😊')">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

</div>