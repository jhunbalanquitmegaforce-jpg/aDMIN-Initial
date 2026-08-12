<?php
include("../config.php");
date_default_timezone_set('Asia/Manila');
$from = isset($_GET['from']) ? trim($_GET['from']) : '';
$to = isset($_GET['to']) ? trim($_GET['to']) : '';
$guard_id = isset($_GET['guard_id']) ? trim($_GET['guard_id']) : '';
$sql = "SELECT
            payroll.*, 
            guards.employee_no,
            guards.firstname,
            guards.lastname
        FROM payroll
        LEFT JOIN guards ON payroll.guard_id = guards.id
        WHERE 1=1
        ";

        $params = [];
        $types = "";

        if($from != ''){
            $sql .= " AND payroll.payroll_from >= ?";
            $params[] = $from;
            $types .= "s";
        }
        if($to != ''){
            $sql .= " AND payroll.payroll_to <= ?";
            $params[] = $to;
            $types .= "s";
        }
         if($guard_id != ''){
            $sql .= " AND payroll.guard_id = ?";
            $params[] = $guard_id;
            $types .= "i";
        }
        $sql .= " ORDER BY payroll.payroll_from DESC";
        $stmt = mysqli_prepare($con, $sql);
        
        if(!empty($params)){
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt); 
        $reference = 'PAY-' . date('Ymd-His');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payroll Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 30px;
                }
                h2 {
                    text-align: center;
                }
                .filter-info {
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                    text-align: center;
                }
                td {
                    vertical-align: middle;
                }
                .amount {
                    text-align: right;
                    white-space: nowrap;
                }
                .print-button {
                    text-align: center;
                    margin-bottom: 20px;
                }
                @media print {
                    .print-button{
                        display: none;
                    }
                }
                .print-icon {
                    width: 35px;
                    height: 35px;
                    padding: 0;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .company-header {
                    text-align: left;
                    margin-bottom: 25px;
                }
                .company-header h2 {
                    margin: 0;
                    font-size: 24px;
                }
                .company-header h3 {
                    margin: 5px 0 15px;
                    font-size: 18px;
                }
                .report-details{
                    margin: 15px 0 20px;
                    padding: 10px 15px;
                    border: 1px solid #000;
                    text-align: left;
                    line-height: 1.8;
                }
                .signature-section {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 60px;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                .signature-box{
                    width: 30%;
                    text-align: center;
                }
                .signature-box p{
                    margin: 5px 0;
                }
                .signature-line {
                    border-bottom: 1px solid;
                    width: 100%;
                    margin-bottom: 8px;
                }
                .report-footer {
                    text-align: center;
                    margin-top: 40px;
                    font-size: 11px;
                    color: #666;
                }
                .report-footer p {
                    margin: 2px 0;
                }
                @media print{
                    .signature-section{
                        break-inside: avoid;
                    }
                      .report-footer{
                        position: fixed;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                    }
                    table{
                        page-break-inside:auto;
                    }
                    tr {
                        page-break-inside: avoid;
                        page-break-after: auto;
                    }
                    thead{
                        display: table-header-group;
                    }
                    tfoot{
                        display: table-row-group;
                    }
                    @page {
                        size: A4;
                        margin: 15mm;
                    }
                }
                .total-row {
                    break-inside: avoid;
                    font-weight: bold;
                }
                .total-row th,
                .total-row td{
                    font-weight: bold;
                }
                .report-title {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid;
                    padding-bottom: 10px;
                }
                .report-title h2{
                    margin-bottom: 5px;
                    font-size: 22px;
                }
                .report-title p {
                    margin: 0;
                    font-size: 13px;
                    color: #555;
                }
                .approval-title {
                    text-align: center;
                    margin-top: 40px;
                    margin-bottom: 20px;
                    font-size: 14px;
                    font-weight: bold;
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>     
        <div class="print-button">
            <button onclick="window.print()" class="print-icon" title="Print Report">
                <i class="bi bi-printer"></i>
            </button>
        </div>
        <div class="report-title">
            <h2>MegaForce</h2>
        <h2>PAYROLL SUMMARY REPORT</h2>
        <p>MegaForce - Payroll Management System</p>
        </div>
        <div class="report-details">
            <strong>Payroll Reference:</strong>
            <?php  echo htmlspecialchars($reference); ?>
        
        <div class="filter-info">
            <strong>Payroll Period</strong>
        <?php if($from != '' && $to != '') {
        echo date("F j, Y", strtotime($from));
        echo " to ";
        echo date("F j, Y", strtotime($to));
        }else{
            echo "All Payroll Periods";
        }
        ?>
        <br>
        <?php
        if($guard_id != ''){
            $guard_query = mysqli_prepare($con, "SELECT firstname, lastname FROM guards WHERE id=?");
            mysqli_stmt_bind_param(
                $guard_query,
                "i",
                $guard_id
            );
            mysqli_stmt_execute($guard_query);
            $guard_result = mysqli_stmt_get_result($guard_query);
            $guard_info = mysqli_fetch_assoc($guard_result);
            if($guard_info){
                echo "<strong>Guard:</strong> " .
                    htmlspecialchars(
                        $guard_info['firstname'] . ' ' . $guard_info['lastname']
                        );
            }
        }else{
            echo"<strong>Guard:</strong> All Guards";
        }
        ?>
        <br>
        <strong>Report Date:</strong>
        <?php  echo date("F j, Y"); ?>
        </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee No.</th>
                    <th>Guard Name</th>
                    <th>Payroll Period</th>
                    <th>Gross Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                
                $total_gross = 0;
                $total_deductions = 0;
                $total_net = 0;
                
                while($row = mysqli_fetch_assoc($result)){
                    $total_gross += $row['gross_pay'];
                    $total_deductions += $row['deductions'];
                    $total_net += $row['net_pay'];
                    ?>
                    <tr>
                        <td>
                            <?php  echo $count++; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['employee_no']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['payroll_from'] . ' to ' . $row['payroll_to']); ?>
                        </td>
                        <td class="amount">
                            &#8369;<?php echo number_format($row['gross_pay'], 2); ?>
                        </td>
                        <td class="amount">
                            &#8369;<?php echo number_format($row['deductions'], 2); ?>
                        </td>
                        <td class="amount">
                            &#8369;<?php echo number_format($row['net_pay'], 2); ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="total-row">
                    <th colspan="4">TOTAL</th>

                    <th class="amount">
                        &#8369;<?php echo number_format($total_gross, 2); ?>
                    </th>

                    <th class="amount">
                        &#8369;<?php echo number_format($total_deductions, 2); ?>
                    </th>

                    <th class="amount">
                        &#8369;<?php echo number_format($total_net, 2); ?>
                    </th>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <h4 class="approval-title">PAYROLL APPROVAL</h4>
        <div class="signature-section">
            <div class="signature-box">
                <p><strong>Prepared by:</strong></p>
                <br><br>
                <div class="signature-line"></div>
                <p>IT / Payroll</p>
                <p>Date: ______________</p>
            </div>
            <div class="signature-box">
                <p><strong>Checked by:</strong></p>
                <br><br>
                <div class="signature-line"></div>
                <p>OIC / HR</p>
                <p>Date: ______________</p>
            </div>
            <div class="signature-box">
                <p><strong>Approved by:</strong></p>
                <br><br>
                <div class="signature-line"></div>
                <p>Management</p>
                <p>Date: ______________</p>
            </div>
        </div>
        <div class="report-footer">
            <p>MegaForce - Payroll Management System</p>
            <p>Generated on <?php echo date("F j, Y h:i A"); ?></p>
        </div>
        </body>
        <link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </html>