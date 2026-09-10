<?php
$pageTitle = "Login";
include 'includes/header.php';
include 'includes/navbar.php';
?>


<section class="login-section d-flex align-items-center">
 <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="login-flip-card">
                <div class="login-flip-inner">

                    <div class="login-flip-front card shadow-lg border-0 login-card"> 
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fa-solid fa-shield-halved fa-4x text-success"></i>
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
                                    <button class="btn btn-success btn-lg">
                                        Login
                                    </button>
                                </div>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a href="#" onclick="flipLogin(event)">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="login-flip-back card shadow-lg border-0 login-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fa-solid fa-key fa-4x text-success"></i>
                                <h2 class="mt-3">Reset Password</h2>
                                <p class="text-muted">
                                    Register your new password
                                </p>
                            </div>
                            <form action="reset_password.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Username
                                    </label>
                                    <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        New Password
                                    </label>
                                    <input type="password" class="form-control" name="new_password" placeholder="Enter your new password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Confirm Password
                                    </label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm your new password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                            <hr>
                            <div class="text-center"> 
                                <a href="#" onclick="flipLogin(event)">
                                    Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>  
             </div>
        </div>
    </div>
 </div>
</section>

<script>
    function flipLogin(event){
        event.preventDefault();
        document.querySelector('.login-flip-card').classList.toggle('flipped');
    }
</script>
<style>
    .login-flip-card{
        width: 100%;
        perspective: 1000px;
    }
    .login-flip-inner{
        display: grid;
        width: 100%;
        transition: transform 0.7s ease;
        transform-style: preserve-3d;
    }
    .login-flip-front, .login-flip-back{
        grid-area: 1/1;
        width: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .login-flip-back{
        transform: rotateY(180deg);
    }
.login-flip-card.flipped .login-flip-inner{
    transform: rotateY(180deg);
}
</style>
<?php include 'includes/footer.php'; ?>