<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(!isset($_GET['id'])){
    header("Location: clients.php");
    exit();
}
$id = $_GET['id'];
$stmt = mysqli_prepare($con, "SELECT * FROM clients Where id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$client = mysqli_fetch_assoc($result);

if(!$client){
    die("Client not found.");
}
if(isset($_POST['update'])){
    $client_name = trim($_POST['client_name']);
    $contact_person= trim($_POST['contact_person']);
    $contact_no = trim($_POST['contact_no']);
    $email= $_POST['email'];
    $address = trim($_POST['address']);
    $status = $_POST['status'];
    $created_at = trim($_POST['created_at']);
    $sql = "UPDATE clients
            SET client_name=?,
            contact_person=?,
            contact_no=?,
            email=?,
            address=?,
            status=?,
            created_at=?
            WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "sssssssi", $client_name, $contact_person, $contact_no, $email, $address, $status, $created_at, $id);
if(mysqli_stmt_execute($stmt)){
    header("Location: clients.php");
    exit();
}else{
    echo mysqli_errno($con);
}
}
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Edit Client</h2>
        <form method="POST">
            <DIV class="mb-3">
                <label>Client Name</label>
                <input type="text" name="client_name" class="form-control" value="<?php echo htmlspecialchars($client['client_name']); ?>" required>
            </DIV>
            <div class="mb-3">
                <label>Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($client['contact_person']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" value="<?php echo htmlspecialchars($client['contact_no']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($client['address']); ?>" required>
            </div>
             <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Active" <?php if($client['status']=="Active") echo "selected"; ?>>
                        Active
                    </option>
                    <option value="Inactive" <?php if($client['status']=="Inactive") echo "selected"; ?>>
                        Inactive
                    </option>
                </select>
            </div>
                <div class="mb-3">
                <label>Created</label>
                <input type="date" name="created_at" class="form-control" value="<?php echo htmlspecialchars($client['created_at']); ?>" required>
            </div>
            <button type="submit"   name="update" class="btn btn-success">
                Update client
            </button>
            <a href="clients.php" class="btn btn-secondary">
                Cancel
            </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>