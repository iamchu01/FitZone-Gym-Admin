<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Roles & Permissions - HRMS admin template</title>

    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>

</head>

<body>
    <div class="main-wrapper">
    <?php include 'layouts/topbar.php'; ?>
    <?php include 'layouts/settings-sidebar.php'; ?>
    <?php include 'layouts/two-col-sidebar.php'; ?>

    <!-- Page Wrapper -->
     <div class="page-wrapper">
          <!-- Page Content -->
           <div class="content container-fluid">
               <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                         <div class="col-sm-12">
                              <h3 class="page-title">Roles & Permissions</h3>
                         </div>
                    </div>
                </div>
               <!-- /Page Header -->

               <!-- Main Content -->
               <form action="process-roles-permissions.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                        <i class="fa fa-eye-slash" id="toggle-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Select Role <span class="text-danger">*</span></label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <!-- Add other roles as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                              <!-- Permissions Section (Single Column) -->
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>Module Permission</th>
                                                <th>Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>File Maintenance</td>
                                                <td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="fileMaintenance"></div></td>
                                            </tr>
                                            <tr>
                                                <td>Transaction</td>
                                                <td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="transaction"></div></td>
                                            </tr>
                                            <tr>
                                                <td>Reports</td>
                                                <td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="reports"></div></td>
                                            </tr>
                                            <tr>
                                                <td>Utilities</td>
                                                <td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="utilities"></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                    </div>
                                </div>
               </form>
               <!-- /Main Content -->

           </div>
          <!-- /Page Content -->
     </div>
    <!-- /Page Wrapper -->

</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const icon = document.getElementById('toggle-icon');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

</body>

</html>
