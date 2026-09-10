<?php
include('config.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password !== $confirm_password){
        die("Error: Passwords do not match. <a href='login.php'>Back to Login</a>");
    }

    $sql = "SELECT id FROM users WHERE username = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) == 0){
        die("Error: Username not found. <a href='login.php'>Back to Login</a>");
    }

    mysqli_stmt_close($stmt);

    $password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = ? WHERE username = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $password, $username);

    if(mysqli_stmt_execute($stmt)){
        echo "Password reset successfully! <a href='login.php'>Back to Login</a>";
    }else{
        echo "Error updating password: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}
?>
