<?php
session_start();
include("config.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

$sql = "SELECT * FROM users WHERE username = ? LIMIT 1"    ;

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    if(password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role_id'] = $user['role_id'];

    switch ($user['role_id']){
        case 1:
            header("Location: admin/dashboard.php");
            exit();
        case 2:
            header("Location: hr/dashboard.php");
            exit();
        case 3:
            header("Location: oic/dashboard.php");
            exit();
        case 4:
            header("Location: employee/dashboard.php");
            exit();
        case 5:
            header("Location: client/dashboard.php");
            exit();
        default:
            echo "Invalid user role.";
    }
    }else{
        echo "Incorrect password.";
    }
}
}
    ?>