<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("includes/audit_log.php");

$id = $_GET['id'];
$stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $role_id = trim($_POST['role_id']);
    $status = trim($_POST['status']);
$sql = "UPDATE users SET role_id=?, fullname=?, email=?, username=?, status=? WHERE id=?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "issssi", $role_id, $fullname, $email, $username, $status, $id);
    if(mysqli_stmt_execute($stmt)){  
           addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Updated User",
            "User Management",
            "Updated user: $fullname"
        );   
        echo "<script> alert('User updated successfully!'); window.location='users.php';</script>";
    }else{
        echo"<div class='alert alert-danger'> Update failed</div>";
    }
}
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container mt-4">
        <h2>Edit User</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role_id" class="form-select">
                    <?php $roles = mysqli_query($con, "SELECT * FROM roles");
                    while($role = mysqli_fetch_assoc($roles)){
                        ?>
                        <option value="<?php echo $role['id'];?>"
                        <?php if($role['id']==$user['role_id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Active"
                        <?php if($user['status']=="Active") echo "selected";?>>
                        Active
                    </option>
                    <option value="Inactive"
                        <?php if($user['status']=="Inactive") echo "selected";?>>
                        Inactive
                    </option>
                </select>
            </div>
            <button class="btn btn-success" name="update">
                Update User
            </button>
            <a href="users.php" class="btn btn-secondary">
                Cancel
            </a>
        </form> 
    </div>
</div>
<?php include("includes/footer.php"); ?>