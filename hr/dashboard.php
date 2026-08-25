<?php
include ("includes/header.php");
?>
<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("includes/topbar.php"); ?>
        <div class="container-fluid mt-4">
        <h2>HR Dashboard</h2>
        <p class="text-muted">
            Welcome, <?php  echo htmlspecialchars($_SESSION['fullname']); ?>!
        </p>
        <div class="row g-3 mt-3">
        <div class="col-md-3 ">
            <a href="guards.php" class="text-decoration-none">
            <div class="card shadow-sm dashboard-card border-0 bg-secondary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                    <h6 class="text-muted mb-2">Total Guards</h6>
                    <h3 class="fw-bold text-dark mb-0">
                        <?php 
                        $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM guards");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total']; ?>
                    </h3>
                </div>
                <div class="dashboard-icon bg-secondary">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </div>
        </a>
    </div>

        <div class="col-md-3">
            <a href="clients.php" class="text-decoration-none">
            <div class="card shadow-sm dashboard-card border-0 bg-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                    <h6 class="text-muted mb-2">Total Clients</h6>
                    <h3 class="fw-bold text-dark mb-0">
                        <?php 
                        $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM clients");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total']; ?>
                    </h3>
                    </div>
                    <div class="dashboard-icon bg-success">
                    <i class="fa-solid fa-building"></i>
                </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="detachments.php" class="text-decoration-none">
            <div class="card shadow-sm dashboard-card border-0 bg-info">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                    <h6 class="text-muted mb-2">Detachments</h6>
                    <h3 class="fw-bold text-dark mb-0">
                        <?php 
                        $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM detachments");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total']; ?>
                    </h3>
                </div>
                <div class="dashboard-icon bg-info">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
            </div>
        </div>
        </a>
        </div>


        <div class="col-md-3">
            <a href="detachments.php" class="text-decoration-none">
            <div class="card shadow-sm dashboard-card border-0 bg-danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                    <h6 class="text-muted mb-2">Payroll Records</h6>
                    <h3 class="fw-bold text-dark mb-0">
                        <?php 
                        $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM payroll");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total']; ?>
                    </h3>
                </div>
                <div class="dashboard-icon bg-danger">
                    <i class="bi bi-cash"></i>
                </div>
            </div>
        </div>
        </a>
        </div>
        </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>