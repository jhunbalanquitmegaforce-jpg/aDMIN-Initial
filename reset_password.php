<?php
session_start();
include('config.php');

if (!isset($_SESSION['reset_user_id']) ||
!isset($_SESSION['reset_verified']) ||
$_SESSION['reset_verified'] !== true 
) {
    header("Location: login.php");
    exit;
}
$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if($new_password === '' || $confirm_password === ''){
        $error = "Please fill out all fields.";
    }elseif ($new_password !== $confirm_password){
        $error = "Password do not match.";
    }elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters.";
    }else{
        $user_id = $_SESSION['reset_user_id'];
        $hashed_password = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );
        $stmt = mysqli_prepare(
            $con, "UPDATE users SET password = ? WHERE id = ?"
        );

    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id
    );
    if (mysqli_stmt_execute($stmt)){
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_username']);
        unset($_SESSION['reset_otp_id']);
        unset($_SESSION['reset_verified']);
        unset($_SESSION['reset_success']);

        $success = "Password reset successfully.";
    }else{
        $error = "Unable to reset password. Please try again.";
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">     
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-0"  style="max-width: 450px; width: 100%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fa-solid fa-key fa-4x text-success"></i>
                <h2 class="mt-3">
                    Reset Password
                </h2>
                <p class="text-muted">
                    Create your new password
                </p>
            </div> 
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert aler-success text-center">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <div class="d-grid">
                        <a href="login.php" class="btn btn-success btn-lg">
                            Back to Login
                        </a>
                    </div>
                    <?php else: ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">
                                    New Password
                                </label>
                                <input type="password" name="new_password" class="form-control" placeholder="Enter your new password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Confirm Password
                                </label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your new password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Reset Password
                                </button>
                            </div>

                        </form>
                        <?php endif; ?>
                    </div>
                    </div>
                    </div>
</body>
</html>