<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(isset($_POST['save'])){
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];
    $status = $_POST['status'];

    // cHECK IF USERNAME already exists
    $check = mysqli_prepare($con, "SELECT id FROM users Where username = ?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if(mysqli_stmt_num_rows($check) > 0){
        echo "<div class='alert alert-danger m-3'> Username aleady exist.</div>";
    }else{
        $sql = "INSERT INTO users
        (role_id, fullname, email, username, password, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param( $stmt, "isssss", $role_id, $fullname, $email, $username, $password, $status);
        if(mysqli_stmt_execute($stmt)){
            header("Location: users.php");
            exit();
        }else{
            echo "<div class='alert alert-danger m-3'>
            Error saving user </div>";
        }
    }
}
?>

<div class="main-content">
    <?php include("includes/topbar.php") ?>
    <div class="container-fluid mt-4">
        <h2>Add New User</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role_id" class="form-select" required>
                <?php 
                $roles = mysqli_query($con, "SELECT * FROM roles ORDER BY  role_name ASC");
                While ($role = mysqli_fetch_assoc($roles)){

             ?>
             <option value="<?php echo $role['id']; ?>">
                <?php echo htmlspecialchars($role['role_name']); ?>
             </option>
             <?php }?>
            </select>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" name="save" class="btn btn-success">
                Save User
            </button>
            <a href="users.php" class="btn btn-secondary">
                Cancel
            </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>