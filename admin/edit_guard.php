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

$result = mysqli_stmt_get_result($stmt);
$guard = mysqli_fetch_assoc($result);

if(!$guard){
    die("Guard not found.");
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
                <label>Contact Number</label>
                <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($guard['contact_no']); ?>" required>
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