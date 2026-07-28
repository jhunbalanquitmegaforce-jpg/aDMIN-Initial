<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if(isset($_POST['save'])){
    $employee_no = trim($_POST['employee_no']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $gender = $_POST['gender'];
    $contact_no = trim($_POST['contact_no']);
    $date_hired = $_POST['date_hired'];
    $license_no = trim($_POST['license_no']);
    $license_expiry = $_POST['license_expiry'];
    $status = $_POST['status'];
    $profile_picture = $_FILES['profile_picture'];

    // cHECK IF USERNAME already exists
    $check = mysqli_prepare($con, "SELECT id FROM guards Where employee_no = ?");
    mysqli_stmt_bind_param($check, "s", $employee_no);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if(mysqli_stmt_num_rows($check) > 0){
        echo "<div class='alert alert-danger m-3'> Employee Number aleady exists.</div>";
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
        $sql = "INSERT INTO guards
        (employee_no, firstname, profile_picture, middlename, lastname, gender, contact_no, date_hired, license_no, license_expiry, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param( $stmt, "sssssssssss", $employee_no,  $firstname, $profile_picture, $middlename, $lastname, $gender, $contact_no, $date_hired, $license_no, $license_expiry, $status);
        
        if(mysqli_stmt_execute($stmt)){
            header("Location: guards.php");
            exit();
        }else{
            echo "<div class='alert alert-danger m-3'>
            Error saving guards: ". mysqli_error($con)." </div>";
        }
    }
}
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Add New Guard</h2>
        <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Employee No.</label>
                <input type="text" name="employee_no" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Middle Name</label>
                <input type="text" name="middlename" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Date Hired</label>
                <input type="text" name="date_hired" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>License Number</label>
                <input type="text" name="license_no" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>License Expiry</label>
                <input type="text" name="license_expiry" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Status</label>
               <select name="status" class="form-select">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
             <div class="mb-3">
            <label>Guard Photo</label>
            <input type="file"
            name="profile_picture"
            class="form-control"
            accept="image/*">
        </div>
        </div>
       
        <button type="submit" name="save" class="btn btn-success">
            Save Guard
        </button>
        <a href="guards.php" class="btn btn-secondary">
            Cancel
        </a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>