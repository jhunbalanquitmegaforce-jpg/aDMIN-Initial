<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if(!empty($search)){
    $sql = "SELECT detachments.*, clients.client_name FROM detachments JOIN clients ON detachments.client_id = clients.id
    WHERE detachments.detachment_name LIKE ? OR clients.client_name LIKE ? OR detachments.address LIKE ? ORDER BY detachments.id DESC";

    $stmt = mysqli_prepare($con, $sql);
    $keyword = "%$search%";
    mysqli_stmt_bind_param($stmt, "sss", $keyword, $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $query =mysqli_stmt_get_result($stmt);
}else{
    $sql = "SELECT detachments. * , clients.client_name FROM detachments JOIN clients ON detachments.client_id = clients.id
    ORDER BY detachments.id DESC";
    $query = mysqli_query($con, $sql);
}
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Detachment Management</h2>
            <a href="add_detachment.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Detachment
            </a>
        </div>
        <form method="GET" class="mb-3">
            <div class="input-group">

            <input type="text" name="search" class="form-control" placeholder="Seach Detachment..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary">
                Search
            </button>
            <a href="detachments.php" class="btn btn-secondary">
                Reset
            </a>
            </div>
        </form>
        <table class="table table-bordered table-hover shadow">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Detachment Name</th>
                <th>Client Name</th>
                <th>Address</th>
                <th>Contact Person</th>
                <th>Contact Number</th>
                <th>Guards Required</th>
                <th>Status</th>
                <th>Date</th>
                <th width="180">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($row['detachment_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['guards_required']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        
                        <a href="view_detachment.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                            view
                        </a>
                        <a href="edit_detachment.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            Edit
                        </a>
                        <a href="delete_detachment.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this?')">
                            Delete
                        </a>
                        <a href="assign_guard.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                            Assign Guards
                        </a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<?php include("includes/footer.php"); ?>