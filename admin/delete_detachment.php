<?php
include("../config.php");

    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT detachment_name FROM detachments where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $detachments = mysqli_fetch_assoc($result);

$stmt = mysqli_prepare($con, "DELETE FROM detachments WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: detachments.php");
exit();

?>
