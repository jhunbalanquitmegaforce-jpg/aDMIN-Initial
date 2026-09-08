<?php
include('includes/header.php');
include('../config.php');
include('includes/sidebar.php');

$role_id = $_SESSION['role_id'] ?? 0;

?>

<div class="main-content">
<?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Payroll Management</h2>
    <?php $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $from = isset($_GET['from']) ? $_GET['from']: '';
    $to = isset($_GET['to']) ? $_GET['to'] : '';
    ?>
<?php if (isset($_GET['success']) && $_GET['success'] === 'status_updated'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success</strong>
    Payroll status has been updated successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Access Denied</strong>
    You do not have permission to access this payroll function.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<form method="GET" class="row g-2 mb-3">
<div class="col-md-5">
    <input type="text"
    name="search"
    class="form-control"
    placeholder="Search employee no. or guard name"
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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Payroll Records</h5>
    <div>
    <a href="add_payroll.php" class="btn btn-success me-2">
        <i class="bi bi-plus-circle"></i>Add Payroll</a>
    <a href="payroll_reports.php" class="btn btn-dark">
        <i class="bi bi-bar-chart-line"></i>Payroll Reports</a>
    <a href="payroll_print.php?search=<?php echo urlencode($search); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>"
    target="_blank"
    class="btn btn-secondary">
        <i class="bi bi-printer"></i>Print Report</a>
    </div>
</div>

    <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead>

        <tr>
            <th>#</th>
            <th>Employee No.</th>
            <th>Guard Name</th>
            <th>Payroll Period</th>
            <th>Gross Pay</th>
            <th>Deductions</th>
            <th>Net Pay</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
<?php
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
if($from != ''){
    $sql .=" AND payroll.payroll_from >= ?";
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
$total_records = 0;
$total_gross = 0;
$total_deductions = 0;
$total_net = 0;
$count = 1;

$rows = [];
while($row = mysqli_fetch_assoc($result)) {

$rows[] = $row;
    $total_records++;
    $total_gross += $row['gross_pay'];
    $total_deductions += $row['deductions'];
    $total_net += $row['net_pay'];

        ?>
    <tr>
    <td><?php echo $count++; ?></td>
    <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
    <td>
        <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>
    </td>
    <td><?php $from_date = date('F j, Y', strtotime($row['payroll_from']));
    $to_date = date('F j, Y', strtotime($row['payroll_to']));
    echo htmlspecialchars($from_date . ' - ' . $to_date)?>
    </td>
    <td>&#8369;<?php echo number_format($row['gross_pay'], 2); ?></td>
    <td>&#8369;<?php echo number_format($row['deductions'], 2); ?></td>
    <td>&#8369;<?php echo number_format($row['net_pay'], 2); ?></td>
    <td>
        <?php  
        if($row['status'] == 'Draft') {
            echo '<span class="badge bg-warning text-dark">Draft</span>';
        }elseif ($row['status'] == 'Checked'){
            echo '<span class="badge bg-primary">Checked</span>';
        }elseif ($row['status'] == 'Approved'){
            echo '<span class="badge bg-success">Approved</span>';
        }
        ?>
    </td>
    <td class="text-nowrap">
        <a href="view_payroll.php?id=<?php echo $row['id']; ?>"
        class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i>View</a> 

        <?php if($row['status'] === 'Draft'): ?>
        <a href="payroll_status.php?id=<?php echo $row['id']; ?>&status=Checked" class="btn btn-sm btn-info" onclick="return confirm('Mark this payroll as Checked?')"><i class="bi bi-check2-circle"></i>Checked</a> 
            <?php elseif ($row['status'] == 'Checked'): ?>
                <a href="payroll_status.php?id=<?php echo $row['id']; ?>&status=Approved"
                class="btn btn-sm btn-success"
                onclick="return confirm('Are you sure you want to APPROVE this payroll?\n\nOnce approved, it cannot be edited or deleted.')"><i class="bi bi-shield-check"></i>Approve</a>
                <?php endif; ?>
            

            <?php if($row['status'] != 'Approved'): ?>
            <a href="edit_payroll.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i>Edit</a>

            <a href="delete_payroll.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this payroll?')"><i class="bi bi-trash"></i>Delete</a> 
            <?php else: ?>

            <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i>Locked</span>
            <?php endif; ?>
        </td>
    </tr>
        <?php } ?>
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Records</h6>
                        <h4><?php echo $total_records; ?></h4>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Gross Pay</h6>
                        <h4>&#8369;<?php echo number_format($total_gross, 2); ?></h4>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Deductions</h6>
                        <h4>&#8369;<?php echo number_format($total_deductions, 2); ?></h4>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Net Pay</h6>
                        <h4>&#8369;<?php echo number_format($total_net, 2); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        </table>
        
        </div>
    </div>
</div>
    <?php include("includes/footer.php"); ?>