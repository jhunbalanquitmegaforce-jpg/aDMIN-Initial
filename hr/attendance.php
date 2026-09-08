<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <h2>Attendance Management</h2>

        <a href="add_attendance.php" class="btn btn-success mb-3">
            Add Attendance
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Guard</th>
                        <th>Detachment</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php

                $sql = "SELECT
                            attendance.*,
                            guards.employee_no,
                            guards.firstname,
                            guards.lastname,
                            detachments.detachment_name
                        FROM attendance
                        LEFT JOIN guards
                            ON attendance.guard_id = guards.id
                        LEFT JOIN detachments
                            ON guards.detachment_id = detachments.id
                        ORDER BY attendance.attendance_date DESC";

                $result = mysqli_query($con, $sql);

                $count = 1;

                while($row = mysqli_fetch_assoc($result)){
                ?>

                    <tr>
                        <td><?php echo $count++; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row['attendance_date']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['employee_no']); ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars($row['firstname'])
                                . " "
                                . htmlspecialchars($row['lastname']);
                            ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['detachment_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['time_in']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['time_out']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['status']); ?>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php include("includes/footer.php"); ?>