<?php 
include('config.php');
$fullname = "System Administrator";
$email = "admin@megaforce.com";
$username = "admin";

// Change your preferred password
$password = password_hash("admin123", PASSWORD_DEFAULT);

$role_id = 1;
$sql = "INSERT INTO users (role_id, fullname, email, username, password) values(?,?,?,?,?)";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "issss", $role_id, $fullname, $email, $username, $password);

if(mysqli_stmt_execute($stmt)){
    echo "Admin account created successfully!";
}else{
    echo "Error: " .mysqli_error($con);
}
?>