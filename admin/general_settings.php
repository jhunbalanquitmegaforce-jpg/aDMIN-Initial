<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
$result = mysqli_query($con, "SELECT * FROM  system_settings LIMIT 1");
$settings = mysqli_fetch_assoc($result);


if(isset($_POST['save'])){
    $company_name = mysqli_real_escape_string($con, $_POST['company_name']);
    $company_address = mysqli_real_escape_string($con, $_POST['company_address']);
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $logo = $settings['logo'];
    if(!empty($_FILES['logo']['name'])){
        $logo = time() . "_" . basename($_FILES['logo']['name']);
        $target = "../assets/uploads/" . $logo;

        move_uploaded_file($_FILES['logo']['tmp_name'], $target);
    }
    mysqli_query($con, "UPDATE system_settings SET
    company_name = '$company_name',
    company_address = '$company_address',
    contact_number = '$contact_number',
    email = '$email', 
    logo = '$logo' 
    WHERE id=1");
     
     echo "<script>
     alert('Settings updated successfully!');
     window.location='general_settings.php';
     </script>";
}
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">
        <h2 class="mb-4">General Settings</h2>
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control"
                value="<?php echo htmlspecialchars($settings['company_name']); ?>">
            </div>
            <div class="mb-3">
                <label>Company Address</label>
                <textarea name="company_address" class="form-control" rows="3"><?php echo htmlspecialchars($settings['company_address']); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                value="<?php echo htmlspecialchars($settings['contact_number']); ?>">
            </div>
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control"
                value="<?php echo htmlspecialchars($settings['email']); ?>">
            </div>
            <div class="mb-3">
                <label>Company Logo</label>
                <input type="file" name="logo" class="form-control">
                <?php if (!empty($settings['logo'])){ ?>
                <img src="../assets/uploads/<?php echo $settings['logo']; ?>" 
                width="120" class="img-thumbnail mt-2">
                <?php } ?>
            </div>
            <button type="submit" name="save" class="btn btn-primary">
                Save Changes
            </button>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>