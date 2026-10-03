<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: login.php");
    exit;
}
$username = trim($_POST['username'] ?? '');

if ($username === ''){
    $_SESSION['reset_error'] = "Please enter your username.";
    header("Location: login.php");
    exit;
}
$stmt = mysqli_prepare($con, "SELECT id, fullname, email FROM users WHERE username = ? AND status = 'Active' LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    $_SESSION['reset_error'] = "Username not found.";
    header("Location: login.php");
    exit;
}
if (empty($user['email'])) {
    $_SESSION['reset_error'] = "No email address is registered for this account.";
    header("Location: login.php");
    exit;
}
$otp = str_pad(random_int(0, 999999), 6, '0',   STR_PAD_LEFT);

// $expires_at = date("Y-m-d H:i:s", time() + 300);
$stmt = mysqli_prepare($con, "INSERT INTO password_resets (user_id, otp, expires_at) VALUES(?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))"
);

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $user['id'],
    $otp,
);
if (!mysqli_stmt_execute($stmt)){
    $_SESSION['reset_error'] = "Unable to generate OTP. Please try again.";
    header("Location: login.php");
    exit;
}
$_SESSION['reset_user_id'] = $user['id'];
$_SESSION['reset_username'] = $username;
$_SESSION['reset_otp_id'] = mysqli_insert_id($con);

$_SESSION['reset_success'] = "OTP generate successfully:" . $otp;
header("Location: verify_otp.php");
exit;
?>