<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>

  <title>Blank</title>

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
              <h3 class="page-title">Blank</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Blank</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#Blank"><i class="fa fa-plus">
                </i> Blank</a>
            </div>
          </div>
        </div>
        <!-- /Page Header -->

        <!-- //*Search Filter -->
        <div class="row filter-row">
          <div class="col-md-6 col-md-3">
            <div class="form-group form-focus">
              <input type="text" class="form-control floating">
              <label class="focus-label">Search</label>
            </div>
          </div>
        </div>
        <!-- //*Search Filter -->

        <!-- Content Starts -->
        <!-- //* Data Table -->
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-striped custom-table datatable">
                <thead>
                  <tr>
                    <th>Blank</th>
                    <th>Blank</th>
                    <th>Blank</th>
                    <th>Blank</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data Table Here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- //* Data Table -->
        <!-- /Content End -->

      </div>
      <!-- //* Page Content -->

      <!-- //* All Modals -->

      <!-- //* Create Membership Plan Modal -->
      <div id="Blank" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Blank</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" method="POST">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name
                        <span class="text-danger">*</span> </label>
                      <input class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name
                        <span class="text-danger">*</span> </label>
                      <input class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name
                        <span class="text-danger">*</span> </label>
                      <input class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name
                        <span class="text-danger">*</span> </label>
                      <input class="form-control" type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name
                        <span class="text-danger">*</span> </label>
                      <input class="form-control" type="text" required>
                    </div>
                  </div>
                </div>
                <div class="submit-section">
                  <button class="btn btn-primary submit-btn" type="submit">Create</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- //* Create Membership Plan Modal End -->

      <!-- //* All Modals End -->


    </div>
    <!-- //* Page Wrapper -->


  </div>
  <!-- end main wrapper-->


  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>



</body>

</html>
