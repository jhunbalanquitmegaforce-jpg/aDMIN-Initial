<?php
session_start();

include("../config.php");
include("../admin/includes/audit_log.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Get client name before deleting
    $stmt = mysqli_prepare(
        $con,
        "SELECT client_name FROM clients WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $client = mysqli_fetch_assoc($result);

    if (!$client) {
        header("Location: clients.php");
        exit();
    }

    $client_name = $client['client_name'];

    // Delete client
    $stmt = mysqli_prepare(
        $con,
        "DELETE FROM clients WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        addAuditLog(
            $con,
            $_SESSION['user_id'],
            "Deleted Client",
            "Client Management",
            "Deleted client: $client_name"
        );

        header("Location: clients.php?success=deleted");
        exit();

    } else {

        echo "Error deleting client: " . mysqli_error($con);
    }

} else {

    header("Location: clients.php");
    exit();
}
?>