<?php
$pageTitle = "Services";
include 'includes/header.php';
include 'includes/navbar.php';
?>


<section class="login-section d-flex align-items-center">
 <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 login-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-shield-halved fa-4x text-warning"></i>
                        <h2 class="mt-3">Welcome Back</h2>
                        <p class="text-muted">
                            Login to your account
                        </p>
                    </div>
                    <form action="authenticate.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">
                                Username
                            </label>
                            <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-warning btn-lg">
                                Login
                            </button>
                        </div>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a href="#">
                            Forgot Password?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

</section>
<?php include 'includes/footer.php'; ?>