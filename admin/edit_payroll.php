    <?php
    include("includes/header.php");
    include("../config.php");
    include("includes/sidebar.php");
    include("includes/audit_log.php");

    if (!isset($_GET['id'])) {
        header("Location: payroll.php");
        exit();
    }
    $id = $_GET['id'];
    $stmt = mysqli_prepare($con, "SELECT payroll.*, guards.firstname, guards.lastname FROM payroll left JOIN guards ON payroll.guard_id = guards.id WHERE payroll.id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $payroll = mysqli_fetch_assoc($result);
    if(!$payroll){
        die("Payroll record not found.");
    }
    ?>
    <?php
    if(isset($_POST['update'])){
        $guard_id = trim($_POST['guard_id']);
        $payroll_from = trim($_POST['payroll_from']);
        $payroll_to = trim($_POST['payroll_to']);
        $days_worked = trim($_POST['days_worked']);
        $rate_per_day = trim($_POST['rate_per_day']);
        $deductions = trim($_POST['deductions']);

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

    $sql = "UPDATE payroll SET guard_id=?, payroll_from=?, payroll_to=?, days_worked=?, rate_per_day=?, deductions=?, gross_pay=?, net_pay=? WHERE id=?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param($stmt, "issiddddi", $guard_id, $payroll_from, $payroll_to, $days_worked, $rate_per_day, $deductions, $gross_pay, $net_pay, $id);
        if(mysqli_stmt_execute($stmt)){
            $g = mysqli_query($con, "SELECT firstname, lastname FROM guards WHERE id='$guard_id'");
            $guard = mysqli_fetch_assoc($g);
            addAuditLog(
                $con, 
                $_SESSION['user_id'],
                "Updated Payroll",
                "Payroll",
                "Updated payroll for {$guard['firstname']} {$guard['lastname']}"
            );
            header("Location: payroll.php?success=updated!");
            exit();
        }else{
            echo"<div class='alert alert-danger'> Update failed</div>";
        }
    }
    ?>
    <div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Edit Payroll</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Guard</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($payroll['firstname'] . ' ' . $payroll['lastname']); ?>" readonly>
                <input type="hidden" name="guard_id" value="<?php echo $payroll['guard_id'] ?>">
            </div>
            <div class="mb-3">
                <label>Payroll From</label>
                <input type="date" name="payroll_from" class="form-control" value="<?php echo ($payroll['payroll_from']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Payroll To</label>
        <input type="date" name="payroll_to" class="form-control" value="<?php echo ($payroll['payroll_to']); ?>" required>
    </div>
    <div class="mb-3">
        <label>Days Worked</label>
        <input type="number" name="days_worked" class="form-control"  min="0" value="<?php echo ($payroll['days_worked']); ?>" required>
    </div>
    <div class="mb-3">
        <label>Rate Per Day</label>
        <input type="number" step="0.01" name="rate_per_day" class="form-control"  min="0" value="<?php echo ($payroll['rate_per_day']); ?>" required>
    </div>
    <div class="mb-3">
        <label>Deductions</label>
        <input type="number" step="0.01" name="deductions" class="form-control" min="0" value="<?php echo ($payroll['deductions']); ?>" required>
    </div>
    <button class="btn btn-success" name="update">Update Payroll</button>
    <a href="payroll.php" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
    </div>
    <?php include("includes/footer.php"); ?>