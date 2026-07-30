<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$user_id = $_SESSION['user_id'];
$query = mysqli_query($con, "SELECT * FROM users Where id='$user_id'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $hashed_password = password_hash($new, PASSWORD_DEFAULT);
    $confirm = $_POST['confirm_password'];

    $query = mysqli_query($con, "SELECT password FROM users WHERE id='$user_id' ");
    $row = mysqli_fetch_assoc($query);

    if(!password_verify($current, $row['password'])) {
        echo "<script>alert('Current password is incorrect.');</script>";
    }elseif ($new !=$confirm){
        echo "<script>alert('New passwords do not match.');</script>";
    }else{
        mysqli_query($con, "UPDATE users SET password='$hashed_password' WHERE id='$user_id'");

        echo "<script>alert('Password changed successfully.');
        window.location='account_settings.php';
            </script>";
    }
}
if(isset($_POST['update_profile'])){
    $profile_picture = $user['profile_picture'];
    if(!empty($_FILES['profile_picture']['name'])){
        $profile_picture = time() . "_" . basename($_FILES['profile_picture']['name']);
        move_uploaded_file(
            $_FILES['profile_picture']['tmp_name'],
            "../assets/uploads/" . $profile_picture
        );
    }
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $update = mysqli_query($con, "UPDATE users SET fullname='$fullname', email='$email', profile_picture='$profile_picture' WHERE id='$user_id'");
    if ($update){
        echo "<script>
        alert('Profile update successfully.');
        window.location='account_settings.php';
        </script>";
    }else{
        echo "<script>alert('Update failed.');</script>";
    }
}

?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Account Settings</h2>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
            <label>Full Name</label>
            <input type="text"
            name="fullname"
            class="form-control"
            value="<?php echo htmlspecialchars($user['fullname']); ?>">
            </div>
            <div class="mb-3">
            <label>Email</label>
            <input type="email"
            name="email"
            class="form-control"
            value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="mb-3">
                <label>Profile Pciture</label>
                <input type="file" name="profile_picture" class="form-control">
            </div>
            <?php if (!empty($user['profile_picture'])) { ?>
            <img src="../assets/uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
            width="120"
            class="img-thumbnail mt-2">
            <?php } ?>
            <button class="btn btn-primary" name="update_profile">
                Update Profile
            </button>
        </form>
        <hr>
        <h4>Change Password</h4>
        <form method="POST">
            <div class="mb-3">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required> 
            </div>
            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required> 
            </div>
            <div class="mb-3">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required> 
            </div>
            <button type="submit" name="change_password" class="btn btn-danger">
                Change Password
            </button>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>