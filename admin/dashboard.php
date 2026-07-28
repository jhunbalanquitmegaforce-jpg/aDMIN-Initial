<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

//Count total users
$user_query = mysqli_query($con, "SELECT COUNT(*) AS total FROM users");
$user_result = mysqli_fetch_assoc($user_query);
$total_users = $user_result['total'];
?>


<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <h2><?php echo $total_users; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        <h5>Total Guards</h5>
                        <h2>0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-dark shadow">
                    <div class="card-body">
                        <h5>Total Clients</h5>
                        <h2>0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Total Reports</h5>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>   

<?php include("includes/footer.php"); ?>