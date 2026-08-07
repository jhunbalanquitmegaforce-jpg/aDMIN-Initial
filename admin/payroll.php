<?php
include('includes/header.php');
include('../config.php');
include('includes/sidebar.php');
?>

<div class="main-content">
<?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Payroll Management</h2>
       <a href="add_payroll.php" class="btn btn-success mb-3">Add Payroll</a>

    <table class="table table-bordered">
        <thead>

        <tr>
            <th>#</th>
            <th>Employee No.</th>
            <th>Guard Name</th>
            <th>Payroll Period</th>
            <th>Gross Pay</th>
            <th>Deductions</th>
            <th>Net Pay</th>
            <th>Actions</th>
        </tr>
        </thead>
       <?php
 $sql = "SELECT payroll.*,
guards.employee_no, guards.firstname, guards.lastname
from payroll
JOIN guards ON payroll.guard_id = guards.id
ORDER BY payroll.payroll_from DESC";

$result = mysqli_query($con, $sql);
$count = 1;
while($row = mysqli_fetch_assoc($result)) {
        ?>
    <tr>
    <td><?php echo $count++; ?></td>
    <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
    <td>
        <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>
    </td>
    <td><?php echo htmlspecialchars($row['payroll_from'] . ' to ' . $row['payroll_to']); ?></td>
    <td>&#8369;<?php echo htmlspecialchars($row['gross_pay'],2); ?></td>
    <td>&#8369;<?php echo htmlspecialchars($row['deductions'],2); ?></td>
    <td>&#8369;<?php echo htmlspecialchars($row['net_pay'],2); ?></td>
    <td>
        <a href="edit_payroll.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete_payroll.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this payroll?')">Delete</a>
    </td>
</tr>
    <?php } ?>

    </table>
    </div>
</div>
<?php include("includes/footer.php"); ?>