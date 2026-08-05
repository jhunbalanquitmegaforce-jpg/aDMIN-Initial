<?php
include("includes/header.php");
include("../config.php");   
include("includes/sidebar.php");
include("includes/audit_log.php");

if (isset($_POST['save'])) {
    $guard_id = $_POST['guard_id'];
    $attendance_date = $_POST['attendance_date'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    
    $sql = "INSERT INTO attendance (guard_id, attendance_date, time_in, time_out, status, remarks) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "isssss", $guard_id, $attendance_date, $time_in, $time_out, $status, $remarks);
    if (mysqli_stmt_execute($stmt)){
        $g = mysqli_query($con, "SELECT firstname, lastname FROM guards WHERE id='$guard_id'");
        $guard_info = mysqli_fetch_assoc($g);
        addAuditLog(
            $con,
            $_SESSION['user_id'],
            "Added Attendance",
            "Attendance",
            "Recorded attendance for {$guard_info['firstname']} {$guard_info['lastname']} "
        );
        header("Location: attendance.php");
        exit();
    }else{
        echo "<div class='alert alert-danger'>Error saving attendance.</div>";
    }
}
?>
 <div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <h2>Add Attendance</h2>
        <form method="POST">
    <div class="mb-3">
    <label>Guard</label>
    <select name="guard_id" class="form-control" required>
        <option value="">Select Guard</option>
        <?php
        $guards = mysqli_query($con, "SELECT id, employee_no, firstname, lastname FROM guards order by lastname asc" );
        while($g = mysqli_fetch_assoc($guards)){ ?>
            <option value="<?php echo $g['id']; ?>"><?php echo $g['employee_no']. " - " . $g['firstname'] . " " . $g['lastname']; ?></option>
        <?php } ?>
    </select>
 </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="attendance_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Time In</label>
        <input type="time" name="time_in" class="form-control">
    </div>
    <div class="mb-3">
        <label>Time Out</label>
        <input type="time" name="time_out" class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="Present">Present</option>
            <option value="Late">Late</option>
            <option value="Absent">Absent</option>
            <option value="Leave">Leave</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Remarks</label>
        <textarea name="remarks" class="form-control"></textarea>
    </div>

    <button class="btn btn-success" name="save">Save Attendance</button>
    <a href="attendance.php" class="btn btn-secondary">Cancel</a>
        
 </form>
 </div>
 </div>
 <?php include("includes/footer.php"); ?>