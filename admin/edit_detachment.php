<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("includes/audit_log.php");

if(!isset($_GET['id'])){
    header("Location: detachments.php");
    exit();
}
$id = $_GET['id'];
$stmt = mysqli_prepare($con, "SELECT * FROM detachments Where id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$detachments = mysqli_fetch_assoc($result);

if(!$detachments){
    die("Detachment not found.");
}
if(isset($_POST['update'])){
    $detachment_name = trim($_POST['detachment_name']);
    $client_id = trim($_POST['client_id']);
    $address = trim($_POST['address']);
    $contact_person = trim($_POST['contact_person']);
    $contact_no = trim($_POST['contact_no']);
    $guards_required = trim($_POST['guards_required']);
    $status = $_POST['status'];
    
    $sql = "UPDATE detachments
            SET detachment_name=?,
            client_id=?,
            address=?,
            contact_person=?,
            contact_no=?,
            guards_required=?,
            status=?
            WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssssssi", $detachment_name, $client_id, $address, $contact_person, $contact_no, $guards_required, $status, $id);
if(mysqli_stmt_execute($stmt)){
    addAuditLog(
            $con, 
            $_SESSION['user_id'],
            "Updated Detachment",
            "Detachment Management",
            "Updated detachment: $detachment_name"
        );   
    header("Location: detachments.php");
    exit();
}else{
    echo mysqli_errno($con);
}
}
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Edit Detachment</h2>
        <form method="POST">
            <DIV class="mb-3">
                <label>Detachment Name</label>
                <input type="text" name="detachment_name" class="form-control" value="<?php echo htmlspecialchars($detachments['detachment_name']); ?>" required>
            </DIV>
            <div class="col-md-6 mb-3">
                <label>Client Name</label>
                <select name="client_id"  class="form-select" required>
                    <option value="">Select Client</option>
                    <?php $clients = mysqli_query($con, "SELECT id, client_name FROM  clients ORDER BY client_name");
                     
                     while($row = mysqli_fetch_assoc($clients)){
                        ?>
                        <option value="<?php echo $row['id'] ?>">
                            <?php echo htmlspecialchars($row['client_name']); ?>
                        </option>
                     <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($detachments['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($detachments['contact_person']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" value="<?php echo htmlspecialchars($detachments['contact_no']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Guard Required</label>
                <input type="text" name="guards_required" class="form-control" value="<?php echo htmlspecialchars($detachments['guards_required']); ?>" required>
            </div>
             <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Active" <?php if($detachments['status']=="Active") echo "selected"; ?>>
                        Active
                    </option>
                    <option value="Inactive" <?php if($detachments['status']=="Inactive") echo "selected"; ?>>
                        Inactive
                    </option>
                </select>
            </div>
            <button type="submit"   name="update" class="btn btn-success">
                Update client
            </button>
            <a href="detachments.php" class="btn btn-secondary">
                Cancel
            </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>