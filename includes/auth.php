<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
if ($_SESSION['role_id'] != 1){
    die("Access Denied!");
}
include_once("../config.php");
$stmt = mysqli_prepare($con, "SELECT session_timeout FROM security_settings WHERE id = 1 LIMIT 1");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$settings = mysqli_fetch_assoc($result);
$session_timeout = 30;

if ($settings && isset ($settings['session_timeout'])){
    $session_timeout = (int) $settings['session_timeout'];
}
$timeout_seconds = $session_timeout * 60;
if(isset($_SESSION['last_activity'])){
$inactive_time = time() - $_SESSION['last_activity'];
if ($inactive_time >= $timeout_seconds) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")){
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
        );
    }
    session_destroy();
    header("Location: ../login.php?error=session_expired");
    exit();
}
}
$_SESSION['last_activity'] = time();
?>