<?php
session_start();
include("../config.php");
include("includes/audit_log.php");

if(!isset($_GET['id']) || !isset($_GET['status'])){
    header("Location: Payroll.php");
    exit();
}
$id = (int) $_GET['id'];
$new_status = $_GET['status'];

// status change allowed
if($new_status !== 'Checked' && $new_status !== 'Approved'){
    header("Location: payroll.php");
    exit();
}

// Get current payroll status
$stmt = mysqli_prepare($con, "SELECT payroll.*, guards.firstname, guards.lastname FROM payroll
LEFT JOIN  guards ON payroll.guard_id = guards.id WHERE payroll.id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$payroll = mysqli_fetch_assoc($result);

if(!$payroll) {
    die("Payroll record not found.");
}
$current_status = $payroll['status'];

//Prevent invalid status changes

if($new_status === 'Checked' && $current_status !=='Draft'){
    header("Location: payroll.php?error=invalid_status");
    exit();
}
if($new_status === 'Approved' && $current_status !=='Checked'){
    header("Location: payroll.php?error=invalid_status");
    exit();
}

//Update status
$stmt = mysqli_prepare($con, "UPDATE payroll SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $new_status, $id);

if(mysqli_stmt_execute($stmt)) {
    $guard_name = $payroll['firstname'] . " " . $payroll['lastname'];

    addAuditLog(
        $con,
        $_SESSION['user_id'],
        "Updated Payroll Status",
        "Payroll",
        "Changed payroll status for{$guard_name} from {$new_status}"
    );
    header("Location: payroll.php?success=status_updated");
    exit();
}else{
    echo "<div class='alert alert-danger'>
    Error updating payroll status.
    </div>";
}
?>