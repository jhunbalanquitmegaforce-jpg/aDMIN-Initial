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

    <button onclick="window.print()" class="btn btn-dark print-button">
        <i class="bi bi-printer"></i>
        Print
    </button>
</div>
</div>

    <!-- Payroll Inforamtion -->
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

    <!--  Payroll Computation -->
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

            <!-- Status  -->
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

                <!-- Record Information -->
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

    @media screen {
        .print-only {
            display: none;
        }
        
    }
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
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background: #fff !important;
            font-family: Arial, sans-serif;
            color: #000 !important;
            font-size: 11pt !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
            min-height: 0 !important;
        }
        .container,
        .container-fluid {
            width: 100% !important;
            max-width: none !important;
            margin: 0  !important;
            padding: 0 !important;
        }
        h2 {
            font-size: 20pt !important;
            margin: 0 0 4px 0 !important;
        }
       
        h5{
            font-size: 12pt !important;
        }
        p{
            margin-top: 4px !important;
            margin-bottom: 4px !important;
        }
        .card {
            width: 100% !important;
            max-width: none !important;
            box-shadow: none !important;
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin-bottom: 12px !important;
        }
        .card-body{
            padding: 1px !important;
        }
        .card-body
        .card-header {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .border{
            border: 1px solid #000 !important;
        }
        .rounded{
            border-radius: 0 !important;
        }
        .p-3{
            padding: 10px !important;
        }
        h4{
            font-size: 15pt !important;
            margin: 2px 0 !important;
        }
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background: #fff !important;
            padding: 4px 8px !important;
            font-size: 10pt !important;
        }
        .alert{
            color: #000 !important;
            background:  #fff !important;
            border:  1px solid #000 !important;
            margin-top: 10px !important;
            margin-bottom:  0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .record-information{
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        tr{
            page-break-inside: avoid !important;
        }
        .shadow-sm{
            box-shadow: none !important;
        }
        .main-content{
            page-break-inside: avoid !important;
        }
        .row {
           margin-left: 0 !important;
           margin-right: 0 !important;
        }
        .row > * {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .record-information{
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .mt-4 {
            margin-top: 0 !important;
        }
        .mb-4 {
            margin-bottom: 12px !important;
        }
        .footer,
        .report-footer {
            display: none !important;
        }
    }
</style>
<?php include("includes/footer.php"); ?>