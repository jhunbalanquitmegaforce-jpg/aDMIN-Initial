<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
$role_id = $_SESSION['role_id'] ?? 0;
if($role_id != 1 && $role_id != 2) {
    header("Location: payroll.php?error=access_denied");
    exit();
}
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("includes/audit_log.php");

if(isset($_POST['save'])) {
    $guard_id = $_POST['guard_id'];
    $payroll_from = $_POST['payroll_from'];
    $payroll_to = $_POST['payroll_to'];
    $days_worked = $_POST['days_worked'];
    $rate_per_day = $_POST['rate_per_day'];
    $deductions = $_POST['deductions'];

     if($days_worked < 0 || $rate_per_day < 0 || $deductions < 0){
            echo "<div class='alert alert-danger'>
            Days worked, rate per day, and deductions cannot be negative.
            </div>";
            exit();
        }
    $gross_pay = $days_worked * $rate_per_day;
    if($deductions > $gross_pay){
            echo "<div class='alert alert-danger'>
            Deduction cannot be greater than Gross Pay.</div>";
            exit();
        }
    $net_pay = $gross_pay - $deductions;

    $stmt = mysqli_prepare($con, "INSERT INTO payroll (guard_id, payroll_from, payroll_to, days_worked, rate_per_day, gross_pay, deductions, net_pay) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "issiiddd", $guard_id, $payroll_from, $payroll_to, $days_worked, $rate_per_day, $gross_pay, $deductions, $net_pay);
    if(mysqli_stmt_execute($stmt)) {
        $g=mysqli_query($con, "SELECT firstname, lastname FROM guards WHERE id = $guard_id");
        $guard=mysqli_fetch_assoc($g);

        addAuditLog(
            $con,
             $_SESSION['user_id'],
              "General Payroll", 
              "Payroll",
               "Generated payroll for {$guard['firstname']} {$guard['lastname']} "
               );
        header("Location: payroll.php");
        exit();
    }
}
?>

<div class="main-content">
<?php include("includes/topbar.php"); ?>
<div class="container-fluid mt-4">

<h2>Add Payroll</h2>
<form method="POST">
    <div class="mb-3">
        <label>Guard</label>
        <select name="guard_id" class="form-control" required>
            <option value="">Select Guard</option>
            <?php
            $guards=mysqli_query($con, "SELECT id, employee_no, firstname, lastname FROM guards Order By lastname");
            while($g = mysqli_fetch_assoc($guards)) { ?>
            <option value="<?php echo $g['id']; ?>">
            <?php echo $g['employee_no'] . ' - ' . $g['firstname'] . ' ' . $g['lastname']; ?></option>
    
            <?php } ?>
        </select>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label>Payroll From</label>
            <input type="date" name="payroll_from" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
            <label>Payroll To</label>
            <input type="date" name="payroll_to" class="form-control" required>
        </div>
    </div>
    <div class="mb-3">
        <label>Days Worked</label>
        <input type="number" name="days_worked" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label>Rate Per Day</label>
        <input type="number" step="0.01" name="rate_per_day" class="form-control"  min="0" required>
    </div>
    <div class="mb-3">
        <label>Deductions</label>
        <input type="number" step="0.01" name="deductions" class="form-control" min="0" required>
    </div>
    <button name="save" class="btn btn-success">
        Save Payroll
    </button>
    <a href="payroll.php" class="btn btn-secondary">
        Cancel</a>
</form>
</div>
</div>
<?php include("includes/footer.php"); ?>
