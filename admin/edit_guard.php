<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(!isset($_GET['id'])){
    header("Location: guards.php");
    exit();
}
$id = $_GET['id'];
$stmt = mysqli_prepare($con, "SELECT * FROM guards Where id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guard = mysqli_fetch_assoc($result);

if(!$guard){
    die("Guard not found.");
}
if(isset($_POST['update'])){
    $employee_no = trim($_POST['employee_no']);
    $firstname = trim($_POST['firstname']);
    $middlename= trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $contact_no = trim($_POST['contact_no']);
    $sql = "UPDATE guards
            SET employee_no=?,
            firstname=?,
            middlename=?,
            lastname=?,
            gender=?,
            status=?,
            contact_no=?
            WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssssssi", $employee_no, $firstname, $middlename, $lastname, $gender, $status, $contact_no, $id);
if(mysqli_stmt_execute($stmt)){
    header("Location: guards.php");
    exit();
}else{
    echo mysqli_errno($con);
}
}
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Edit Guard</h2>
        <form method="POST">
            <DIV class="mb-3">
                <label>Employee Number</label>
                <input type="text" name="employee_no" class="form-control" value="<?php echo htmlspecialchars($guard['employee_no']); ?>" required>
            </DIV>
            <div class="mb-3">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($guard['firstname']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Middle Name</label>
                <input type="text" name="middlename" class="form-control" value="<?php echo htmlspecialchars($guard['middlename']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($guard['lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male" <?php if($guard['gender']=="Male") echo "selected"; ?>>
                        Male
                    </option>
                    <option value="Female" <?php if($guard['gender']=="Female") echo "selected"; ?>>
                        Female
                    </option>
                </select>
            </div>
             <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Active" <?php if($guard['status']=="Active") echo "selected"; ?>>
                        Active
                    </option>
                    <option value="Inactive" <?php if($guard['status']=="Inactive") echo "selected"; ?>>
                        Inactive
                    </option>
                </select>
            </div>
                <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" value="<?php echo htmlspecialchars($guard['contact_no']); ?>" required>
            </div>
            <button type="submit"   name="update" class="btn btn-success">
                Update Guard
            </button>
            <a href="guards.php" class="btn btn-secondary">
                Cancel
            </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>