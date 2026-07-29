<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

//Count total users
function getTotal($con, $table)
{
    $query = mysqli_query($con, "SELECT COUNT(*) AS total FROM $table");
    $result = mysqli_fetch_assoc($query);
    return $result['total'];
}
$total_users = getTotal($con, "users");
$total_guards = getTotal($con, "guards");
$total_clients = getTotal($con, "clients");
$total_detachments = getTotal($con, "detachments");

$expiring = mysqli_query($con, " SELECT employee_no, firstname, lastname, license_expiry
FROM guards
WHERE license_expiry BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
ORDER BY license_expiry ASC
");
$recent_guards = mysqli_query($con, "SELECT employee_no, firstname, lastname, profile_picture, date_hired, status FROM guards ORDER BY id DESC LIMIT 5");
$status_query = mysqli_query($con, "SELECT status, COUNT(*) AS total FROM guards GROUP BY status");
$active = 0;
$inactive = 0;
while($row = mysqli_fetch_assoc($status_query)){
    if($row['status'] == "Active"){
        $active = $row['total'];
    }else{
        $inactive =$row['total'];
    }
}
?>

<div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <h2><?php echo $total_users; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        <h5>Total Guards</h5>
                        <h2><?php echo $total_guards; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-dark shadow">
                    <div class="card-body">
                        <h5>Total Clients</h5>
                        <h2><?php echo $total_clients; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h5>Total Detachment</h5>
                        <h2><?php echo $total_detachments; ?></h2>
                    </div>
                </div>
            </div>
        </div>
<div class="card shadow mt-4">
    <div class="card-header bg-warning">
        <h5 class="mb-0">License Expiring Within 30 Days</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Guard Name</th>
                    <th>License Expiry</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(mysqli_num_rows($expiring) > 0){ ?>
                <?php while($row = mysqli_fetch_assoc($expiring)){ ?>

                <tr>
                    <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                    <td>
                        <?php echo htmlspecialchars(
                            $row['firstname']. " " .$row['lastname']
                        );
                         ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['license_expiry']); ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?> 
            <tr>
                <td colspan="3" class="text-center">
                    No License expiring soon. </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Recently Added Guards</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover align-middle">

        <thead>
            <tr>
                <th>Photo</th>
                <th>Employee No.</th>
                <th>Name</th>
                <th>Date Hired</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($recent_guards)){ ?>
            <tr>
                <td>
                    <img src="../assets/uploads/guards/<?php echo $row['profile_picture']; ?>"
                    width="45"
                    height="45"
                    style="border-radius:50%; object-fit:cover;">
                </td>
                <td>
                    <?php echo htmlspecialchars($row['employee_no']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['firstname']. " " .$row['lastname']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['date_hired']); ?>
                </td>
                <td>
                    <?php if($row['status']=="Active") { ?>
                    <span class="badge bg-success">Active</span>
                    <?php }else{ ?>
                    <span class="badge bg-danger">Inactive</span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
    <div class="card shadow mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"> Guard Status Overview</h5>
        </div>
        <div class="card-body">
            <canvas id="guardChart"></canvas>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('guardChart');
    new Chart(ctx, {
    type: 'bar',
    data: {
    labels: ['Active', 'Inactive'],
    datasets: [{
    label: 'Number of Guards',
    data: [<?php echo $active; ?>, <?php echo $inactive; ?>]
    }]
    },
    options: {
    responsive: true,
    plugins: {
    legend: {
    display: false
    }
    }
    }
});
</script>
</div>
</div>
</div>   


<?php include("includes/footer.php"); ?>