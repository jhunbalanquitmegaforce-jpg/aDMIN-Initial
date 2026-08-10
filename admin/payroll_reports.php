<?php
include('includes/header.php');
include('../config.php');
include('includes/sidebar.php');
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Payroll Summary Report</h2>
        <form method="GET" class="row g-2 mb-4">
            <div class="mb-3">
                <label>Guard</label>
                <select name="guard_id" class="form-control">
                    <option value="">All Guards</option>
        <?php 
        $guards = mysqli_query($con, 
        "SELECT id, employee_no, firstname, lastname FROM guards Order by lastname ASC ");
        While($guard = mysqli_fetch_assoc($guards)){
         ?> 
         <option value="<?php echo $guard['id']; ?>"
         <?php echo(isset($_GET['guard_id']) && $_GET['guard_id'] == $guard['id']) ? 'selected' : '';?>>
         <?php echo htmlspecialchars(
            $guard['employee_no'] . ' - ' .
            $guard['firstname'] . ' - ' .
            $guard['lastname']
         ); ?>
        </option>
        <?php } ?>
        </select>
        </div>
    <div class="col-md-4">
        <label>From</label>
        <input type="date"
        name="from"
        class="form-control"
        value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : ''; ?>">
    </div>
     <div class="col-md-4">
        <label>To</label>
        <input type="date"
        name="to"
        class="form-control"
        value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : ''; ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
        Filter
        </button>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <a href="payroll_reports.php" class="btn btn-secondary w-100">
            Reset
        </a>
    </div>
</form>
        <?php
        $from = isset($_GET['from']) ? trim($_GET['from']) : '';
        $to = isset($_GET['to']) ? trim($_GET['to']) : '';
        $guard_id = isset($_GET['guard_id']) ? trim($_GET['guard_id']) : '';

        $sql = "SELECT
        COUNT(*) AS total_records, 
        COALESCE(SUM(gross_pay), 0) AS total_gross,
        COALESCE(SUM(deductions), 0) AS total_deductions,
        COALESCE(SUM(net_pay), 0) AS total_net
        FROM payroll
        WHERE 1=1
        ";
        $params = [];
        $types = "";

        if($from != ''){
            $sql .= " AND payroll_from >= ?";
            $params[] = $from;
            $types .= "s";
        }
       
        if($to != ''){
            $sql .= " AND payroll_to <= ?";
            $params[] = $to;
            $types .= "s";
        }
        if($guard_id !=''){
            $sql .= " AND guard_id = ?";
            $params[] =  $guard_id;
            $types .= "i";
        }
        $stmt = mysqli_prepare($con, $sql);
        if(!empty($params)){
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $summary = mysqli_fetch_assoc($result);
        ?>

        <div class="row mt-4">

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Payroll Records</h6>
                        <h3><?php echo $summary['total_records']; ?></h3>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Gross Pay</h6>
                        <h3>&#8369;<?php echo number_format($summary['total_gross'], 2); ?></h3>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Deductions</h6>
                        <h3>&#8369;<?php echo number_format($summary['total_deductions'], 2); ?></h3>
                    </div>
                </div>
            </div>
             <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Total Net Pay</h6>
                        <h3>&#8369;<?php echo number_format($summary['total_net'], 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="d-flex justify-content-center">
    <a href="payroll_print.php?guard_id=<?php echo urlencode($guard_id); ?>&from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>"
    target="_blank"
    class="btn btn-dark"
    style="width: 30%; padding: 4px 8px; font-size: 12px;">
    <i class="bi bi-printer"></i>Print Report
    </a>
    </div>
</div>
<?php include("includes/footer.php"); ?>