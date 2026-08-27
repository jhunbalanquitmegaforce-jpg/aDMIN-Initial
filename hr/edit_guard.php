<?php
include("includes/header.php");
include("../config.php");
// include("../includes/audit_log.php")
?>

<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("includes/topbar.php"); ?>

        <?php 
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: guards.php");
            exit();
        }
        $id = (int) $_GET['id'];
        $error = "";

        $sql = "SELECT * FROM guards WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $guard = mysqli_fetch_assoc($result);
        if (!$guard) {
            header("Location: guards.php");
            exit();
        }

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
        $status = $_POST['status'] ?? 'Inactive';

         $profile_picture = $guard['profile_picture'];
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK){
            $uploadDir = "../assets/uploads/guards/";
            $fileName = $_FILES['profile_picture']['name'];
            $tmpName = $_FILES['profile_picture']['tmp_name'];

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowed)) {
                die("Invalid image type.");
            }
            $newFileName = uniqid("guard_", true) . "." . $extension;
            $destination = $uploadDir . $newFileName;
            if(move_uploaded_file($tmpName, $destination)) {

            if (!empty($profile_picture) && $profile_picture !== "default.png"){
                $oldFile = $uploadDir . $profile_picture;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
                $profile_picture = $newFileName;
            }
        }

        if ($employee_no === '' || 
        $firstname === '' ||
        $lastname === '' ||
        $gender === '' ||
        $date_hired === ''
        ) {
        $error = "Please fill in all required fields.";
        } else {
            $checkSql = "SELECT id FROM guards WHERE employee_no = ? AND id != ? LIMIT 1";
            $checkStmt = mysqli_prepare($con, $checkSql);
            mysqli_stmt_bind_param($checkStmt,
            "si",
            $employee_no,
            $id
            );
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            if (mysqli_num_rows($checkResult) > 0){
                $error = "Employee number already exists.";
            } else {
                if (
                    $detachment_id !== '' &&
                     (int)$detachment_id !== (int)($guard['detachment_id'] ?? 0)
                    ){
                    $capacitySql = "SELECT d.guards_required,
                    COUNT(g.id) AS assigned FROM detachments d LEFT JOIN guards g ON d.id = g.detachment_id WHERE d.id = ? GROUP BY d.id
                    ";
                    $capacityStmt = mysqli_prepare($con, $capacitySql);
                    mysqli_stmt_bind_param(
                        $capacityStmt,
                        "i",
                        $detachment_id
                    );

                    mysqli_stmt_execute($capacityStmt);
                    $capacityResult = mysqli_stmt_get_result($capacityStmt);
                    $capacity = mysqli_fetch_assoc($capacityResult);
                    if (
                        $capacity &&
                        $capacity['guards_required'] !== null &&
                        $capacity['assigned'] >= $capacity['guards_required']
                    ){
                        $error = "This detachment is already fully staffed.";
                    }
                }
                if ($error === ''){
                    $updateSql = "
                    UPDATE guards SET
                employee_no = ?,
                firstname = ?,
                middlename = ?,
                lastname = ?,
                suffix = ?,
                gender = ?,
                birthdate = ?,
                contact_no = ?,
                email = ?,
                address = ?,
                date_hired = ?,
                license_no = ?,
                license_expiry = ?,
                detachment_id = NULLIF(?, ''),
                status = ?,
                profile_picture = ?
                WHERE id = ? 
                ";
                
                $updateStmt = mysqli_prepare($con, $updateSql);
                mysqli_stmt_bind_param(
                    $updateStmt,
                    "sssssssssssssissi",
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
                    $status,
                    $profile_picture,
                    $id
                );

                if (mysqli_stmt_execute($updateStmt)) {
                    // addAuditLog(
                    //     $con, 
                    //     $_SESSION['user_id'],
                    //     "Update Guard",
                    //     "Guard Management",
                    //     "Update guard: {$firstname} {$lastname} (Employee No. {$employee_no})"
                    // );
                    header("Location: view_guard.php?id" . $id . "&success=Guard updated successfully");
                    exit();
                }else{
                    $error = "Failed to update guard: " . mysqli_error($con);
                }
            }
        }
        }
        $guard['employee_no'] = $employee_no;
        $guard['firstname'] = $firstname;
        $guard['middlename'] = $middlename;
        $guard['lastname'] = $lastname;
        $guard['suffix'] = $suffix;
        $guard['gender'] = $gender;
        $guard['birthdate'] = $birthdate;
        $guard['contact_no'] = $contact_no;
        $guard['email'] = $email;
        $guard['address'] = $address;
        $guard['date_hired'] = $date_hired;
        $guard['license_no'] = $license_no;
        $guard['license_expiry'] = $license_expiry;
        $guard['detachment_id'] = $detachment_id;
        $guard['status'] = $status;
        }
        $detachmentSql = "
        SELECT id, detachment_name FROM detachments WHERE status = 'Active'
        ORDER BY detachment_name ASC ";
        $detachmentResult = mysqli_query($con, $detachmentSql);
        ?>
<div class="container-fluid mt-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Edit Guard</h2>

                <div>
                    <a href="view_guard.php?id=<?php echo $id; ?>"
                       class="btn btn-info">
                        <i class="fa-solid fa-eye"></i>
                        View
                    </a>

                    <a href="guards.php"
                       class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <!-- Personal Information -->
                        <h5 class="border-bottom pb-2 mb-3">
                            Personal Information
                        </h5>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Employee No.
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="employee_no"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['employee_no'] ?? ''); ?>"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    First Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="firstname"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['firstname'] ?? ''); ?>"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Middle Name
                                </label>

                                <input type="text"
                                       name="middlename"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['middlename'] ?? ''); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Last Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="lastname"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['lastname'] ?? ''); ?>"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Suffix
                                </label>

                                <input type="text"
                                       name="suffix"
                                       class="form-control"
                                       placeholder="Jr., Sr., III"
                                       value="<?php echo htmlspecialchars($guard['suffix'] ?? ''); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Gender
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="gender"
                                        class="form-select"
                                        required>

                                    <option value="">Select Gender</option>

                                    <option value="Male"
                                        <?php echo (($guard['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>
                                        Male
                                    </option>

                                    <option value="Female"
                                        <?php echo (($guard['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>
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
                                       value="<?php echo htmlspecialchars($guard['birthdate'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label>Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                                <?php if (!empty($guard['profile_picture'])): ?>
                                <img src="../assets/uploads/guards/<?php echo htmlspecialchars($guard['profile_picture']); ?>" class="rounded-circle mb-2" width="120" height="120" style="object-fit: cover;">
                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- Contact Information -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            Contact Information
                        </h5>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Contact No.
                                </label>

                                <input type="text"
                                       name="contact_no"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['contact_no'] ?? ''); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['email'] ?? ''); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Address
                                </label>

                                <textarea name="address"
                                          class="form-control"
                                          rows="2"><?php echo htmlspecialchars($guard['address'] ?? ''); ?></textarea>
                            </div>

                        </div>

                        <!-- Employment Information -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
                            Employment Information
                        </h5>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Date Hired
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="date_hired"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['date_hired'] ?? ''); ?>"
                                       required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Detachment
                                </label>

                                <select name="detachment_id"
                                        class="form-select">

                                    <option value="">
                                        Unassigned
                                    </option>

                                    <?php while ($detachment = mysqli_fetch_assoc($detachmentResult)): ?>

                                        <option value="<?php echo $detachment['id']; ?>"
                                            <?php if ((int)$detachment['id'] === (int)$guard['detachment_id']) {
                                                echo 'selected'; 
                                            }
                                            ?>>

                                            <?php echo htmlspecialchars($detachment['detachment_name']); ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status"
                                        class="form-select">

                                    <option value="Active"
                                        <?php echo (($guard['status'] ?? '') === 'Active') ? 'selected' : ''; ?>>
                                        Active
                                    </option>

                                    <option value="Inactive"
                                        <?php echo (($guard['status'] ?? '') === 'Inactive') ? 'selected' : ''; ?>>
                                        Inactive
                                    </option>

                                </select>
                            </div>

                        </div>

                        <!-- License Information -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">
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
                                       value="<?php echo htmlspecialchars($guard['license_no'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    License Expiry
                                </label>

                                <input type="date"
                                       name="license_expiry"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($guard['license_expiry'] ?? ''); ?>">
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="view_guard.php?id=<?php echo $id; ?>"
                               class="btn btn-secondary">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

<?php include("includes/footer.php"); ?>



        