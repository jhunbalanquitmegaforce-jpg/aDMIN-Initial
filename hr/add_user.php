<?php
include("includes/header.php");
?>

<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>

    <div class="main-content">
        <?php include("includes/topbar.php"); ?>
        <?php
        $success = "";
        $error = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $status = $_POST['status'];

        if ($fullname == "" || $email == "" || $password == ""){
            $error = "Please fill in all required fields.";
        }else{
            $check = mysqli_prepare($con, "SELECT id FROM users Where username = ? LIMIT 1");
            mysqli_stmt_bind_param($check, "s", $username);
            mysqli_stmt_execute($check);
            $checkResult = mysqli_stmt_get_result($check);
            if(mysqli_num_rows($checkResult) > 0){
            $error = "Username already exists.";
            }else{
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );
                $stmt    =  mysqli_prepare($con, "INSERT INTO users
                ( fullname, email, username, password, role_id, status) VALUES (?, ?, ?, ?, ?, ?)" ); 
                mysqli_stmt_bind_param( $stmt, "ssssii",  $fullname, $email, $username, $hashedPassword, $role_id, $status);
                if(mysqli_stmt_execute($stmt)){
                    $success = "User successfully added.";
        }else{
            $error = "Failed to add user.";
        }
    }
}
    }
?>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add User</h2>

        <a href="users.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
        </div>
        <?php if ($success != ""): ?>
            <div alert alert-success>
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            <?php if ($error != ""): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Fullname
                                </label>
                                <input type="text"
                                name="fullname"
                                class="form-control"
                                required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email"
                                name="email"
                                class="form-control"
                                required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Username
                                </label>
                                <input type="text"
                                name="username"
                                class="form-control"
                                required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password
                                </label>
                                <input type="password"
                                name="password"
                                class="form-control"
                                required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Role
                                </label>
                                <select
                                name="role_id"
                                class="form-select"
                                required>
                                <option value="">Select Role</option>
                                <option value="2">HR</option>
                                <option value="3">OIC</option>
                                <option value="4">Guard</option>
                                <option value="5">Client</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb3">
                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select" required>
                                    <option value="1">
                                        Active
                                    </option>
                                    <option value="0">
                                        Inactive
                                    </option>
                                </select>
                            </div>
                                    <div class="mt-3">
                                        <button type="submit"
                                        class="btn btn-primary">
                                        <i class="fa-solid fa-save"></i>
                                            Save User
                                    </button>
                                    <a href="users.php" class="btn btn-secondary">
                                        Cancel
                                    </a>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <?php include("includes/footer.php"); ?>
    </div>
</div>
 