<?php
session_start();
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

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
$reset_id = mysqli_insert_id($con);
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host        = 'smtp.gmail.com';
    $mail->SMTPAuth    = true;
    $mail->Username    ='jhunbalanquit.megaforce@gmail.com';

    $mail->Password      = getenv('MEGAFORCE_GMAIL_APP_PASSWORD');
    $mail->SMTPSecure    = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port          = 587;

    $mail->setFrom(
        'jhunbalanquit.megaforce@gmail.com',
        'Megaforce Security Agency'
    );

    $mail->addAddress(
        $user['email'],
        $user['fullName']
    );
    $mail->isHTML(true);
    $mail->Subject = 'Your Megaforce Password Reset OTP';
    $mail->Body = '
    <H2>Password Reset Request</h2>

    <p>Hello ' . htmlspecialchars($user['fullName']) . ',</p>
    <p>Your password reset OTP is; </p>
    <h1 style="letter-spacing: 5px;">' . $otp . '</h1>
    <p>This OTP will expire in <strong> 5 minutes</strong>.</p>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p>Regards,<br>
    Megaforce</p>
    ';

    $mail->send();
    
$_SESSION['reset_user_id'] = $user['id'];
$_SESSION['reset_username'] = $username;
$_SESSION['reset_otp_id'] = $reset_id;

$_SESSION['reset_success'] = "OTP sent generate successfully to your registered email.";
header("Location: verify_otp.php");
exit;

} catch (Exception $e) {
    $delete = mysqli_prepare(
        $con,
        "DELETE FROM password_resets WHERE id = ?"
    );

    mysqli_stmt_bind_param($delete, "i", $reset_id);
    mysqli_stmt_execute($delete);

    $_SESSION['reset_error'] =
        "Unable to send the OTP email. Please try again.";
    header("Location: login.php");
    exit;
}    
?>
