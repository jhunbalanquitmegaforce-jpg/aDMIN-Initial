<?php
include("../config.php");

    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT profile_picture FROM guards where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $guard = mysqli_fetch_assoc($result);
    
    if ($guard && $guard['profile_picture'] != "default.png"){
        $file = "../assets/uploads/guards/" . $guard['profile_picture'];
        if (file_exists($file)){
            unlink($file);
        }
}
$stmt = mysqli_prepare($con, "DELETE FROM guards WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: guards.php");
exit();

?>
