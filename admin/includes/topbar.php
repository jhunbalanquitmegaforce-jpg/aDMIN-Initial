<?php   
$user_id = $_SESSION['user_id'];
$topbarQuery = mysqli_query($con, "SELECT fullname, email, profile_picture, role_id FROM users WHERE id='$user_id'");
$topbarUser = mysqli_fetch_assoc($topbarQuery);

  $role = "unknown";

        switch ($topbarUser['role_id']) {
            case 1:
                $role = "Administrator";
                break;
            case 2:
                $role = "HR";
                break;
            case 3:
                $role = "OIC";
                break;
            case 4:
                $role = "Guard";
                break;
            case 5:
                $role = "client";
                break;
            default:
                $role = "unknown";
        }

?>
<div class="topbar">
    <h4>
        Welcome,
    </h4>
        <!-- <div class="d-flex align-items-center">
            <img src="../assets/uploads/<?php echo htmlspecialchars($topUser['profile_picture']); ?>" alt="<?php echo htmlspecialchars($topUser['fullname']); ?>" title="Click to enlarge" class="rounded-circle me-2" width="40" height="40" style="cursor: pointer; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#profileModal">
            <span class="ms-2"><?php echo htmlspecialchars($topUser['fullname']); ?></span>
        </div>  -->
            <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../assets/uploads/<?php echo htmlspecialchars($topbarUser['profile_picture']); ?>" alt="<?php echo htmlspecialchars($topbarUser['fullname']); ?>" title="Click to enlarge" class="rounded-circle me-2" width="40" height="40" style="cursor: object-fit: cover;">
                <span><?php echo htmlspecialchars($topbarUser['fullname']); ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="text-center p-3">
                    <img src="../assets/uploads/<?php echo htmlspecialchars($topbarUser['profile_picture']); ?>" alt="<?php echo htmlspecialchars($topbarUser['fullname']); ?>" class="rounded-circle" width="80" height="80" style="object-fit: cover;"> 
                    <h6 class="mb-0"><?php echo htmlspecialchars($topbarUser['fullname']); ?></h6>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($topbarUser['email']); ?>
                    </small>
                    <br>
                    <small class="badge bg-primary">
                        <?php echo $role; ?>
                    </small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                <li><a class="dropdown-item" href="../logout.php">Sign out</a></li>
            </ul>
        </div>
        <div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="../assets/uploads/<?php echo htmlspecialchars($topbarUser['profile_picture']); ?>" alt="<?php echo htmlspecialchars($topbarUser['fullname']); ?>" class="img-fluid">
            </div>
            <h5 class="modal-title text-center" ><?php echo htmlspecialchars($topbarUser['fullname']); ?></h5>
        </div>
    </div>
</div>
</div>
