<?php
include("includes/header.php");
include("../config.php");
?>
<div class="wrapper">
    <?php include("includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("includes/topbar.php"); ?>

    <?php 
    $search = "";

    $detachment_filter = "";
    $status_filter = "";
    if (isset($_GET['search'])) {
        $search = trim($_GET['search']);
    }
    if (isset($_GET['detachment_id'])){
        $detachment_filter = trim($_GET['detachment_id']);
    }
    if (isset($_GET['status'])){
        $status_filter = trim($_GET['status']);
    }
    $limit = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page'])
    ? (int) $_GET['page']
    : 1;
    if  ($page < 1) {
    $page = 1;
    }
    $offset = ($page - 1) * $limit;
    $keyword = "%$search%";
    $countSql = "SELECT COUNT(*) AS total FROM guards WHERE employee_no LIKE ? OR firstname LIKE ? OR middlename LIKE ? OR lastname LIKE ? OR license_no LIKE ?";

    $countStmt = mysqli_prepare($con, $countSql);
    mysqli_stmt_bind_param($countStmt, "sssss", $keyword, $keyword, $keyword, $keyword, $keyword);

    mysqli_stmt_execute($countStmt);

    $countResult = mysqli_stmt_get_result($countStmt);
    $countRow = mysqli_fetch_assoc($countResult);

    $totalGuards = $countRow['total'];

    $totalPages = ceil($totalGuards / $limit);

    $sql = "SELECT  guards.*,  guards.status AS guard_status, detachments.detachment_name FROM guards LEFT JOIN detachments ON guards.detachment_id = detachments.id

    WHERE guards.employee_no LIKE ? OR guards.firstname LIKE ? OR guards.middlename LIKE ? OR guards.lastname LIKE ? OR guards.license_no LIKE ? ORDER BY guards.id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param(
    $stmt, "sssssii", $keyword, $keyword, $keyword, $keyword, $keyword, $limit, $offset
    );
    mysqli_stmt_execute($stmt);
    $query =mysqli_stmt_get_result($stmt);
    ?>

   <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Guard Management</h2>
        <a href="add_guard.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Guard
        </a>
    </div>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Employee No., Name or License No." 
            value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">
                Search
            </button>
            <a href="guards.php" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
    <table class="table table-bordered table-hover shadow">
        <thead class="table-dark">
        <tr>
            <th>Photo</th>
            <th>ID</th>
            <th>Employee No.</th>
            <TH>Full Name</TH>
            <th>Gender</th>
            <th>Detachment</th>
            <th>Date Hired</th>
            <th>License No.</th>
            <th>License Expiry</th>
            <th>Status</th>
            <th class="text-nowrap">Action</th>
        </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <?php $fullName = trim(
                    $row['firstname']. ' ' . 
                    ($row['middlename'] ?? ''). ' ' .
                    $row['lastname']. ' ' . 
                    ($row['suffix']?? '')
                );
                 ?>
        <tr>
            <td class="text-center">
                <?php 
                $profilePicture = $row['profile_picture'] ?? ''; ?>
                <?php if (!empty($profilePicture)): ?>
                    <img src="../assets/uploads/guards/<?php echo htmlspecialchars($profilePicture); ?>"
                     alt="Profile Picture"
                     class="rounded-circle"
                     width="50"
                     height="50"
                     style="object-fit: cover;">
                     <?php else: ?>
                        <img src="../assets/uploads/guards/default.png"
                     alt="Profile Picture"
                     class="rounded-circle"
                     width="50"
                     height="50"
                     style="object-fit: cover;">
                     <?php endif; ?>
            </td>
            <td><?php echo $row['id'] ?></td>
            <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
            <td><?php echo htmlspecialchars($fullName); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['detachment_name'] ?? 'Unassigned'); ?></td>
            <td><?php echo !empty($row['date_hired']) ? date('M d, Y', strtotime($row['date_hired'])): '-'; ?></td>
            <td><?php echo htmlspecialchars($row['license_no'] ?? '-'); ?></td>
            <td><?php echo !empty($row['license_expiry']) ? date('M d, Y', strtotime($row['license_expiry'])): '-'; ?></td>
            <td><?php if (($row['guard_status'] ?? '') === 'Active'): ?>
            <span class="badge bg-success">
                Active
            </span>
            <?php else: ?>
                 <span class="badge bg-secondary">
                Inactive
            </span>

            <?php endif; ?>
            </td>
            <td class="text-nowrap">
                <div class="d-flex gap-1">
                <a href="view_guard.php?id=<?php echo $row['id']; ?>"
                class="btn btn-info btn-sm">
                 View
                </a>
                <a href="edit_guard.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_guard.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this guard?');">
                    Delete 
                 </a>
                 </div>
            </td>
        </tr> 
    <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="11"
            class="text-center text-muted py-4">
                No guards found
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
    <?php if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                        Previous
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active': ''; ?>">
                        <a class="page-link"  href="?page=<?php echo $i; ?>&search=<?php echo urldecode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : '';?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urldecode($search); ?>">
                            Next
                        </a>
                    </li>
            </ul>
        </nav>
        <?php endif; ?>
 </div>
</div>
</div>
<?php include("includes/footer.php"); ?>