<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Client Management</h2>

            <a href="add_client.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Client
            </a>
        </div>

        <!-- Search -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search client..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                >

                <button type="submit" class="btn btn-secondary">
                    Search
                </button>

                <a href="clients.php" class="btn btn-outline-secondary">
                    Clear
                </a>
            </div>
        </form>

        <?php
        $search = "";

        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
        }

        $sql = "SELECT * FROM clients
                WHERE client_name LIKE ?
                OR contact_person LIKE ?
                OR contact_no LIKE ?
                ORDER BY id DESC";

        $stmt = mysqli_prepare($con, $sql);

        $searchTerm = "%" . $search . "%";

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $searchTerm,
            $searchTerm,
            $searchTerm
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row['id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['client_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['contact_person']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['contact_no']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['address']); ?>
                            </td>

                            <td>
                                <?php if ($row['status'] == 'Active'): ?>

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                <?php endif; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['created_at']); ?>
                            </td>

                            <td class="text-nowrap">
                                <a
                                    href="edit_client.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <a
                                    href="delete_client.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this client?');"
                                >
                                    Delete
                                </a>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9" class="text-center">
                            No clients found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php include("includes/footer.php"); ?>