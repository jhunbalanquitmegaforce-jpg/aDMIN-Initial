<?php
function addAuditLog($con, $user_id, $action, $module, $details = "") {
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = mysqli_prepare($con, "INSERT INTO audit_logs (user_id, action, module, details, ip_address) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $action, $module, $details, $ip);
    if (!mysqli_stmt_execute($stmt)) {
        die(mysqli_stmt_error($stmt));
    }
}
?>


