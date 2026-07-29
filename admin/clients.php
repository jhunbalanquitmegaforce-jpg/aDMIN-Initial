<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}
$sql = "SELECT * FROM clients WHERE client_name LIKE? OR contact_person LIKE? OR contact_no LIKE?  ORDER BY id DESC";

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
            <h2>Client Management</h2>
            <a href="add_client.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Client
            </a>
        </div>
        <form method="GET" class="mb-3">
            <div class="input-group">

            <input type="text" name="search" class="form-control" placeholder="Seach Client..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary">
                Search
            </button>
            <a href="clients.php" class="btn btn-secondary">
                Reset
            </a>
            </div>
        </form>
        <table class="table table-bordered table-hover shadow">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Client Name</th>
                <th>Contact Person</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Status</th>
                <th>Created</th>
                <th width="180">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($row['client_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="edit_client.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            Edit
                        </a>
                        <a href="delete_client.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this?')">
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