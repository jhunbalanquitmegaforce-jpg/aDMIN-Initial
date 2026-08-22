<?php
session_start();

include("includes/header.php");
include("../config.php");
include("includes/sidebar.php");
include("includes/audit_log.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}
date_default_timezone_set('Asia/Manila');
$backup_dir = "../backups/";

if(!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}
// Create Database Backup

if(isset($_POST['create_backup'])) {
    $database_result = mysqli_query($con, "SELECT DATABASE() AS db_name");
    $database_row = mysqli_fetch_assoc($database_result);

    $database_name = $database_row['db_name'];
    if (!$database_name){
        die("Database name could not be detected.");
    }
    $filename = $database_name . "_backup_" . date("Y-m-d_H-i-s") . ".sql";
    $filepath = $backup_dir . $filename;

    $sql_backup = "";
    $sql_backup .= "-- MegaForce Management System Database Backup\n";
    $sql_backup .= "-- Database: " . $database_name . "\n";
    $sql_backup .= "-- Generated: " . date("F j, Y h:i A") . "\n";
    $sql_backup .= "-- ---------------------------------------------\n\n";
    
    $tables_result = mysqli_query($con, "SHOW TABLES");
    
    if (!$tables_result) {
        $error = "Unable to retrieve database tables.";
    }else{
        while ($table_row = mysqli_fetch_row($tables_result)){
            $table_name = $table_row[0];

            // Table Structure
        $create_result = mysqli_query($con, "SHOW CREATE TABLE `" . $table_name . "`");
        $create_row = mysqli_fetch_assoc($create_result);
        $create_sql = $create_row['Create Table'];

        $sql_backup .= "-- -------------------------------------\n";
        $sql_backup .= "-- Table: " . $table_name . "\n";
        $sql_backup .= "-- -------------------------------------\n\n";
        $sql_backup .= "DROP TABLE IF EXISTS `" . $table_name . "`;\n";
        $sql_backup .= $create_sql . ";\n\n";

        // Table Data

        $data_result = mysqli_query($con, 
        "SELECT * FROM `" . $table_name . "`");
        if ($data_result && mysqli_num_rows($data_result) > 0) {
            $fields = mysqli_num_fields($data_result);
            while($data_row = mysqli_fetch_row($data_result)) {
                $values = [];
                for ($i = 0; $i < $fields; $i++){
                    if ($data_row[$i] === null) {
                        $values[] = "NULL";
                    }else{
                        $values[] = "'" .
                        mysqli_real_escape_string($con, $data_row[$i]) . 
                        "'";
                    }
                }
                $sql_backup .= "INSERT INTO `" . $table_name . "` VALUES (" . implode(", ", $values) . ");\n";
            }
            $sql_backup .= "\n";
        }
        }

        // Save Backup File
        if (file_put_contents($filepath, $sql_backup) !== false) {
            addAuditLog(
                $con, 
                $_SESSION['user_id'],
                "Created Database Backup",
                "Backup",
                "Created database backup: {$filename}"
            );
            header("Location: backup_restore.php?success=backup_created");
            exit();
        }else{
            $error = "Failed to create the backup file.";
        }
    }
}
// Restore Database Backup
if (isset($_POST['restore_backup'])){
    if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK){
        $error = "Please select a valid SQL backup file.";
    }else{
        $file = $_FILES['backup_file'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension !== 'sql'){
            $error = "Only .sql backup files are allowed.";
        }elseif ($file['size'] > 50 * 1024 * 1024) {
            $error = "Backup file is too large. Maximum size is 50 MB.";
        }else{
            $sql_file = file_get_contents($file['tmp_name']);
            if ($sql_file === false || trim($sql_file) === ''){
                $error = "The uploaded backup file is empty or invalid.";
            }else{
                mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0");
                if (mysqli_multi_query($con, $sql_file)){
                    do{
                        if ($result = mysqli_store_result($con)){
                            mysqli_free_result($result);
                        }
                    } while (mysqli_more_results($con) && mysqli_next_result($con));

                    if (mysqli_errno($con)) {
                        $error = "Database restore failed:" . 
                        mysqli_error($con);
                    }else{
                        addAuditLog(
                            $con, $_SESSION['user_id'],
                            "Restore Database Backup",
                            "Backup",
                            "Restored database from uploaded backup: {$file['name']} " 
                        );
                        header("Location: backup_restore.php?success=restored"
                        );
                        exit();
                    }
                }else{
                    $error = "Database restore failed: " . 
                    mysqli_errno($con);
                }
                mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 1");
            }
        }
    }
}

// Delete Backup
if(isset($_GET['delete'])) {
    $filename = basename($_GET['delete']);
    $filepath = $backup_dir . $filename;
    if( file_exists($filepath) && pathinfo($filename, PATHINFO_EXTENSION) === 'sql'){
        if (unlink($filepath)) {
            addAuditLog($con, $_SESSION['user_id'],
            "Deleted Database Backup",
            "Backup",
            "Deleted database backup: {$filename}"
            );
            header("Location: backup_restore.php?success=backup_deleted"
            );
            exit();
        }
    }
    header("Location: backup_restore.php?error=deleted_failed");
    exit();
 }
//  Get Backup Files
 $backup_files = [];
 if (is_dir($backup_dir)) {
    $files = scandir($backup_dir, SCANDIR_SORT_DESCENDING);
    foreach ($files as $file) {
        if ($file !== "." && $file !== ".." && pathinfo($file, PATHINFO_EXTENSION) === 'sql'){
            $backup_files[] = $file;
        }
    }
 }
 ?>
 <div class="main-content">
    <?php include("includes/topbar.php"); ?>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Backup & Restore</h2>
                <p class="text-muted mb-0">
                    Create and manage database backups.
                </p>
            </div>
            <a href="settings.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    <?php if (isset($_GET['success']) && $_GET['success'] === 'backup_created'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Backup Created!</strong>
            The database backup was created successfully.

            <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
            </button>
        </div>
        <?php endif; ?>
         <?php if (isset($_GET['success']) && $_GET['success'] === 'restored'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Database Restored!</strong>
            The database backup was restored successfully.

            <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
            </button>
        </div>
        <?php endif; ?>
       
        <?php if (isset($_GET['success']) && $_GET['success'] === 'backup_deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Backup Deleted!</strong>
            The selected backup was deleted successfully.

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
            <?php endif ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-database"></i>
                        Database Backup
                    </h5>
                </div>
                <div class="card-body">
                    <p>
                        Create a complete backup of the 
                        <strong>MEGAFORCE database</strong>
                        including database structure and records.
                    </p>
                    <form method="POST">
                        <button type="submit"
                        name="create_backup"
                        class="btn btn-primary"
                        onclick="return confirm('Create a database backup now?')">
                        <i class="bi bi-cloud-arrow-down"></i>
                        Create Backup
                    </button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Restore Database
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Warning:</strong>
                        Restoring a database backup will replace existing
                        database tables and records with the contents of
                        the selected backup.
                </div>
                <form method="POST"
                enctype="multipart/form-data"
                onsubmit="return confirmRestore();">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Select SQL Backup
                    </label>
                    <input
                    type="file"
                    name="backup_file"
                    class="form-control"
                    accept=".sql"
                    required>
                    <small class="text-muted">
                        Only .sql backup files are allowed.
                        maximum file size: 50 MB.
                    </small>
                </div>
                <button type="submit"
                name="restore_backup"
                class="btn btn-warning">
                <i class="bi bi-arrow-counterclockwise"></i>
                Restore Database
            </button>
            </form>
            </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i>
                        Backup History
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($backup_files)): ?>
                        <div class="alert alert-info mb-0">
                            No database backups have been created yet.
                        </div>
                        <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Backup File</th>
                                    <th>Size</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 1;
                                foreach ($backup_files as $file):
                                    $filepath = $backup_dir . $file;
                                    $size = filesize($filepath);

                                $created = date("F j, Y h:i A", filemtime($filepath));
                                 ?>
                                 <tr>
                                    <td>
                                        <?php echo $count++; ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-file-earmark-text"></i>
                                        <?php echo htmlspecialchars($file); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($size / 1024, 2); ?>
                                        KB
                                    </td>
                                    <td>
                                        <?php echo $created; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="../backups/<?php echo urldecode($file); ?>" class="btn btn-sm btn-primary"
                                        download>
                                    <i class="bi bi-download"></i>
                                    Download
                                    </a>
                                    <a href="backup_restore.php?delete=<?php  echo urlencode($file); ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this backup?')">
                                <i class="bi bi-trash"></i>
                                Delete
                                </a>
                                    </td>
                                 </tr>
                                 <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmaRestore(){
        return confirm(
            "Warning!\n\n" +
            "Restore this backup may replace the current" +
            "database tables and records. \n\n" +
            "Make sure you have a recent backup before continuing.\n\n" +
            "Do you want to continue?"
            );
            }
        </script>
 <?php include("includes/footer.php"); ?>