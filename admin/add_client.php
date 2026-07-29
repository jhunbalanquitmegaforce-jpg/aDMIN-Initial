<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(isset($_POST['save'])){
    $client_name   = trim($_POST['client_name']);
    $contact_person= trim($_POST['contact_person']);
    $contact_no = trim($_POST['contact_no']);
    $email = trim($_POST['email']);
    $address = $_POST['address'];
    $status = $_POST['status'];
    //  $created_at = $_POST['created_at'];

    // cHECK IF USERNAME already exists
    $check = mysqli_prepare($con, "SELECT id FROM clients Where client_name = ?");
    mysqli_stmt_bind_param($check, "s", $client_name);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if(mysqli_stmt_num_rows($check) > 0){
        echo "<div class='alert alert-danger m-3'> Client Name aleady exists.</div>";
    }else{
        $profile_picture = "";
        if(!empty($_FILES['profile_picture']['name'])){
            $profile_picture = time() ."_" . $_FILES['profile_picture']['name'];
            move_uploaded_file($_FILES['profile_picture']['tmp_name'],
            "../assets/uploads/guards/" .$profile_picture);
            // {
            //     echo "Upload Success";
            // }else{
            //     echo "Upload Failed";
            // }
        }
        $sql = "INSERT INTO clients
        (client_name, contact_person, contact_no, email, address, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param( $stmt, "ssssss", $client_name,  $contact_person, $contact_no, $email, $address, $status);
        
        if(mysqli_stmt_execute($stmt)){
            header("Location: clients.php");
            exit();
        }else{
            echo "<div class='alert alert-danger m-3'>
            Error saving clients: ". mysqli_error($con)." </div>";
        }
    }
}
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Add New Client</h2>
        <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Client Name</label>
                <input type="text" name="client_name" class="form-control" required>
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
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
           <div class="col-md-6 mb-3">
                <label>Status</label>
               <select name="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Created</label>
                <input type="date" name="created_at" class="form-control" required>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-success">
            Save Client
        </button>
        <a href="guards.php" class="btn btn-secondary">
            Cancel
        </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>