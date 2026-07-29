<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(isset($_POST['save'])){
    $detachment_name  = trim($_POST['detachment_name']);
    $client_id= trim($_POST['client_id']);
    $address = trim($_POST['address']);
    $contact_person = trim($_POST['contact_person']);
    $contact_no = trim($_POST['contact_no']);
    $guards_required = trim($_POST['guards_required']);
    $status = $_POST['status'];
    

    // cHECK IF USERNAME already exists
    $check = mysqli_prepare($con, "SELECT id FROM detachments Where detachment_name= ?");
    mysqli_stmt_bind_param($check, "s", $detachment_name);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if(mysqli_stmt_num_rows($check) > 0){
        echo "<div class='alert alert-danger m-3'> Detachment Name aleady exists.</div>";
    }
        $sql = "INSERT INTO detachments
        (detachment_name, client_id, address, contact_person, contact_no, guards_required, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $detachment_name,  $client_id, $address, $contact_person, $contact_no, $guards_required, $status);
        
        if(mysqli_stmt_execute($stmt)){
            header("Location: detachments.php");
            exit();
        }else{
            echo "<div class='alert alert-danger m-3'>
            Error saving Detachment: ". mysqli_error($con)." </div>";
        }
    }

?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Add New Detachment</h2>
        <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Detachment Name</label>
                <input type="text" name="detachment_name" class="form-control" required>
            </div>
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
            <div class="col-md-6 mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Contact Person</label>
                <input type="text" name="contact_person" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Guard Required</label>
                <input type="text" name="guards_required" class="form-control" required>
            </div>
           <div class="col-md-6 mb-3">
                <label>Status</label>
               <select name="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-success">
            Save Client
        </button>
        <a href="detachments.php" class="btn btn-secondary">
            Cancel
        </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>