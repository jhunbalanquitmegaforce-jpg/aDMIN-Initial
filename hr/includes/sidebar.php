

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
    
     <a href="dashboard.php">
       <i class="bi bi-speedometer2"></i>
         Dashboard
      </a>

         <a href="users.php">
        <i class="bi bi-people"></i>
        Users
        </a>
    
    <a href="guards.php">
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
    <a href="attendance.php">
        <i class="fas fa-calendar-check"></i>
        Attendance
    </a>
    <a href="payroll.php">
        <i class="bi bi-cash"></i>
        Payroll
    </a> 
    <a href="payroll_reports.php">
        <i class="bi bi-bar-chart"></i>
       Payroll Reports
    </a>
    <a href="settings.php">
        <i class="fa-solid fa-gear"></i>
        Settings
    </a>
     <a href="../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
    </a>
</div>