<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>

  <title>Payment Methods</title>

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
              <h3 class="page-title">Payment Methods</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Payment Methods</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#create_payment"><i
                  class="fa fa-plus">
                </i> Create Payment</a>
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
                    <th>Payment Method</th>
                    <th>Payment Type</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Fetch payment methods from the database
                  $query = "SELECT * FROM tbl_payment_methods ORDER BY method_id DESC";
                  $result = $conn->query($query);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $method_name = htmlspecialchars($row['method_name']);
                      $method_type = htmlspecialchars($row['method_type']);
                      $account_name = htmlspecialchars($row['account_name']);
                      $account_number = htmlspecialchars($row['account_number']);
                      $description = htmlspecialchars($row['description']);
                      $status = htmlspecialchars($row['status']);

                      // Determine status label class and text
                      $status_label = $status === 'active' ? 'text-success' : 'text-danger';
                      $new_status = $status === 'active' ? 'inactive' : 'active';
                      ?>
                      <tr>
                        <td><?php echo $method_name; ?></td>
                        <td><?php echo $method_type; ?></td>
                        <td><?php echo $account_name; ?></td>
                        <td><?php echo $account_number; ?></td>
                        <td><?php echo $description ? $description : 'N/A'; ?></td>
                        <td>
                          <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown">
                              <i id="status-icon-<?php echo $row['method_id']; ?>"
                                class="fa fa-dot-circle-o <?php echo $status_label; ?>"></i>
                              <span id="status-text-<?php echo $row['method_id']; ?>"><?php echo ucfirst($status); ?></span>
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#"
                                onclick="toggleStatus(<?php echo $row['method_id']; ?>, 'active')">
                                <i class="fa fa-dot-circle-o text-success"></i> Active
                              </a>
                              <a class="dropdown-item" href="#"
                                onclick="toggleStatus(<?php echo $row['method_id']; ?>, 'inactive')">
                                <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                              </a>
                            </div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i
                                class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_method"><i
                                  class="fa fa-pencil m-r-5"></i>
                                Edit</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo "<tr><td colspan='7' class='text-center'>No payment methods found</td></tr>";
                  }
                  ?>
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

      <!-- //* Create Payment Methods Modal -->
      <div id="create_payment" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Create Payment Method</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="backend-add-authenticate/store-payment-method.php" method="POST">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Payment Method Name <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="method_name" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Payment Method Type <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="method_type" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Name <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="account_name" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Account Number <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="account_number" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Description <span style="color: gray;">(Optional)</span></label>
                      <textarea class="form-control" name="description"></textarea>
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

      <!-- //* Create Payment Methods Modal End -->

      <!-- //* All Modals End -->


    </div>
    <!-- //* Page Wrapper -->


  </div>
  <!-- end main wrapper-->


  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>

  <script>
    function toggleStatus(methodId, newStatus) {
      // Perform AJAX request to update the status
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "toggle-payment-status.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Check if the server response indicates success
          if (xhr.responseText.trim() === "success") {
            // Update the icon and text based on new status
            const icon = document.getElementById("status-icon-" + methodId);
            const text = document.getElementById("status-text-" + methodId);

            // Update icon and text
            icon.className = newStatus === 'active' ? "fa fa-dot-circle-o text-success" :
              "fa fa-dot-circle-o text-danger";
            text.innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
          } else {
            console.error("Failed to update status:", xhr.responseText);
          }
        }
      };
      xhr.send("method_id=" + methodId + "&status=" + newStatus);
    }
  </script>



</body>

</html>
