<?php
include("includes/header.php");
?>

<div class="wrapper">
    <?php include("includes/sidebar.php") ?>

<div class="main-content">
 <?php include("includes/topbar.php");?>
 <?php
 $search = "";
if (isset($_GET['search'])){
    $search = trim($_GET['search']);
}
$limit = 10;
$page = isset($_GET['page']) && is_numeric ($_GET['page'])
? (int) $_GET['page'] 
: 1;
if  ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;
$keyword = "%$search%";
$countSql = "SELECT COUNT(*) AS total FROM users WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ?";
$countStmt = mysqli_prepare($con, $countSql);
mysqli_stmt_bind_param($countStmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($countStmt);
$countResult = mysqli_stmt_get_result($countStmt);
$countRow = mysqli_fetch_assoc($countResult);
$totalUsers = $countRow['total'];
$totalPages = ceil($totalUsers / $limit);
$sql = "SELECT users.*, roles.role_name
FROM users
JOIN roles ON users.role_id = roles.id
WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ? ORDER BY users.id DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param(
    $stmt, "sssii", $keyword, $keyword, $keyword, $limit, $offset
);
mysqli_stmt_execute($stmt);
$query =mysqli_stmt_get_result($stmt);
?>
 <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>User Management</h2>
        <a href="add_user.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add User
        </a>
    </div>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Full Name, Username of Email" value="<?php echo isset ($_GET['search']) ? htmlspecialchars($_GET['search']) :''; ?>">
            <button class="btn btn-primary" type="submit">
                Search
            </button>
            <a href="users.php" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>
    <table class="table table-bordered table-hover shadow">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <TH>Full Name</TH>
            <th>Role</th>
            <th>Username</th>
            <th>Status</th>
            <th width="170">Action</th>
        </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($query)){?>
        <tr>
            <td><?php echo $row['id'] ?></td>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['role_name']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php if ($row['role_id'] != 1): ?>
                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');">
                    Delete 
                </a>
                <?php else: ?>
                    <span class="badge bg-secondary">
                        Protected
                    </span>
                    <?php endif; ?>
            </td>
        </tr> 
    <?php } ?>
        </tbody>
    </table>
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