<?php
include("../config.php");

    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT client_name FROM clients where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $client = mysqli_fetch_assoc($result);

$stmt = mysqli_prepare($con, "DELETE FROM clients WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: clients.php");
exit();

?>
