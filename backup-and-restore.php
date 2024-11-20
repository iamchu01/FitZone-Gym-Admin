<?php include 'layouts/title-meta.php'; ?>
<?php include 'layouts/db-connection.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
// backup_restore.php

// Handle Backup
if (isset($_POST['backupName'])) {
    $backupName = $_POST['backupName'];
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = ''; // Your database password
    $dbName = 'gymms';

    // Create a backup file
    $backupFile = 'uploads/backup_db' . $backupName . '.sql';

    // Command to execute mysqldump
    $command = "mysqldump --opt -h $dbHost -u $dbUser -p$dbPass $dbName > $backupFile";

    // Execute the command
    $output = null;
    $resultCode = null;
    exec($command, $output, $resultCode);

    if ($resultCode === 0) {
        echo "Backup successful!";
    } else {
        echo "Backup failed!";
    }
    exit();
}

// Handle Restore
if (isset($_POST['restorePoint'])) {
    $restoreFile = 'backups/' . $_POST['restorePoint'];

    if (file_exists($restoreFile)) {
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPass = ''; // Your database password
        $dbName = 'gymms';

        // Command to restore database from the backup
        $command = "mysql -h $dbHost -u $dbUser -p$dbPass $dbName < $restoreFile";

        // Execute the command
        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            echo "Restore successful!";
        } else {
            echo "Restore failed!";
        }
    } else {
        echo "Backup file not found!";
    }
    exit();
}

// Get backup files from the backups directory for the restore point dropdown
$backupFiles = scandir('uploads/backup_db');
$backupOptions = '';

foreach ($backupFiles as $file) {
    if (strpos($file, '.sql') !== false) {
        $backupOptions .= '<option value="' . $file . '">' . $file . '</option>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Backup & Restore - HRMS Admin Template</title>
    <!-- Add your head includes here -->
    <link rel="stylesheet" href="path/to/bootstrap.min.css"> <!-- Example -->
</head>

<body>
<?php include 'layouts/menu.php'; ?> 
    <div class="main-wrapper">
        <!-- Include menu if necessary -->
        
        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <!-- Page Content -->
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Backup & Restore</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Backup & Restore</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Backup & Restore Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Backup Data</h5>
                                <p class="card-text">Create a backup of your data.</p>
                                <div class="form-group">
                                    <label for="backupName">Backup Name</label>
                                    <input type="text" class="form-control" id="backupName" placeholder="Enter backup name">
                                </div>
                                <button class="btn btn-primary" id="backupButton">Create Backup</button>
                                <div class="backup-status mt-3">
                                    <!-- Status message will be displayed here after backup process -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Restore Data</h5>
                                <p class="card-text">Restore your system data from an existing backup.</p>
                                <div class="form-group">
                                    <label for="restorePoint">Select Restore Point</label>
                                    <select class="form-control" id="restorePoint">
                                        <option>Select a backup to restore</option>
                                        <?php echo $backupOptions; ?>
                                    </select>
                                </div>
                                <button class="btn btn-danger" id="restoreButton">Restore Backup</button>
                                <div class="restore-status mt-3">
                                    <!-- Status message will be displayed here after restore process -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Backup & Restore Section -->
            </div>
            <!-- /Page Content -->
        </div>
        <!-- /Page Wrapper -->
    </div>

    <!-- JAVASCRIPT -->
    <?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>


    <script>
        // Backup Button Handler
        document.getElementById('backupButton').addEventListener('click', function () {
            var backupName = document.getElementById('backupName').value;

            if (backupName.trim() === '') {
                alert('Please enter a backup name');
                return;
            }

            // AJAX call to trigger PHP backup
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'backup_restore.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.querySelector('.backup-status').innerHTML = xhr.responseText;
                }
            };
            xhr.send('backupName=' + backupName);
        });

        // Restore Button Handler
        document.getElementById('restoreButton').addEventListener('click', function () {
            var restorePoint = document.getElementById('restorePoint').value;

            if (restorePoint === 'Select a backup to restore') {
                alert('Please select a backup to restore');
                return;
            }

            // AJAX call to trigger PHP restore
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'backup_restore.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.querySelector('.restore-status').innerHTML = xhr.responseText;
                }
            };
            xhr.send('restorePoint=' + restorePoint);
        });
    </script>
</body>

</html>
