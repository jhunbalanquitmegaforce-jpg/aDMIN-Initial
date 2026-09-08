<?php
include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("../admin/includes/audit_log.php");


if (isset($_POST['save'])) {

    $client_name    = trim($_POST['client_name']);
    $contact_person = trim($_POST['contact_person']);
    $contact_no     = trim($_POST['contact_no']);
    $email          = trim($_POST['email']);
    $address        = trim($_POST['address']);
    $status         = $_POST['status'];
    $created_at     = trim($_POST['created_at']);

    // Check if client name already exists
    $check = mysqli_prepare(
        $con,
        "SELECT id FROM clients WHERE client_name = ?"
    );

    mysqli_stmt_bind_param($check, "s", $client_name);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {

        echo "<div class='alert alert-danger m-3'>
                Client Name already exists.
              </div>";

    } else {

        $sql = "INSERT INTO clients
                (client_name, contact_person, contact_no, email, address, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssss",
            $client_name,
            $contact_person,
            $contact_no,
            $email,
            $address,
            $status,
            $created_at
        );

        if (mysqli_stmt_execute($stmt)) {

            addAuditLog(
                $con,
                $_SESSION['user_id'],
                "Added Client",
                "Client Management",
                "Created client: $client_name"
            );

            header("Location: clients.php");
            exit();

        } else {

            echo "<div class='alert alert-danger m-3'>
                    Error saving client: " . mysqli_error($con) . "
                  </div>";
        }
    }
}
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <h2>Add New Client</h2>

        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Client Name</label>
                    <input
                        type="text"
                        name="client_name"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Contact Person</label>
                    <input
                        type="text"
                        name="contact_person"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Contact Number</label>
                    <input
                        type="text"
                        name="contact_no"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Address</label>
                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status</label>

                    <select name="status" class="form-select">

                        <option value="Active">
                            Active
                        </option>

                        <option value="Inactive">
                            Inactive
                        </option>

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Created</label>

                    <input
                        type="date"
                        name="created_at"
                        class="form-control"
                        required
                    >
                </div>

            </div>

            <button
                type="submit"
                name="save"
                class="btn btn-success"
            >
                Save Client
            </button>

            <a
                href="clients.php"
                class="btn btn-secondary"
            >
                Cancel
            </a>

        </form>

    </div>
</div>

<?php include("includes/footer.php"); ?>