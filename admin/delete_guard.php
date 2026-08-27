<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
include("../config.php");
include("includes/audit_log.php");
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = (int) $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT profile_picture, firstname, lastname FROM guards where id=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $guard = mysqli_fetch_assoc($result);
    if (!$guard) {
        header("Location: guards.php?error=not_found");
        exit();
    }
    $firstname = $guard['firstname'];
    $lastname = $guard['lastname'];
    
    if ($guard && $guard['profile_picture'] != "default.png"){
        $file = "../assets/uploads/guards/" . $guard['profile_picture'];
        if (file_exists($file)){
            unlink($file);
        }
}
$stmt = mysqli_prepare($con, "DELETE FROM guards WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
  if(mysqli_stmt_execute($stmt)){
           addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Deleted Guard",
            "Guard Management",
            "Deleted guard: $firstname $lastname"
        );
  
        header("Location: guards.php?success=deleted");
        exit();
    }else{
        echo "Error deleting guard." .mysqli_error($con);
    }
}else{
    header("Location: guards.php");
    exit();
}
?>
