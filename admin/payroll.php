<?php
include('includes/header.php');
include('../config.php');
include('includes/sidebar.php');
?>

<div class="main-content">
<?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Payroll Management</h2>

<form method="GET" class="row g-2 mb-3">
<div class="col-md-5">
    <input type="text"
    name="search"
    class="form-control"
    placeholder="Search employeeno. or guard name"
    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ; ?>">
</div>
<div class="col-md-3">
    <input type="date"
    name="from"
    class="form-control"
    value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ; ?>">
</div>
<div class="col-md-3">
    <input type="date"
    name="to"
    class="form-control"
    value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : '' ; ?>">
</div>
<div class="col-md-1">
    <button type="submit" class="btn btn-primary w-100">
        Search
    </button>
</div>
</form>
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
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $from = isset($_GET['from']) ? $_GET['from']: '';
    $to = isset($_GET['to']) ? $_GET['to'] : '';
 $sql = "SELECT payroll.*,
guards.employee_no, guards.firstname, guards.lastname
from payroll 
LEFT JOIN guards ON payroll.guard_id = guards.id
WHERE 1=1";
$params = [];
$types = "";

if($search != ''){
    $sql .= " AND (
    guards.employee_no LIKE ?
    OR guards.firstname LIKE ?
    OR guards.lastname LIKE ? 
    )";

    $searchValue = "%$search%";
    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;
    $types .= "sss";
}
if($to != ''){
    $sql .=" AND payroll.payroll_from <= ?";
    $params[] = $from;
    $types .= "s";
}
if($to != ''){
    $sql .=" AND payroll.payroll_to <= ?";
    $params[] = $to;
    $types .= "s";
}
$sql .= " ORDER BY payroll.payroll_from DESC";
$stmt = mysqli_prepare($con, $sql);

if(!empty($params)){
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
    <td>&#8369;<?php echo number_format($row['gross_pay'], 2); ?></td>
    <td>&#8369;<?php echo number_format($row['deductions'], 2); ?></td>
    <td>&#8369;<?php echo number_format($row['net_pay'], 2); ?></td>
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