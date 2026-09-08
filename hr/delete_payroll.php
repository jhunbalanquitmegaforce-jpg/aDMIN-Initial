<?php
session_start();

include("../config.php");
include("../admin/includes/audit_log.php");

if(!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id']) && is_numeric($_GET['id'])){

    $id = (int) $_GET['id'];

    // Get payroll record first
    $stmt = mysqli_prepare(
        $con,
        "SELECT payroll.*, guards.firstname, guards.lastname
         FROM payroll
         LEFT JOIN guards ON payroll.guard_id = guards.id
         WHERE payroll.id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $payroll = mysqli_fetch_assoc($result);

    if(!$payroll){
        die("Payroll record not found.");
    }

    // Approved payroll cannot be deleted
    if($payroll['status'] === 'Approved'){
        header("Location: payroll.php?error=approved_locked");
        exit();
    }

    // Delete payroll
    $guard_name = $payroll['firstname'] . " " . $payroll['lastname'];

    $stmt = mysqli_prepare(
        $con,
        "DELETE FROM payroll WHERE id = ? AND status != 'Approved'"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if(mysqli_stmt_execute($stmt)){

        addAuditLog(
            $con,
            $_SESSION['user_id'],
            "DELETED Payroll",
            "Payroll",
            "Deleted payroll for {$guard_name}"
        );

        header("Location: payroll.php?success=deleted");
        exit();

    }else{

        echo "<div class='alert alert-danger'>
            Error deleting payroll.
        </div>";
    }

}else{

    header("Location: payroll.php");
    exit();
}
?>