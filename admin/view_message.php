<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");

$id = intval($_GET['id'] ?? 0);

$query = mysqli_query($con, "
    SELECT id, fullname, email, subject, message, status, created_at
    FROM contact_messages
    WHERE id = $id
");

if (!$query || mysqli_num_rows($query) === 0) {
    die("Message not found.");
}

$row = mysqli_fetch_assoc($query);
if ($row['status'] === 'Unread') {
    mysqli_query($con,
    "UPDATE contact_messages
    SET status = 'Read' WHERE id = $id ");
    $row['status'] = 'Read';
}
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <div class="card shadow">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-envelope-open me-2"></i>
                    View Contact Message
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <strong>Full Name:</strong>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($row['fullname']); ?>
                    </p>
                </div>

                <div class="mb-3">
                    <strong>Email Address:</strong>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($row['email']); ?>
                    </p>
                </div>

                <div class="mb-3">
                    <strong>Subject:</strong>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($row['subject']); ?>
                    </p>
                </div>

                <div class="mb-3">
                    <strong>Message:</strong>

                    <div class="border rounded p-3 mt-2">
                        <?php echo nl2br(
                            htmlspecialchars($row['message'])
                        ); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Status:</strong>

                    <?php if ($row['status'] == "Unread") { ?>

                        <span class="badge bg-danger">
                            Unread
                        </span>

                    <?php } elseif ($row['status'] == "Read") { ?>

                        <span class="badge bg-primary">
                            Read
                        </span>

                    <?php } else { ?>

                        <span class="badge bg-success">
                            Replied
                        </span>

                    <?php } ?>
                </div>

                <div class="mb-4">
                    <strong>Date Received:</strong>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($row['created_at']); ?>
                    </p>
                </div>

                <a href="contact_messages.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Back to Messages
                </a>

            </div>

        </div>

    </div>

</div>

<?php include("includes/footer.php"); ?>