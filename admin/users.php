<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");


$search = "";
if (isset($_GET['search'])){
    $search = trim($_GET['search']);
}
$sql = "SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id WHERE fullname LIKE? OR username LIKE? OR email LIKE? ORDER BY id DESC";
$stmt = mysqli_prepare($con, $sql);
$keyword = "%$search%";
mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>
<div class="main-content">
 <?php include("includes/topbar.php");?>
 
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
            <td>
                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');">
                    Delete </a>
            </td>
        </tr> 
    
    
    <?php } ?>
        </tbody>
    </table>
 </div>
</div>
<?php include("includes/footer.php"); ?>