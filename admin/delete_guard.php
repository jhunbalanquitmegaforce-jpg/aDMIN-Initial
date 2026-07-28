<?php
include("../config.php");

if(isset ($_GET['id'])){
    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "DELETE FROM guards where id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if(mysqli_stmt_execute($stmt)){
        header("Location: guards.php");
        exit();
    }else{
        echo "Delete failed";
    }
}else{
    header("Location: guards.php");
    exit();
}
?>
