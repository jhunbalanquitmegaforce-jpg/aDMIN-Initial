<?php
include("includes/header.php");
include("../config.php");
?>

<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("includes/topbar.php"); ?>
        <?php 
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_no = trim($_POST['employee_no'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $middlename = trim($_POST['middlename'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $suffix = trim($_POST['suffix'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $birthdate = $_POST['birthdate'] ?? '';
        $contact_no = trim($_POST['contact_no'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $date_hired = $_POST['date_hired'] ?? '';
        $license_no = trim($_POST['license_no'] ?? '');
        $license_expiry = $_POST['license_expiry'] ?? '';
        $detachment_id = $_POST['detachment_id'] ?? '';
        $status = $_POST['status'] ?? 'Active';

        if ($employee_no === '' || 
        $firstname === '' ||
        $lastname === '' ||
        $gender === '' ||
        $date_hired === ''
        ){
        $error ="Please fill in all required fields.";
        }else{
            $checkSql = "SELECT id FROM guards WHERE employee_no = ? LIMIT 1";
            $checkStmt = mysqli_prepare($con, $checkSql);
            mysqli_stmt_bind_param($checkStmt,
            "s",
            $employee_no
            );
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            if (mysqli_num_rows($checkResult) > 0){
                $error = "Employee number already exists.";
            }else{
                $sql = "
                INSERT INTO guards (
                employee_no,
                firstname,
                middlename,
                lastname,
                suffix,
                gender,
                birthdate,
                contact_no,
                email,
                address,
                date_hired,
                license_no,
                license_expiry,
                detachment_id,
                status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ";
                $stmt = mysqli_prepare($con, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssssssssis",
                    $employee_no,
                    $firstname,
                    $middlename,
                    $lastname,
                    $suffix,
                    $gender,
                    $birthdate,
                    $contact_no,
                    $email,
                    $address,
                    $date_hired,
                    $license_no,
                    $license_expiry,
                    $detachment_id,
                    $status
                );
                if (mysqli_stmt_execute($stmt)){
                    header("Location: guards.php?success=Guards= added successfully");
                    exit();
                }else{
                    $error = "Failed to add guard: " . mysqli_error($con);
                }
            }
        }
        }
        $detachmentSql = " SELECT id, detachment_name FROM detachments WHERE status = 'Active' ORDER BY detachment_name ASC";
        $detachmentResult = mysqli_query($con, $detachmentSql);
        ?>
<div  class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Add guard</h2>
        <a href="guards.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
    </div>
    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                <h5 class="border-bottom pb-2 mb-3">
                    Personal Information
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Employee No. <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                        name="employee_no"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($_POST['employee_no'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            First Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="firstname" class="form-control" required value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Middle Name 
                        </label>
                        <input type="text" name="middlename" class="form-control" value="<?php echo htmlspecialchars($_POST['middlename'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Last Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="lastname" class="form-control" required value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Suffix
                        </label>
                        <input type="text" name="suffix" class="form-control" placeholder="Jr., Sr., III" value="<?php echo htmlspecialchars($_POST['suffix'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Gender <span class="text-danger">*</span>
                        </label>
                        <select type="text" name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">
                            <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>
                            Male
                        </option>
                        <option value="Female">
                            <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>
                            Female
                        </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Birthdate
                        </label>
                        <input type="date"
                        name="birthdate"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file"
                    name="profile_picture"
                    class="form-control"
                    accept="image/*"
                    capture="environment">
                    <small class="text-muted">
                        Upload or take a photo of the guard.
                    </small>
                </div>
                <h5 class="border-bottom pb-2 mb-3 mt-3">
                    Contact Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="from-label">
                            Contact No.
                        </label>
                        <input type="text"
                        name="contact_no"
                        class="form-controll"
                        value="<?php echo htmlspecialchars($_POST['contact_no'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="from-label">
                            Email
                        </label>
                        <input type="email"
                        name="email"
                        class="form-controll"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="from-label">
                            Address
                        </label>
                        <textarea
                        name="address"
                        class="form-controll"
                        rows="3"> <?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <h5 class="border-bottom pb-2 mb-3">
                    Employment Information
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Date Hired <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                        name="date_hired"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($_POST['date_hired'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Detachment
                        </label>
                        <select name="detachment_id" class="form-select">
                            <option value="">
                                Unassigned
                            </option>
                            <?php while ($detachment = mysqli_fetch_assoc($detachmentResult)): ?>
                                <option value="<?php echo $detachment['id']; ?>"
                                <?php echo (($_POST['detachment_id'] ?? '') == $detachment['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($detachment['detachment_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="Active"
                            <?php echo (($_POST['status'] ?? 'Active') === 'Active') ? 'selected' : ''; ?>>
                            Active
                        </option>
                        <option value="Inctive"
                            <?php echo (($_POST['status'] ?? 'Inactive') === 'Inactive') ? 'selected' : ''; ?>>
                            Inactive
                        </option>
                        </select>
                    </div>
                </div>
                <h5 class="border-bottom pb-2 mb-3 mt-3">
                    License Information
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            License No.
                        </label>
                        <input type="text"
                        name="license_no"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['license_no'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            License Expiry.
                        </label>
                        <input type="date"
                        name="license_expiry"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['license_expiry'] ?? ''); ?>">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="guards.php"
                    class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i>
                Save Guard
            </button>
                </div>
                </form>
            </div>
        </div>
</div>

    </div>
</div>
<?php include("includes/footer.php"); ?>