<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$records_per_page = 10;
$page =isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$total_query = mysqli_query($con, "SELECT COUNT(*) AS total FROM contact_messages");
$total_row = mysqli_fetch_assoc($total_query);
$total_records = $total_row['total'];

$total_pages = ceil($total_records / $records_per_page);
if ($total_pages > 0 && $page > $total_pages){
    $page = $total_pages;
}
$offset = ($page - 1) * $records_per_page;
// Get all contact messages
$query = mysqli_query($con, "
    SELECT id, fullname, email, subject, message, status, created_at
    FROM contact_messages
    ORDER BY created_at DESC
    LIMIT $offset, $records_per_page
    ");
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <div class="card shadow">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-envelope me-2"></i>
                    Contact Messages
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if(mysqli_num_rows($query) > 0){ ?>

                            <?php while($row = mysqli_fetch_assoc($query)){ ?>

                                <tr>

                                    <td>
                                        <?php echo htmlspecialchars($row['id']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['fullname']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['subject']); ?>
                                    </td>

                                    <td style="max-width:300px;">
                                        <?php echo nl2br(
                                            htmlspecialchars($row['message'])
                                        ); ?>
                                    </td>

                                    <td>

                                        <?php
                                        if($row['status'] == "Unread"){
                                        ?>

                                            <span class="badge bg-danger">
                                                Unread
                                            </span>

                                        <?php
                                        } elseif($row['status'] == "Read"){
                                        ?>

                                            <span class="badge bg-primary">
                                                Read
                                            </span>

                                        <?php
                                        } else {
                                        ?>

                                            <span class="badge bg-success">
                                                Replied
                                            </span>

                                        <?php } ?>

                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(
                                            $row['created_at']
                                        ); ?>
                                    </td>
                                    <td>
                                        <a href="view_message.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-eye"></i>
                                            View
                                        </a>
                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="8" class="text-center">
                                    No contact messages yet.
                                </td>
                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>
                    <?php if ($total_pages > 1) { ?>

<nav aria-label="Contact Messages Pagination">

    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">

            <a class="page-link"
               href="?page=<?php echo $page - 1; ?>">
                Previous
            </a>

        </li>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">

                <a class="page-link"
                   href="?page=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>

            </li>

        <?php } ?>

        <!-- Next -->
        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">

            <a class="page-link"
               href="?page=<?php echo $page + 1; ?>">
                Next
            </a>

        </li>

    </ul>

</nav>

<?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("includes/footer.php"); ?>