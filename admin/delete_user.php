<?php 
session_start();
include("../config.php");
include("includes/audit_log.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $getUser = mysqli_prepare($con, "SELECT fullname FROM users Where id=?");
    mysqli_stmt_bind_param($getUser, "i", $id);
    mysqli_stmt_execute($getUser);
    $result = mysqli_stmt_get_result($getUser);
    $user = mysqli_fetch_assoc($result);
    $fullname = $user['fullname'];
    
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if(mysqli_stmt_execute($stmt)){
           addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Deleted User",
            "User Management",
            "Deleted user: $fullname"
        );
        header("Location: users.php?success=deleted");
        exit();
    }else{
        echo "Error deleting user.";
    }
}else{
    header("Location: users.php");
    exit();
}
?>