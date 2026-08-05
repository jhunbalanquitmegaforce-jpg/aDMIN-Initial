<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../config.php");
include("includes/audit_log.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT detachment_name FROM detachments where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $detachments = mysqli_fetch_assoc($result);
    $detachment_name = $detachments['detachment_name'];

$stmt = mysqli_prepare($con, "DELETE FROM detachments WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id );
if(mysqli_stmt_execute($stmt)){
           addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Deleted Detachment",
            "Detachment Management",
            "Deleted detachment: $detachment_name"
        );
        header("Location: detachments.php?success=deleted");
        exit();
    }else{
        echo "Error deleting detachment.";
    }
}else{
    header("Location: detachments.php");
    exit();
}
?>
