<?php
session_start();
include("../config.php");
include("includes/audit_log.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT client_name FROM clients where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $client = mysqli_fetch_assoc($result);
    $client_name = $client['client_name'];

$stmt = mysqli_prepare($con, "DELETE FROM clients WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
if(mysqli_stmt_execute($stmt)){
           addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Deleted Client",
            "Client Management",
            "Deleted client: $client_name"
        );
        header("Location: clients.php?success=deleted");
        exit();
    }else{
        echo "Error deleting client.";
    }
}else{
    header("Location: clients.php");
    exit();
}
?>