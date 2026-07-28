<?php 
include("../config.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if(mysqli_stmt_execute($stmt)){
        header("Location: users.php?success=deleted");
        exit();
    }else{
        echo "Error deleting user.";
    }
}else{
    header("Location: users.phg");
    exit();
}
?>