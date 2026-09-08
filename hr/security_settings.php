<?php
session_start();

include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("../admin/includes/audit_log.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit();
}

$stmt = mysqli_prepare(
    $con,
    "SELECT * FROM security_settings WHERE id = 1 LIMIT 1"
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$settings = mysqli_fetch_assoc($result);

if (!$settings) {
    mysqli_query(
        $con,
        "INSERT INTO security_settings (id, session_timeout)
         VALUES (1, 30)"
    );

    $settings = [
        'id' => 1,
        'session_timeout' => 30
    ];
}

if (isset($_POST['update_security'])) {

    $session_timeout = (int) $_POST['session_timeout'];

    if ($session_timeout < 5 || $session_timeout > 480) {

        $error = "Session timeout must be between 5 and 480 minutes.";

    } else {

        $stmt = mysqli_prepare(
            $con,
            "UPDATE security_settings
             SET session_timeout = ?
             WHERE id = 1"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $session_timeout
        );

        if (mysqli_stmt_execute($stmt)) {

            addAuditLog(
                $con,
                $_SESSION['user_id'],
                "Update Security Settings",
                "Security Settings",
                "Changed session timeout to {$session_timeout} minutes"
            );

            header("Location: security_settings.php?success=updated");
            exit();

        } else {

            $error = "Failed to update security settings.";
        }
    }
}
?>

<div class="main-content">

    <?php include("includes/topbar.php"); ?>

    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2>Security Settings</h2>
                <p class="text-muted mb-0">
                    Configure login and session security.
                </p>
            </div>

            <a href="settings.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>

            <div class="alert alert-success alert-dismissible fade show">

                <strong>Success!</strong>
                Security settings have been updated successfully.

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>


        <?php if (isset($error)): ?>

            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="bi bi-shield-lock"></i>
                    Session Security
                </h5>

            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Session Timeout
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="session_timeout"
                                   class="form-control"
                                   min="5"
                                   max="480"
                                   value="<?php echo htmlspecialchars($settings['session_timeout']); ?>"
                                   required>

                            <span class="input-group-text">
                                minutes
                            </span>

                        </div>

                        <small class="text-muted">
                            The user session will expire after this period of activity.
                        </small>

                    </div>


                    <div class="alert alert-info">

                        <i class="bi bi-info-circle"></i>

                        Recommended session timeout:
                        <strong>30 minutes</strong>

                    </div>


                    <button type="submit"
                            name="update_security"
                            class="btn btn-primary">

                        <i class="bi bi-save"></i>
                        Save Security Settings

                    </button>

                    <a href="settings.php"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include("includes/footer.php"); ?>