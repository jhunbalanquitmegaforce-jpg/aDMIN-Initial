<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("../admin/includes/audit_log.php");

if (!isset($_GET['id'])) {
    header("Location: detachments.php");
    exit();
}

$id = $_GET['id'];

$stmt = mysqli_prepare($con, "SELECT * FROM detachments WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$detachment = mysqli_fetch_assoc($result);

if (!$detachment) {
    die("Detachment not found.");
}

if (isset($_POST['update'])) {

    $detachment_name = trim($_POST['detachment_name']);
    $client_id = trim($_POST['client_id']);
    $address = trim($_POST['address']);
    $contact_person = trim($_POST['contact_person']);
    $contact_no = trim($_POST['contact_no']);
    $guards_required = trim($_POST['guards_required']);
    $status = $_POST['status'];

    // Check if Detachment Name already exists
    // Exclude the current detachment
    $check = mysqli_prepare(
        $con,
        "SELECT id FROM detachments 
         WHERE detachment_name = ? AND id != ?"
    );

    mysqli_stmt_bind_param(
        $check,
        "si",
        $detachment_name,
        $id
    );

    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {

        echo "<div class='alert alert-danger m-3'>
                Detachment Name already exists.
              </div>";

    } else {

        $sql = "UPDATE detachments
                SET detachment_name=?,
                    client_id=?,
                    address=?,
                    contact_person=?,
                    contact_no=?,
                    guards_required=?,
                    status=?
                WHERE id=?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssi",
            $detachment_name,
            $client_id,
            $address,
            $contact_person,
            $contact_no,
            $guards_required,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            addAuditLog(
                $con,
                $_SESSION['user_id'],
                "Updated Detachment",
                "Detachment Management",
                "Updated detachment: $detachment_name"
            );

            header("Location: detachments.php");
            exit();

        } else {

            echo "<div class='alert alert-danger m-3'>
                    Error updating Detachment: " . mysqli_error($con) . "
                  </div>";
        }
    }
}
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <h2>Edit Detachment</h2>

        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Detachment Name</label>

                    <input
                        type="text"
                        name="detachment_name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($detachment['detachment_name']); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Client Name</label>

                    <select
                        name="client_id"
                        class="form-select"
                        required
                    >

                        <option value="">Select Client</option>

                        <?php
                        $clients = mysqli_query(
                            $con,
                            "SELECT id, client_name
                             FROM clients
                             ORDER BY client_name"
                        );

                        while ($row = mysqli_fetch_assoc($clients)) {
                        ?>

                            <option
                                value="<?php echo $row['id']; ?>"
                                <?php
                                if ($detachment['client_id'] == $row['id']) {
                                    echo "selected";
                                }
                                ?>
                            >
                                <?php echo htmlspecialchars($row['client_name']); ?>
                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Address</label>

                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        value="<?php echo htmlspecialchars($detachment['address']); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Contact Person</label>

                    <input
                        type="text"
                        name="contact_person"
                        class="form-control"
                        value="<?php echo htmlspecialchars($detachment['contact_person']); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Contact Number</label>

                    <input
                        type="text"
                        name="contact_no"
                        class="form-control"
                        value="<?php echo htmlspecialchars($detachment['contact_no']); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Guards Required</label>

                    <input
                        type="number"
                        name="guards_required"
                        class="form-control"
                        min="1"
                        value="<?php echo htmlspecialchars($detachment['guards_required']); ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status</label>

                    <select name="status" class="form-select">

                        <option
                            value="Active"
                            <?php
                            if ($detachment['status'] == "Active") {
                                echo "selected";
                            }
                            ?>
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            <?php
                            if ($detachment['status'] == "Inactive") {
                            echo "selected";
                            }
                            ?>
                            >
                            Inactive 
                          </option>
                    </select>
            </div>
            </div>
            <button type="submit" name="update" class="btn btn-success">Update Detachment</button>
                <a href="detachments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>