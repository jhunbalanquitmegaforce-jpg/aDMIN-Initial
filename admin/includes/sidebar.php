<?php $company = mysqli_fetch_assoc( mysqli_query($con, "SELECT company_name, logo FROM system_settings LIMIT 1"));
?>

<div class="sidebar">
    <div class="text-center py3">
    <img src="../assets/uploads/<?php echo $company['logo']; ?>" 
    width="70"
    class="rounded-circle mb-2">
    <h6 class="text-white">
        <?php echo htmlspecialchars($company['company_name']); ?>
    </h6>
</div>
    <!-- <h3 class="text-center py-3">
        <i class="fa-solid fa-shield-halved"></i>
        SAMS
    </h3> -->
    <a href="dashboard.php">
        <i class="fa-solid fa-gauge"></i>
        Dashboard
    </a>
    <a href="users.php">
        <i class="fa-solid fa-users"></i>
        Users
    </a>
    <a href="guards.php" class="nav-link">
        <i class="fa-solid fa-user-shield"></i>
        Guards
    </a>
     <a href="clients.php">
        <i class="fa-solid fa-building"></i>
        Clients
    </a>
    <a href="detachments.php">
        <i class="fa-solid fa-map-location-dot"></i>
        Detachments
    </a>
     <a href="settings.php">
        <i class="fa-solid fa-gear"></i>
        Settings
    </a>
     <a href="audit_logs.php">
        <i class="fas fa-history"></i>
        Audit Logs
    </a>
    <a href="attendance.php" class="nav-link">
        <i class="fas fa-calendar-check"></i>
        Attendance
    </a>
     <a href="../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
    </a>
</div>