<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ .  "/../../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /sams/login.php?error=session_expired");
    exit();
}
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    die("Access Denied!");
}
?>