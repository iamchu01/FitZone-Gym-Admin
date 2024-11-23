<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>

  <title>Membership Plan</title>

  <?php include 'layouts/title-meta.php'; ?>
  <?php require_once('vincludes/load.php'); ?>  
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

        <!-- //*success message after adding member -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
            <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                Successfully Created!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                    <!-- <th>Plan Name</th> -->
                    <th>Plan Type</th>
                    <th>Duration (in Days)</th>
                    <th>Membership Fee</th>
                    <!-- <th>Payment Method</th> -->
                    <th>Description</th>
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
            $plan_type = htmlspecialchars($row['plan_type']);
            $duration_days = htmlspecialchars($row['duration_days']);
            $price = htmlspecialchars($row['price']);
            $description = htmlspecialchars($row['description']);
            $status = htmlspecialchars($row['status']);

            // Display status with appropriate label color
            $status_label = $status === 'active' ? 'text-success' : 'text-danger';
            ?>
            <tr>
                <td><?php echo $plan_type; ?></td>
                <td><?php echo $duration_days . ' days'; ?></td>
                <td>₱<?php echo number_format($price, 2); ?></td>
                <td><?php echo !empty($description) ? $description : 'N/A'; ?></td>
                <td>
                    <div class="dropdown action-label">
                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-dot-circle-o <?php echo $status_label; ?>"></i>
                            <span><?php echo ucfirst($status); ?></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['plan_id']; ?>, 'active')">
                                <i class="fa fa-dot-circle-o text-success"></i> Active
                            </a>
                            <a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['plan_id']; ?>, 'inactive')">
                                <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                            </a>
                        </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="plan-details.php?id=<?php echo $row['plan_id']; ?>">
                                <i class="fa fa-eye m-r-5"></i> View Details
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_plan">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                            </a>
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
                                <label class="col-form-label">Membership Plan Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="planType" name="plan_type" required onchange="updatePlanDetails()">
                                    <option value="" disabled selected>Select Plan Type</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Half-Month">Half-Month</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-form-label">Duration (In Days)</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" id="durationDays" name="duration_days" readonly required>
                                    <span class="input-group-text">Days</span>
                                </div>
                            </div>
                        </div>

                        <!-- Toggle for Student/Regular Rates -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Rate Type</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="rateToggle" name="rate_type" value="Regular" onchange="updatePlanDetails()">
                                    <label class="form-check-label" for="rateToggle">Toggle for Regular Rate</label>
                                </div>
                            </div>
                        </div>

                        <!-- Membership Fee -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Membership Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-default-light">₱</span>
                                    <input class="form-control" type="number" id="planPrice" name="price" readonly required>
                                </div>
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
      document.getElementById("selectAll").checked = checkboxes.length === document.querySelectorAll(
        "#payment-options .form-check-input").length;
    }
  </script>

  <script>
    function updateStatus(planId, status) {
      // Send an AJAX request to update the status
      fetch('backend-add-authenticate/update-plan-status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ plan_id: planId, status: status })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload the page or update the status element
            location.reload();
          } else {
            alert("Error updating status: " + data.message);
          }
        })
        .catch(error => console.error('Error:', error));
    }
  </script>


<script>
  function updateDurationDays() {
    const planType = document.getElementById('planType').value;
    const durationField = document.getElementById('durationDays');

    switch (planType) {
      case 'Monthly':
        durationField.value = "30 Days"; // Assuming 30 days for a month
        break;
      case 'Weekly':
        durationField.value = "7 Days"; // 7 days for a week
        break;
      case 'Half-Month':
        durationField.value = "15 Days"; // 15 days for half a month
        break;
      default:
        durationField.value = ''; // Clear the field if no valid selection
    }
  }
</script>
<script>
    function updatePlanDetails() {
    const planType = document.getElementById('planType').value;
    const rateToggle = document.getElementById('rateToggle').checked; // Regular rate if true
    const durationField = document.getElementById('durationDays');
    const priceField = document.getElementById('planPrice');

    // Prices for student and regular rates
    const prices = {
        Daily: { student: 60.00, regular: 80.00 },
        Weekly: { student: 200.00, regular: 250.00 },
        'Half-Month': { student: 350.00, regular: 450.00 },
        Monthly: { student: 600.00, regular: 800.00 }
    };

    // Durations for each plan type
    const durations = {
        Daily: 1,        // 1 day
        Weekly: 7,       // 7 days
        'Half-Month': 15, // 15 days
        Monthly: 30      // 30 days
    };

    // Determine the rate type (student or regular)
    const rateType = rateToggle ? 'regular' : 'student';

    // Set the duration and price based on the selected plan type and rate
    if (planType in prices && planType in durations) {
        durationField.value = durations[planType];
        priceField.value = prices[planType][rateType].toFixed(2); // Display price with 2 decimal places
    } else {
        durationField.value = ''; // Clear the field if no valid selection
        priceField.value = '';
    }
}

  </script>

  <script>
    const successAlert = document.getElementById("successAlert");
  if (successAlert) {
      setTimeout(() => {
          successAlert.classList.remove("show");
          successAlert.classList.add("fade");
          setTimeout(() => {
              successAlert.remove();
          }, 300);
      }, 5000);
  }

    // Clean up URL Parameters
    const url = new URL(window.location.href);
  if (url.searchParams.has("success") || url.searchParams.has("error")) {
      url.searchParams.delete("success");
      url.searchParams.delete("error");
      history.replaceState(null, "", url);
  }
  </script>



</body>

</html>
