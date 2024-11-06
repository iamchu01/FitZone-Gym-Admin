<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Blank Page - HRMS admin template</title>

    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>

</head>

<body>
    <div class="main-wrapper">
    <?php include 'layouts/menu.php'; ?>

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
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Content Starts -->
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
                                    <!-- Options for existing backups will be populated here -->
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
            <!-- /Content End -->
            
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->



<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>



</body>

</html>