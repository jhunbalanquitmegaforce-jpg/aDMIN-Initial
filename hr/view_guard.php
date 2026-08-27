<?php 
include("includes/header.php");
include("../config.php");
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
    $guard_id = (int) $_GET['id'];
    $sql = "SELECT guards.*, guards.status AS guard_status, detachments.detachment_name FROM guards LEFT JOIN detachments ON guards.detachment_id = detachments.id 
    WHERE guards.id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $guard_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $guard = mysqli_fetch_assoc($result);

    if (!$guard) {
        echo '<div class="container-fluid mt-4">
        <div class="alert alert-danger">
        Guard record not found.
        </div>
        </div>';
        include("includes/footer.php");
        exit();
    }
$fullName = trim(
    $guard['firstname']. ' ' . 
    ($guard['middlename'] ?? ''). ' ' . 
    $guard['lastname']. ' ' . 
    ($guard['suffix'] ?? '')
);
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Guard Profile</h2>
        <div>
            <a href="guards.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Back
            </a>
            <a href="edit_guard.php?id=<?php echo $guard['id']; ?>"
            class="btn btn-warning">
            <i class="fa-solid fa-pen"></i>
            Edit
            </a>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-4 d-flex flex-column justify-content-center align-items-center text-center">
            <?php 
             $profilePicture = $guard['profile_picture'] ?? ''; 
             ?>
            <?php 
            if (!empty($profilePicture)): ?>   
            <img src="../assets/uploads/guards/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture"
            class="rounded-circle shadow"
            width="180"
            height="180"
            style="object-fit: cover;">
            <!-- <small class="d-block text-muted mt-2">
                <?php  echo htmlspecialchars($profilePicture); ?>
            </small> -->
            <?php else: ?>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto"
                style="width:180px; height:180px;">
                <i class="fa-solid fa-user fa-5x"></i>
            </div>
            <small class="d-block text-muted mt-2">
                No profile picture
            </small>
            <?php endif; ?>
            <h4 class="mt-3 mb-1">
                <?php echo htmlspecialchars($fullName); ?>
            </h4>
            <span class="badge bg-primary">
                <?php echo htmlspecialchars($guard['employee_no']); ?>
            </span>
            <div class="mt-2">
                <?php  if (($guard['guard_status'] ?? '') === 'Active'): ?>
                    <span class="badge bg-success">
                        Active
                    </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">
                            Inactive
                        </span>
                        <?php endif; ?>
            </div>
            </div>
            <div class="col-md-9">
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    Personal Information
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>First Name</strong>
                        <div>
                            <?php echo htmlspecialchars($guard['firstname'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Middle Name</strong>
                        <div>
                            <?php echo htmlspecialchars($guard['middlename'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Last Name</strong>
                        <div>
                            <?php echo htmlspecialchars($guard['lastname'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Suffix</strong>
                        <div>
                            <?php echo htmlspecialchars($guard['suffix'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Gender</strong>
                        <div>
                            <?php echo htmlspecialchars($guard['gender'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Birthdate</strong>
                        <div>
                            <?php echo !empty($guard['birthdate'])
                             ? date('M d, Y', strtotime($guard['birthdate'])): '-'; ?>
                        </div>
                    </div>
                </div>
                <h5 class="border-bottom pb-2">
                    Contact Information
                </h5>
                <div class="row">
                <div class="col-md-4 mb-3">
                        <strong>Contact No.</strong>
                    <div>
                    <?php echo htmlspecialchars($guard['contact_no'] ?? '-'); ?>
                   </div>
                </div>

                <div class="col-md-4 mb-3">
                        <strong>Email</strong>
                    <div>
                    <?php echo htmlspecialchars($guard['email'] ?? '-'); ?>
                   </div>
                </div>
                <div class="col-md-4 mb-3">
                        <strong>Address</strong>
                    <div>
                    <?php echo htmlspecialchars($guard['address'] ?? '-'); ?>
                   </div>
                </div>
            </div>
            </div>
        
        <h5 class="border-bottom pb-2">
            Employment Information
        </h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>Employee No.</strong>
                <div>
                    <?php echo htmlspecialchars($guard['employee_no'] ?? '-') ?>
                </div>
            </div>
             <div class="col-md-4 mb-3">
                <strong>Date Hired</strong>
                <div>
                    <?php echo !empty($guard['date_hired'])
                    ? date('M d, Y', strtotime($guard['date_hired'])): '-'; ?>
                </div>
            </div>
             <div class="col-md-4 mb-3">
                <strong>Detachment</strong>
                <div>
                    <?php echo !empty($guard['detachment_name'])
                    ? htmlspecialchars($guard['detachment_name']): 'Unassigned'; ?>
                </div>
            </div>
        </div>
        <h5 class="border-bottom pb-2">
            License Information
        </h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>License No.</strong>
                <div>
                    <?php echo htmlspecialchars($guard['license_no'] ?? '-'); ?>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <strong>License Expiry</strong>
                <div>
                    <?php echo !empty($guard['license_expiry'])
                    ? date('M d, Y', strtotime($guard['license_expiry'])) :'-'; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
</div>
</div>
</div>
<?php include("includes/footer.php"); ?>