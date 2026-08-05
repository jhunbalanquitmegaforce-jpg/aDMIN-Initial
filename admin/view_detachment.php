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
                <?php $countStmt = mysqli_prepare($con, "SELECT COUNT(*) AS total FROM guards WHERE detachment_id = ?");
                mysqli_stmt_bind_param($countStmt, "i", $id);
                mysqli_stmt_execute($countStmt);
                $countResult = mysqli_stmt_get_result($countStmt);
                $countRow = mysqli_fetch_assoc($countResult);

                $assigned = $countRow['total'];
                $required = $row['guards_required'];
                ?>
                <h5 class="mt-4">Assigned Guards(<?php echo $assigned; ?> / <?php echo $required; ?>)</h5>
                <?php if ($assigned == 0 ){
                        echo '<div class="alert alert-danger">No guards assigned.</div>';
                    }else if ($assigned < $required){
                        echo '<div class="alert alert-warning">Understaffed (' . ($required - $assigned) . ' more  guard(s) needed)</div>';   
                    }else{
                        echo '<div class="alert alert-success">Fully staffed</div>';
                    }  
                    ?>      
                    
                    
                    
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee No</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT guards.* FROM guards WHERE guards.detachment_id = ?";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        while($guard_row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($guard_row['employee_no']); ?></td>
                            <td>
                                <img src="../assets/uploads/guards/<?php echo htmlspecialchars($guard_row['profile_picture']); ?>"
                                width="60"
                                height="60"
                                style="object-fit:cover; border-radius: 50%;">
                            </td>
                            <td><?php echo htmlspecialchars($guard_row['firstname']." ".$guard_row['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($guard_row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($guard_row['contact_no']); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="detachments.php" class="btn btn-secondary">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>