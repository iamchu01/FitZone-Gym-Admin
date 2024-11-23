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
                <div class="row">
                    <div class="col-md-12">
                        <?php echo display_msg($msg); ?>
                    </div>
                </div>

                <!-- Handle Form Submissions -->
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['submit'])) {
                        // Add a new tax setting
                        $tax_name = $db->escape($_POST['tax_name']);
                        $tax_rate = $db->escape($_POST['tax_rate']);
                        $status = (int)$_POST['status'];

                        $sql = "INSERT INTO tax_settings (tax_name, tax_rate, status) VALUES ('$tax_name', '$tax_rate', '$status')";
                        if ($db->query($sql)) {
                            $session->msg("s", "Successfully Added New tax Successfully");
                            redirect('tax-settings.php', false);
                             echo "<script>
            setTimeout(function(){
                window.location.href = 'offered-programs.php';
            }, 100);
            </script>";
                        } else {
                            $_SESSION['error'] = "Failed to add tax setting.";
                        }
                    }

                    if (isset($_POST['edit_submit'])) {
                        // Update an existing tax setting
                        $tax_id = (int)$_POST['tax_id'];
                        $tax_name = $db->escape($_POST['tax_name']);
                        $tax_rate = $db->escape($_POST['tax_rate']);
                        $status = (int)$_POST['status'];

                        $sql = "UPDATE tax_settings SET tax_name='$tax_name', tax_rate='$tax_rate', status='$status' WHERE id='$tax_id'";
                        if ($db->query($sql)) {
                            $_SESSION['success'] = "Tax setting updated successfully!";
                        } else {
                            $_SESSION['error'] = "Failed to update tax setting.";
                        }
                    }

                    if (isset($_POST['delete_tax'])) {
                        // Delete a tax setting
                        $tax_id = (int)$_POST['tax_id'];

                        $sql = "DELETE FROM tax_settings WHERE id='$tax_id'";
                        if ($db->query($sql)) {
                            $session->msg("s", "Deletion success");
                            echo "<script>
                            setTimeout(function(){
                                window.location.href = 'tax-settings.php';
                            }, 100);
                            </script>";
                        } else {
                            $session->msg("d", "Sorry Failed to delete tax.");
                            redirect('tax-settings.php', false);
                        }
                    }
                    exit();
                   
                }
                ?>

                <!-- Tax Settings Form -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Manage Tax Settings</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
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
                                        $query = "SELECT * FROM tax_settings";
                                        $result = $db->query($query);

                                        while ($tax = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$tax['id']}</td>";
                                            echo "<td>{$tax['tax_name']}</td>";
                                            echo "<td>{$tax['tax_rate']}</td>";
                                            echo "<td>" . ($tax['status'] == 1 ? 'Active' : 'Inactive') . "</td>";
                                            echo "<td>
        <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editTaxModal' data-id='{$tax['id']}' data-name='{$tax['tax_name']}' data-rate='{$tax['tax_rate']}' data-status='{$tax['status']}'>Edit</button>
        <form method='POST' action='' style='display:inline-block;'>
            <input type='hidden' name='tax_id' value='{$tax['id']}'>
            <button type='submit' name='delete_tax' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this tax setting?\");'>Delete</button>
        </form>
    </td>";
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
                    <form method="POST" action="">
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
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var rate = button.data('rate');
            var status = button.data('status');

            var modal = $(this);
            modal.find('#edit_tax_id').val(id);
            modal.find('#edit_tax_name').val(name);
            modal.find('#edit_tax_rate').val(rate);
            modal.find('#edit_status').val(status);
        });
    </script>
</body>
</html>
