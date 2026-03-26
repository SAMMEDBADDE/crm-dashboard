<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>



<div class="col-md-2 bg-dark text-white p-3" style="min-height:100vh; position:relative;">
    <h5 class="text-center mb-1" style="font-size:15px; font-weight:700; letter-spacing:0.3px;">⚙️ CRM Admin</h5>
    <p class="text-center text-muted" style="font-size:11px; margin-bottom:16px;">Admin Panel</p>
    <hr class="mt-0">

    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='admin-dashboard.php') echo 'bg-primary'; ?>" href="admin-dashboard.php">
                <i class="fa-solid fa-house me-2"></i>Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-users.php') echo 'bg-primary'; ?>" href="manage-users.php">
                <i class="fa-solid fa-user-gear me-2"></i>Manage Users
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-leads.php') echo 'bg-primary'; ?>" href="manage-leads.php">
                <i class="fa-solid fa-users me-2"></i>Manage Leads
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='manage-list.php') echo 'bg-primary'; ?>" href="manage-list.php">
                <i class="fa-solid fa-list me-2"></i>Manage List
            </a>
        </li>
        <li class="nav-item">
    <a class="nav-link text-white <?php if($currentPage=='import_leads.php') echo 'bg-primary'; ?>" href="import_leads.php">
        <i class="fa-solid fa-file-import me-2"></i>Import Leads
    </a>
</li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='reports.php') echo 'bg-primary'; ?>" href="reports.php">
                <i class="fa-solid fa-chart-bar me-2"></i>Reports
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='followup.php') echo 'bg-primary'; ?>" href="followup.php">
                <i class="fa-solid fa-calendar-check me-2"></i>Follow-Ups
            </a>
        </li>
        <li class="nav-item">
    <a class="nav-link text-white <?php if($currentPage=='manage-courses.php') echo 'bg-primary'; ?>" href="manage-courses.php">
        <i class="fa-solid fa-book me-2"></i>Manage Courses
    </a>
</li>
        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='admission.php') echo 'bg-primary'; ?>" href="admission.php">
                <i class="fa-solid fa-graduation-cap me-2"></i>Admission
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white <?php if($currentPage=='call-records.php') echo 'bg-primary'; ?>" href="call-records.php">
                <i class="fa-solid fa-phone me-2"></i>Call Records
            </a>
        </li>

    </ul>

    <!-- LOGOUT AT BOTTOM -->
    <div style="position:absolute; bottom:20px; left:0; right:0; padding:0 12px;">
        <a class="nav-link text-danger d-flex align-items-center gap-2 p-2 rounded"
           href="login.php"
           onclick="return confirm('👋 Are you sure you want to logout?\n\nYour session will be ended.\nSee you soon! 😊')"
           style="font-size:14px; font-weight:600; transition:0.2s;">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>

</div>