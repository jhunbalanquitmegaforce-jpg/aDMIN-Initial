<?php
include("includes/header.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit();
}
$user_id = (int) $_GET['id'];
$stmt = mysqli_prepare(
    $con, "SELECT role_id FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: users.php");
    exit();
}
if ((int)$user['role_id'] === 1){
    header("Location: users.php?error=admin_delete");
    exit();
}
if ($user_id == $_SESSION['user_id']) {
    header("Location: users.php?error=self_delete");
    exit();
}
$stmt = mysqli_prepare($con, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
if (mysqli_stmt_execute($stmt)) {
    header("Location: users.php?success=deleted");
    exit();
}
header("Location: users.php?error=delete_failed");
exit();