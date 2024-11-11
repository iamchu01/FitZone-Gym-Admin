<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<?php
include 'layouts/db-connection.php';

// Check if an ID is passed
if (isset($_GET['id'])) {
  $plan_id = intval($_GET['id']);
  $query = "SELECT * FROM tbl_membership_plan WHERE plan_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $plan_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $plan = $result->fetch_assoc();
  } else {
    echo "Plan not found.";
    exit;
  }
} else {
  echo "No plan ID provided.";
  exit;
}
?>

<head>

  <title>Plan Details - HRMS admin template</title>

  <?php include 'layouts/title-meta.php'; ?>

  <?php include 'layouts/head-css.php'; ?>

</head>

<body>
  <div class="main-wrapper">
    <?php include 'layouts/menu.php'; ?>

    <div class="page-wrapper">
      <div class="content container-fluid">
        <div class="page-header">
          <div class="row">
            <div class="col-sm-12">
              <h3 class="page-title">Plan Details</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Plan Details</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Plan Overview -->
        <div class="col-md-4 d-flex">
          <div class="card profile-box flex-fill">
            <div class="card-body">
              <h3 class="card-title">Plan Overview</h3>
              <ul class="personal-info">
                <li>
                  <div class="title">Plan Name</div>
                  <div class="text text-dark"><?php echo htmlspecialchars($plan['plan_name']); ?></div>
                </li>
                <li>
                  <div class="title">Plan Type</div>
                  <div class="text text-dark"><?php echo htmlspecialchars($plan['plan_type']); ?></div>
                </li>
                <li>
                  <div class="title">Duration</div>
                  <div class="text text-dark"><?php echo htmlspecialchars($plan['duration_days']) . ' days'; ?></div>
                </li>
                <li>
                  <div class="title">Status</div>
                  <div class="text fs-6">
                    <span class="badge bg-<?php echo $plan['status'] === 'active' ? 'success' : 'danger'; ?>">
                      <?php echo ucfirst($plan['status']); ?>
                    </span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Pricing & Payment Method -->
        <div class="col-md-8 d-flex">
          <div class="card profile-box flex-fill">
            <div class="card-body">
              <h3 class="card-title">Pricing & Payment Method</h3>
              <ul class="personal-info">
                <li>
                  <div class="title">Pricing</div>
                  <div class="text text-dark">₱<?php echo number_format($plan['price'], 2); ?></div>
                </li>
                <?php
                // Check if the plan has payment methods listed
                if (!empty($plan['payment_method'])) {
                  $payment_method_names = explode(',', $plan['payment_method']);
                  foreach ($payment_method_names as $method_name) {
                    $method_name = trim($method_name);
                    $method_query = "SELECT method_name, account_name, account_number, description, status FROM tbl_payment_methods WHERE method_name = ?";
                    $method_stmt = $conn->prepare($method_query);
                    if ($method_stmt) {
                      $method_stmt->bind_param("s", $method_name);
                      $method_stmt->execute();
                      $method_result = $method_stmt->get_result();
                      if ($method_result->num_rows > 0) {
                        $method_row = $method_result->fetch_assoc();
                        ?>
                        <!-- Each payment method is grouped in a separate section -->
                        <li class="payment-method-section">
                          <div class="title">Method Name</div>
                          <div class="text text-dark"><?php echo htmlspecialchars($method_row['method_name']); ?></div>
                        </li>
                        <li>
                          <div class="title">Account Name</div>
                          <div class="text text-dark"><?php echo htmlspecialchars($method_row['account_name']); ?></div>
                        </li>
                        <li>
                          <div class="title">Account Number</div>
                          <div class="text text-dark"><?php echo htmlspecialchars($method_row['account_number']); ?></div>
                        </li>
                        <li>
                          <div class="title">Description</div>
                          <div class="text text-dark"><?php echo htmlspecialchars($method_row['description']); ?></div>
                        </li>
                        <li>
                          <div class="title">Status</div>
                          <div class="text">
                            <?php echo $method_row['status'] === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?>
                          </div>
                        </li>
                        <hr>
                        <?php
                      } else {
                        echo "<p class='text-muted'>No details found for payment method: $method_name.</p>";
                      }
                      $method_stmt->close();
                    } else {
                      echo "<p class='text-muted'>Error preparing query for payment method: $method_name.</p>";
                    }
                  }
                } else {
                  echo "<p class='text-muted'>No payment methods available for this plan.</p>";
                }
                ?>
              </ul>
            </div>
          </div>
        </div>

        <!-- Plan Description -->
        <div class="col-md-6 d-flex">
          <div class="card profile-box flex-fill">
            <div class="card-body">
              <h3 class="card-title">Plan Description</h3>
              <ul class="personal-info">
                <li>
                  <div class="title">Description</div>
                  <div class="text text-dark"><?php echo htmlspecialchars($plan['description'] ?: 'N/A'); ?></div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="col-md-12 d-flex">
          <div class="card profile-box flex-fill">
            <div class="card-body">
              <h3 class="card-title">Terms & Conditions</h3>
              <p>
                By subscribing to this plan, you agree to adhere to all gym policies, including access and usage
                guidelines. The subscription is non-transferable and non-refundable once activated. Any violations
                may result in suspension or termination of the subscription.
              </p>
            </div>
          </div>
        </div>

      </div>
      <!-- Page Content -->
    </div>
    <!-- Page Wrapper -->
  </div>
  <!-- end main wrapper-->

  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>



</body>

</html>
