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
                    margin-bottom: 15px;
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
                    margin-bottom: 12px;
                }
                .company-header h2 {
                    margin: 0;
                    font-size: 22px;
                }
                .company-header h3 {
                    margin: 3px 0 8px;
                    font-size: 16px;
                }
                .report-details{
                    margin: 8px 0 12px;
                    padding: 7px 10px;
                    border: 1px solid #000;
                    text-align: left;
                    line-height: 1.4;
                }
                .signature-section {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 30px;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                .signature-box{
                    width: 30%;
                    text-align: center;
                }
                .signature-box p{
                    margin: 3px 0;
                }
                .signature-line {
                    border-bottom: 1px solid #000;
                    width: 100%;
                    margin-bottom: 5px;
                }
                .report-footer {
                    text-align: center;
                    margin-top: 15px;
                    font-size: 9px;
                    color: #666;
                }
                .report-footer p {
                    margin: 1px 0;
                }
                @media print{
                    @page{
                        size: A4 portrait;
                        margin: 8mm;
                    }
                      html,
                      body {
                        width: 100%;
                        height: auto;
                        margin: 0 !important;
                        padding: 0 !important;
                        font-family: Arial, sans-serif;
                        font-size: 9pt;
                        color: #000;
                        background: #fff;
                      }
                      .print-button {
                        display: none !important;
                      }
                      .company-header{
                        margin-bottom: 7px !important;
                      }
                      .company-header h2{
                        font-size: 18pt !important;
                        margin: 0 !important;
                      }
                      .company-header h3{
                        font-size: 13pt !important;
                        margin:  2px 0 4px !important;
                      }
                      .company-header p {
                        margin:  2px 0 !important;
                      }
                      .report-details{
                        margin: 5px 0 8px !important;
                        padding: 5px 8px !important;
                        line-height: 1.25 !important;
                        font-size: 8.5pt   !important;
                      }
                    table{
                        width: 100% !important;
                        margin-top: 5px !important;
                        page-break-inside:auto !important;
                    }
                    tr {
                        page-break-inside: avoid !important;
                        page-break-after: avoid !important;
                    }
                    thead{
                        display: table-header-group;
                    }
                    th,
                    td{
                        padding: 4px !important;
                        font-size: 8.5pt !important;
                    }
                    th{
                        background: #f2f2f2 !important;
                    }
                    .amount{
                        white-space: nowrap !important;
                    }
                .total-row {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                .signature-section{
                    margin-top: 15px !important;
                    display: flex !important;
                    justify-content: space-between !important;
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                .signature-box{
                    width: 30% !important;
                    font-size: 8.5pt !important;
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                .signature-box p{
                    margin:  2px 0 !important;
                }
                .signature-line{
                    margin-bottom: 4px !important;
                }
                .report-footer{
                    position: fixed;
                    bottom: 5mm;
                    left:  0;
                    right: 0;
                    text-align: center;
                    font-size: 10px;
                }
                .approval-title {
                    text-align: center;
                    margin-top:  15px;
                    margin-bottom: 12px;
                    font-size: 14px;
                    font-weight: bold;
                    text-decoration: underline;
                }
                br{
                    line-height: 0.5 !important;
                }
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
        <h4 class="approval-title">PAYROLL APPROVAL</h4>
        <div class="signature-section">
            <div class="signature-box">
                <p><strong>Prepared by:</strong></p>
                <div class="signature-line"></div>
                <p>IT / Payroll</p>
                <p>Date: ______________</p>
            </div>
            <div class="signature-box">
                <p><strong>Checked by:</strong></p>
                <div class="signature-line"></div>
                <p>OIC / HR</p>
                <p>Date: ______________</p>
            </div>
            <div class="signature-box">
                <p><strong>Approved by:</strong></p>
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