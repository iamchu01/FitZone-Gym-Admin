<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Tax Settings - Admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
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
                            <h3 class="page-title">Tax Settings</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Tax Settings</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <!-- Tax Settings Form -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Manage Tax Settings</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="tax-settings.php">
                                    <div class="form-group">
                                        <label for="tax_name">Tax Name</label>
                                        <input type="text" class="form-control" id="tax_name" name="tax_name" placeholder="Enter tax name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tax_rate">Tax Rate (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" placeholder="Enter tax rate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary">Save Tax Setting</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Tax Settings Form -->
                
                <!-- Existing Tax Settings Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Existing Tax Settings</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tax Name</th>
                                            <th>Tax Rate (%)</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Fetch tax settings from the database
                                            $query = "SELECT * FROM tax_settings";
                                            $result = $db->query($query);
                                            $tax_settings = find_all('tax_settings');
                                            
                                            foreach ($tax_settings as $tax) {
                                                echo "<tr>";
                                                echo "<td>{$tax['id']}</td>";
                                                echo "<td>{$tax['tax_name']}</td>";
                                                echo "<td>{$tax['tax_rate']}</td>";
                                                echo "<td>" . ($tax['status'] == 1 ? 'Active' : 'Inactive') . "</td>";
                                                echo "<td>";
                                                echo "<button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editTaxModal' data-id='{$tax['id']}' data-name='{$tax['tax_name']}' data-rate='{$tax['tax_rate']}' data-status='{$tax['status']}'>Edit</button>";
                                                echo "<a href='delete-tax.php?id={$tax['id']}' class='btn btn-danger btn-sm'>Delete</a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Existing Tax Settings Table -->
                
            </div>
            <!-- /Page Content -->
            
        </div>
        <!-- /Page Wrapper -->
    </div>
    <!-- end main wrapper-->

    <!-- Edit Tax Modal -->
    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="editTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">Edit Tax Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="tax-settings.php">
                        <input type="hidden" name="tax_id" id="edit_tax_id">
                        <div class="form-group">
                            <label for="edit_tax_name">Tax Name</label>
                            <input type="text" class="form-control" id="edit_tax_name" name="tax_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_tax_rate">Tax Rate (%)</label>
                            <input type="number" class="form-control" id="edit_tax_rate" name="tax_rate" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="edit_submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

    <script>
        // Script to populate the edit modal with existing data
        $('#editTaxModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var taxId = button.data('id');
            var taxName = button.data('name');
            var taxRate = button.data('rate');
            var status = button.data('status');

            var modal = $(this);
            modal.find('#edit_tax_id').val(taxId);
            modal.find('#edit_tax_name').val(taxName);
            modal.find('#edit_tax_rate').val(taxRate);
            modal.find('#edit_status').val(status);
        });
    </script>

</body>
</html>
