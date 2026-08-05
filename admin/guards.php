<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}
$sql = "SELECT  guards.*, detachments.detachment_name FROM guards LEFT JOIN detachments ON guards.detachment_id = detachments.id where employee_no LIKE ? OR firstname LIKE ? OR lastname LIKE ? ORDER BY guards.lastname ASC";

$stmt = mysqli_prepare($con, $sql);
$keyword = "%$search%";
mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>
<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Guard Management</h2>
            <a href="add_guard.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Guard
            </a>
        </div>
        <form method="GET" class="mb-3">
            <div class="input-group">

            <input type="text" name="search" class="form-control" placeholder="Search Guard..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary">
                Search
            </button>
            <a href="guards.php" class="btn btn-secondary">
                Reset
            </a>
            </div>
        </form>
        <table class="table table-bordered table-hover shadow">
            <thead class="table-dark">
            <tr>
                <th>Employee No</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Detachment</th>
                <th>Status</th>
                <th width="180">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                    <td>
                        <img src="../assets/uploads/guards/<?php echo htmlspecialchars($row['profile_picture']); ?>"
                    width="60"
                    height="60"
                    style="object-fit:cover; border-radius: 50%;">
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['firstname']." ". $row['lastname']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                    <td><?php echo !empty($row['detachment_name']) ? htmlspecialchars($row['detachment_name']) : 'Unassigned'; ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="edit_guard.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            Edit
                        </a>
                        <a href="delete_guard.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this guard?')">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<?php include("includes/footer.php"); ?>