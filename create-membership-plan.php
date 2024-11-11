<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>

  <title>Membership Plan</title>

  <?php include 'layouts/title-meta.php'; ?>

  <?php include 'layouts/head-css.php'; ?>

  <style>
    .form-select[multiple] {
      height: auto;
      /* Adjust the height as needed */
    }
  </style>

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
              <h3 class="page-title">Membership Plan</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Membership Plan</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#create_plan"><i
                  class="fa fa-plus">
                </i> Create Plan</a>
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
                    <th>Plan Name</th>
                    <th>Plan Type</th>
                    <th>Duration (in Days)</th>
                    <th>Pricing</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Fetch membership plans from the database
                  $query = "SELECT * FROM tbl_membership_plan ORDER BY plan_id DESC";
                  $result = $conn->query($query);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $plan_name = htmlspecialchars($row['plan_name']);
                      $plan_type = htmlspecialchars($row['plan_type']);
                      $price = htmlspecialchars($row['price']);
                      $duration_days = htmlspecialchars($row['duration_days']);
                      $payment_method = htmlspecialchars($row['payment_method']);
                      $description = htmlspecialchars($row['description']);
                      $status = htmlspecialchars($row['status']);

                      // Display status with appropriate label color
                      $status_label = $status === 'active' ? 'text-success' : 'text-danger';
                      ?>
                      <tr>
                        <td><?php echo $plan_name; ?></td>
                        <td><?php echo $plan_type; ?></td>
                        <td>₱<?php echo number_format($price, 2); ?></td>
                        <td><?php echo $duration_days . ' days'; ?></td>
                        <td><?php echo $payment_method; ?></td>
                        <td><?php echo $description ? $description : 'N/A'; ?></td>
                        <td>
                          <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown">
                              <i class="fa fa-dot-circle-o <?php echo $status_label; ?>"></i>
                              <span><?php echo ucfirst($status); ?></span>
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#"
                                onclick="updateStatus(<?php echo $row['plan_id']; ?>, 'active')"><i
                                  class="fa fa-dot-circle-o text-success"></i> Active</a>
                              <a class="dropdown-item" href="#"
                                onclick="updateStatus(<?php echo $row['plan_id']; ?>, 'inactive')"><i
                                  class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                            </div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i
                                class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="plan-details.php?id=<?php echo $row['plan_id']; ?>"><i
                                  class="fa fa-eye m-r-5"></i> View Details</a>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_plan"><i
                                  class="fa fa-pencil m-r-5"></i>
                                Edit</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo "<tr><td colspan='8' class='text-center'>No membership plans found</td></tr>";
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

      <!-- //* Create Membership Plan Modal -->
      <div id="create_plan" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Create Membership Plan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="backend-add-authenticate/store-membership-plan.php" method="POST">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Membership Plan Name <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="plan_name" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Membership Plan Type <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="plan_type" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label class="col-form-label">Pricing <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-text bg-default-light">₱</span>
                        <input class="form-control" type="number" name="price" step="0.01" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-form-label">Duration (in days) <span class="text-danger">*</span></label>
                      <input class="form-control" type="number" name="duration_days" required>
                    </div>
                  </div>

                  <!-- //* Payment Drop Down -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="payment-selector-dropdown" class="form-label">Payment Methods
                        <span class="text-danger">*</span></label>

                      <!-- Dropdown Trigger -->
                      <div class="dropdown">
                        <button class="btn btn-secondary w-100 text-start" type="button" id="paymentSelectorButton"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <span id="selected-options">Select Payment Methods</span>
                        </button>

                        <!-- Dropdown Menu with Checkboxes -->
                        <ul class="dropdown-menu w-100" aria-labelledby="paymentSelectorButton"
                          style="max-height: 200px; overflow-y: auto;">
                          <li>
                            <div class="form-check ms-3">
                              <input class="form-check-input" type="checkbox" id="selectAll"
                                onclick="toggleSelectAll()">
                              <label class="form-check-label" for="selectAll"><strong>Select All</strong></label>
                            </div>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <div id="payment-options">
                            <?php include 'fetch-payment-methods.php'; ?>
                          </div>
                        </ul>
                      </div>

                      <!-- <small id="selected-count" class="text-muted">0 methods selected</small> -->
                    </div>
                  </div>

                  <!-- //* Payment Drop Down -->

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

      <!-- //* Create Membership Plan Modal End -->

      <!-- //* All Modals End -->


    </div>
    <!-- //* Page Wrapper -->


  </div>
  <!-- end main wrapper-->


  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>

  <script>
    function toggleSelectAll() {
      const selectAllCheckbox = document.getElementById("selectAll");
      const checkboxes = document.querySelectorAll("#payment-options .form-check-input");
      checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
      updateSelectedOptions();
    }

    function updateSelectedOptions() {
      const checkboxes = document.querySelectorAll("#payment-options .form-check-input:checked");
      const selectedSummary = Array.from(checkboxes).map(checkbox => checkbox.nextElementSibling.textContent);
      const displayText = selectedSummary.length > 0 ? selectedSummary.join(", ") : "Select Payment Methods";
      document.getElementById("selected-options").textContent = displayText;

      // Uncheck 'Select All' if not all options are selected
      document.getElementById("selectAll").checked = checkboxes.length === document.querySelectorAll(
        "#payment-options .form-check-input").length;
    }
  </script>


</body>

</html>
