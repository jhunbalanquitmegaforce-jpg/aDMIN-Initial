<?php
include("includes/header.php");
?>
<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>
    <div class="main-content">
    <?php include("includes/topbar.php"); ?>

    <?php
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: user.php");
        exit();
    }
    $user_id = (int) $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT id, fullname, email, username, role_id, status
     FROM users WHERE id = ? limit 1 ");
     mysqli_stmt_bind_param($stmt, "i", $user_id);
     mysqli_stmt_execute($stmt);
    
     $result = mysqli_stmt_get_result($stmt);
     $user = mysqli_fetch_assoc($result);

     if (!$user) {
        header("Location: users.php");
        exit();
     }
     $error = "";
     $success = "";

     if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $role_id = (int) $_POST['role_id'];
    $status = (int) $_POST['status']; 

    if (!in_array($role_id, [2, 3, 4, 5])) {
        $error = "Invalid role selected.";
    }elseif (
        $fullname == "" ||
        $email == "" ||
        $username == ""
    ){
        $error = "Please fill out all required feilds.";
    }else{
        $check = mysqli_prepare(
            $con,
            "SELECT id
            FROM users
            WHERE username = ?
            AND id != ? LIMIT 1"
        );
        mysqli_stmt_bind_param($check, "si", $username, $user_id);

        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);
        if (mysqli_num_rows($checkResult) > 0){
            $error = "Username already exists.";
        } else {
            $update = mysqli_prepare($con, "UPDATE users SET fullname = ?,
            email = ?, username = ?, role_id = ?, status = ?, WHERE id = ?");

            mysqli_stmt_bind_param($update, "sssiii", $fullname, $email, $username, $role_id, $status, $user_id);
            if (mysqli_stmt_execute($update)) {
                $success = "User successfully updated.";

                $user['fullname'] = $fullname;
                $user['email'] = $email;
                $user['username'] = $username;
                $user['role_id'] = $role_id;
                $user['status'] = $status;
            }else {
                $error = "Failed to update user.";
            }
        }
    }
     
}
?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit User</h2>
    <a href="users.php" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        Back
    </a>
    </div>
    <?php if ($success != ""): ?>
        <div class="alert alert-success">
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
                                    Full Name
                                </label>
                                <input type="text" 
                                name="fullname"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email" 
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Username
                                </label>
                                <input type="text" 
                                name="username"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                   Role
                                </label>
                                <select 
                                name="role_id"
                                class="form-control"
                                required>
                                <option value="2"
                                <?php echo ($user['role_id'] == 2) ? 'selected' : ''; ?>>
                                HR
                                </option>
                                <option value="3"
                                <?php echo ($user['role_id'] == 3) ? 'selected' : ''; ?>>
                                OIC
                                </option>
                                <option value="4"
                                <?php echo ($user['role_id'] == 4) ? 'selected' : ''; ?>>
                                Guard
                                </option>
                                <option value="5"
                                <?php echo ($user['role_id'] == 5) ? 'selected' : ''; ?>>
                                Client
                                </option>
                            </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>
                                <select name="status" class="form-select" required>
                                    <option value="1"
                                    <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>>
                                    Active
                                    </option>
                                    
                                    <option value="0"
                                    <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>>
                                    Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button 
                            type="submit"
                            class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                            Update User
                         </button>
                         <a href="users.php"
                         class="btn btn-secodary">
                        Cancel
                        </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include("includes/footer.php"); ?>
    </div>
</div>