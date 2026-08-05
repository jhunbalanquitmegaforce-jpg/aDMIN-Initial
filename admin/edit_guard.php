<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("includes/audit_log.php");

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
if(isset($_POST['update'])){
    $employee_no = trim($_POST['employee_no']);
    $firstname = trim($_POST['firstname']);
    $middlename= trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $contact_no = trim($_POST['contact_no']);
    $detachment_id = $_POST['detachment_id'];
    $sql = "UPDATE guards
            SET employee_no=?,
            firstname=?,
            middlename=?,
            lastname=?,
            gender=?,
            status=?,
            contact_no=?,
            detachment_id=?
            WHERE id=?";
$check = mysqli_prepare($con, "SELECT d.guards_required, COUNT(g.id) as assigned FROM detachments d LEFT JOIN guards g ON d.id = g.detachment_id WHERE d.id = ? GROUP BY d.id");
mysqli_stmt_bind_param($check, "i", $detachment_id);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);
$data = mysqli_fetch_assoc($result);
if ($detachment_id != "" && $guard['detachment_id'] != $detachment_id) {
    if ($data['assigned'] >= $data['guards_required']) {
        echo "<script>
        alert('This detachment is already fully staffed.');
        window.history.back();
        </script>";
        exit();
    }
}

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssssssii", $employee_no, $firstname, $middlename, $lastname, $gender, $status, $contact_no, $detachment_id, $id);
$getDet = mysqli_prepare($con, "SELECT detachment_name FROM detachments WHERE id = ?");
mysqli_stmt_bind_param($getDet, "i", $detachment_id);
mysqli_stmt_execute($getDet);
$res = mysqli_stmt_get_result($getDet);
$det = mysqli_fetch_assoc($res);
$detachment_name = $det['detachment_name'];

if(mysqli_stmt_execute($stmt)){
    addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Assigned Guard",
            "Guard Management",
            "Assigned guard: {$firstname} {$lastname} to {$detachment_name}"
        );
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
            <div class="mb-3">
                <label>Assigned Detachment</label>
                <select name="detachment_id" class="form-select">
                    <option value="">Select Detachment</option>
                    <?php
                    $detachments = mysqli_query($con, "SELECT id, detachment_name FROM detachments order by detachment_name asc");
                    while($d = mysqli_fetch_assoc($detachments)){
                    ?>
                    <option value="<?php echo $d['id']; ?>" <?php if($guard['detachment_id']==$d['id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($d['detachment_name']); ?>
                    </option>
                    <?php } ?>
                </select>
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