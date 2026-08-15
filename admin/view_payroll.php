<?php
include('includes/header.php');
include('../config.php');
include('includes/sidebar.php');

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: payroll.php");
    exit();
}

$id = (int) $_GET['id'];
$sql = "SELECT
            payroll.*,
            guards.employee_no,
            guards.firstname,
            guards.lastname
            FROM payroll
            LEFT JOIN guards
                ON payroll.guard_id = guards.id
            WHERE payroll.id = ?";
            
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$payroll = mysqli_fetch_assoc($result);

if(!$payroll) {
    header("Location: payroll.php?error=not_found");
    exit();
}
?>
<div class="main-content">

<?php include("includes/topbar.php"); ?>
<div class="container-fluid mt-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Payroll Details</h2>
        <p class="text-muted mb-0">
            View payroll information
        </p>  
    </div>
<div>
    <a href="payroll.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>

    <button onclick="window.print()" class="btn btn-dark">
        <i class="bi bi-printer"></i>
        Print
    </button>
</div>
</div>

    <!-- //Payroll Inforamtion -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            Payroll Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="fw-bold">
                    Employee No.
                </label>
                <div>
                    <?php echo htmlspecialchars($payroll['employee_no']); ?>
                </div>
            </div>

             <div class="col-md-6">
                <label class="fw-bold">
                    Guard Name
                </label>
                <div>
                    <?php echo htmlspecialchars($payroll['firstname'] . ' ' . $payroll['lastname']); ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="fw-bold">
                    Payroll From
                </label>
                <div>
                    <?php echo date('F j, Y', strtotime($payroll['payroll_from'])); ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="fw-bold">
                    Payroll To
                </label>
                <div>
                    <?php echo date('F j, Y', strtotime($payroll['payroll_to'])); ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="fw-bold">
                    Days Worked
                </label>
                <div>
                    <?php echo htmlspecialchars($payroll['days_worked']); ?>
                </div>
            </div>

            <div class="col-md-6">
                <label class="fw-bold">
                    Rate Per Day
                </label>
                <div>
                    &#8369;<?php echo number_format($payroll['rate_per_day'], 2); ?>
                </div>
            </div>

        </div>
    </div>
</div>

    <!-- // Payroll Computation -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                Payroll Computation
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Gross Pay
                        </small>
                        <h4 class="mb-0">
                            &#8369;<?php echo number_format($payroll['gross_pay'], 2); ?>
                        </h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Deductions
                        </small>
                        <h4 class="mb-0">
                            &#8369;<?php echo number_format($payroll['deductions'], 2); ?>
                        </h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3">
                        <small class="text-muted">
                            Net Pay
                        </small>
                        <h4 class="mb-0">
                            &#8369;<?php echo number_format($payroll['net_pay'], 2); ?>
                        </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

            <!-- //Status  -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        Payroll Status
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($payroll['status'] === 'Draft'): ?>
                        <span class="badge bg-warning text-dark fs-6">
                            Draft
                        </span>
                        <?php elseif ($payroll['status'] === 'Checked'): ?>
                            <span class="badge bg-primary fs-6">
                                Checked
                            </span>
                             <?php elseif ($payroll['status'] === 'Approved'): ?>
                                <span class="badge bg-success fs-6">
                                Approved
                            </span>
                        <?php endif; ?>

                        <?php if ($payroll['status'] === 'Approved'): ?>
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="bi bi-lock-fill"></i>
                                This payroll has been approved and is  locked.
                            </div>
                            <?php endif; ?>
            </div>
            </div>

                <!-- //Record Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Record Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>Created:</strong>
                            <?php echo $payroll['created_at']
                            ? date('F j, Y h:i A', strtotime($payroll['created_at'])): 'N/A'; ?>
                        </p>
                    </div>
                </div>
</div>
</div>

<style>
    @media print{
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        /* Hide unnecessary screen elements  */
        .sidebar,
        .topbar,
        .navbar,
        .print-button,
        footer,
        .btn,
        button {
            display: none !important;
        }
        /* Remove Bootstrap/layout restrictions */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
            font-family: Arial, sans-serif;
            color: #000;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .container,
        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0  !important;
            padding: 0 !important;
        }
        h1,
        h2,
        h3,
        h4,
        h5{
            color: #000 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin-bottom: 15px !important;
        }
        .card-header {
            padding: 15px !important;
        }
        .row {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .record-information{
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .mt-4,
        .mb-5 {
            margin-bottom: 10px !important;
        }
        .footer,
        .report-footer {
            display: none !important;
        }
    }
</style>
<?php include("includes/footer.php"); ?>