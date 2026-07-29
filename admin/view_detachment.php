<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

if (!isset($_GET['id'])) {
    header("Location: detachments.php");
    exit();
}
$id = intval($_GET['id']);

$sql = "SELECT detachments. *, clients.client_name FROM detachments JOIN clients ON detachments.client_id = clients.id
    WHERE detachments.id =?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if(!$row){
    echo "Detachment not found.";
    exit();
}
?>
<div class="main-content">
    <?php  include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Detachment Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderd">

                <tr>
                    <th width="250">Detachment Name</th>
                    <td><?php echo htmlspecialchars($row['detachment_name']); ?></td>
                </tr>
                 <tr>
                    <th>Client</th>
                    <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                </tr>
                <tr>
                    <th>Contact Person</th>
                    <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                </tr>
                <tr>
                    <th>Guards Required</th>
                    <td><?php echo htmlspecialchars($row['guards_required']); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr><tr>
                    <th>Date Created</th>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
                </table>
                <a href="detachments.php" class="btn btn-secondary">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>