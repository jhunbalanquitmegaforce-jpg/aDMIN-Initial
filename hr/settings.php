<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">
        <h2 class="mb-4">Settings</h2>

        <div class="row">

            <!-- General Settings -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>General Settings</h5>
                        <p>Manage company information.</p>
                        <a href="general_settings.php" class="btn btn-primary">
                            Open
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Account Settings</h5>
                        <p>Update your profile and password.</p>
                        <a href="account_settings.php" class="btn btn-success">
                            Open
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Security Settings</h5>
                        <p>Configure login and session security.</p>
                        <a href="security_settings.php" class="btn btn-warning">
                            Open
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>