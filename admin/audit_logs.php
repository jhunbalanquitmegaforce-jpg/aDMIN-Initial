<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <h2>Audit Logs</h2>

        <div class="card shadow">

            <div class="card-body">

                <form method="GET" class="mb-3">

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by user, action, module, or details"
                            value="<?php echo htmlspecialchars($search); ?>"
                        >

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            Search
                        </button>

                    </div>

                </form>

                <?php

                // Count total records
                $countSql = "
                    SELECT COUNT(*) AS total
                    FROM audit_logs
                    LEFT JOIN users
                    ON users.id = audit_logs.user_id
                ";

                if ($search != "") {

                    $safeSearch = mysqli_real_escape_string($con, $search);

                    $countSql .= "
                        WHERE users.fullname LIKE '%$safeSearch%'
                        OR audit_logs.action LIKE '%$safeSearch%'
                        OR audit_logs.module LIKE '%$safeSearch%'
                        OR audit_logs.details LIKE '%$safeSearch%'
                    ";
                }

                $countResult = mysqli_query($con, $countSql);
                $countRow = mysqli_fetch_assoc($countResult);

                $totalRecords = $countRow['total'];

                $totalPages = ceil($totalRecords / $limit);

                ?>

                <div class="table-responsive">

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

                            $sql = "
                                SELECT
                                    audit_logs.*,
                                    users.fullname
                                FROM audit_logs
                                LEFT JOIN users
                                ON users.id = audit_logs.user_id
                            ";

                            if ($search != "") {

                                $sql .= "
                                    WHERE users.fullname LIKE '%$safeSearch%'
                                    OR audit_logs.action LIKE '%$safeSearch%'
                                    OR audit_logs.module LIKE '%$safeSearch%'
                                    OR audit_logs.details LIKE '%$safeSearch%'
                                ";
                            }

                            $sql .= "
                                ORDER BY audit_logs.created_at DESC
                                LIMIT $offset, $limit
                            ";

                            $result = mysqli_query($con, $sql);

                            // Correct numbering across pages
                            $count = $offset + 1;

                            if (mysqli_num_rows($result) > 0) {

                                while ($row = mysqli_fetch_assoc($result)) {

                            ?>

                                    <tr>

                                        <td>
                                            <?php echo $count++; ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['created_at']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['fullname'] ?? 'Unknown User'); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['action']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['module']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['details']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['ip_address']); ?>
                                        </td>

                                    </tr>

                            <?php

                                }

                            } else {

                            ?>

                                <tr>
                                    <td colspan="7" class="text-center">
                                        No audit logs found.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <?php if ($totalPages > 1) { ?>
                <?php 
                $pagesPerGroup = 5; 
                $currentGroup = ceil($page / $pagesPerGroup);
                $startPage = ($currentGroup - 1) * $pagesPerGroup + 1;
                $endPage = min($startPage + $pagesPerGroup - 1, $totalPages);
                ?>
                    <nav aria-label="Audit log pagination">

                        <ul class="pagination justify-content-center">

                            <!-- Previous -->
                             <?php if ($page > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                                    Previous
                                </a>
                            </li>
<?php } ?>
                        <?php for ($i = $startPage; $i <= $endPage; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php } ?>

                            <?php if ($endPage < $totalPages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $endPage + 1; ?>&search=<?php echo urlencode($search); ?>">
                                    Next
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </nav>
                    <?php } ?>
                    <?php if ($totalRecords > 0) { ?>
                <div class="text-muted text-center">
            Showing
            <?php echo $offset + 1; ?>
            -
            <?php echo min($offset + $limit, $totalRecords); ?>
            of
            <?php echo $totalRecords; ?>
            audit log entries.
        </div> 
        <?php } ?>

            </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>