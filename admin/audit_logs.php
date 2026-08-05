<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
?>
<div class="main-content">
    <?php  include("includes/topbar.php") ?>
    <div class="container-fluid mt-4">

    <h2>Audit Logs</h2>
    <div class="card shadow">
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by user, action, module, or details" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "
            SELECT 
                audit_logs.*,
                users.fullname
                FROM audit_logs
                LEFT JOIN users
                ON users.id = audit_logs.user_id
                ";
                if($search != ""){
                    $search = mysqli_real_escape_string($con, $search);
                    $sql .= "WHERE users.fullname LIKE '%$search%' OR audit_logs.action LIKE '%$search%' OR audit_logs.module LIKE '%$search%' OR audit_logs.details LIKE '%$search%' ";
                }
                $sql .= "ORDER BY audit_logs.created_at DESC";

                $result = mysqli_query($con, $sql);
                $count = 1;
                while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['action']); ?></td>
                <td><?php echo htmlspecialchars($row['module']); ?></td>
                <td><?php echo htmlspecialchars($row['details']); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
            </tr>

            <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>