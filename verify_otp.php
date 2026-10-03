<?php
session_start();
include 'config.php';
if(!isset($_SESSION['reset_user_id'], $_SESSION['reset_otp_id'])){
    header("Location: login.php");
    exit;
}
$error = "";
if ($_SERVER['REQUEST_METHOD'] === "POST"){
    $otp = trim($_POST['otp'] ?? '');
    if(!preg_match('/^\d{6}$/', $otp)) {
        $error = "Please enter the 6-digit OTP.";
    }else{
        $otp_id = $_SESSION['reset_otp_id'];
        $user_id = $_SESSION['reset_user_id'];

        $stmt = mysqli_prepare(
            $con,
            "SELECT id FROM password_resets
            WHERE id = ?
            AND user_id = ?
            AND otp = ?
            AND expires_at >= NOW()
            AND verified = 0
            LIMIT 1"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "iis",
            $otp_id,
            $user_id,
            $otp
        );

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) === 1) {
            $update = mysqli_prepare($con,
            "UPDATE password_resets SET verified = 1 WHERE id = ?");
            mysqli_stmt_bind_param($update, "i", $otp_id);
            mysqli_stmt_execute($update);

            $_SESSION['reset_verified'] = true;
            header("Location: reset_password.php");
            exit;
        }else{
            $error = "Invalid or expired OTP.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0" style="max-width: 450px; width: 100%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fa-solid fa-shield-halved fa-4x text-success"></i>
                <h2 class="mt-3">Verify OTP</h2>
                <p class="text-muted">
                    Enter the 6-digit OTP sent to your registered email.
                </p>
            </div>
            <?php if ($error): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">
                            OTP Code
                        </label>
                        <input type="text"
                        name="otp" class="form-control form-control-lg text center"
                        placeholder="Enter 6-digit OTP"
                        maxlength="6"
                        inputmode="numeric"
                        autocomplete="one-time-code" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            Verify OTP
                        </button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="login.php">
                        Back to Login
                    </a>
                </div>
        </div>
    </div>
</div>
</body>
</html>