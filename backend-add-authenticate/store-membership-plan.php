<?php
include '../layouts/db-connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $plan_name = $_POST['plan_name'];
  $plan_type = $_POST['plan_type'];
  $price = $_POST['price'];
  $duration_days = $_POST['duration_days'];
  // Convert selected payment methods to a comma-separated string
  $payment_method = isset($_POST['payment_method']) ? implode(',', $_POST['payment_method']) : '';
  $description = $_POST['description'];

  // Prepare SQL query to insert membership plan with multiple payment methods as a comma-separated string
  $query = "INSERT INTO tbl_membership_plan (plan_name, plan_type, price, duration_days, payment_method, description, status)
              VALUES (?, ?, ?, ?, ?, ?, 'inactive')";

  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ssisss", $plan_name, $plan_type, $price, $duration_days, $payment_method, $description);

    if ($stmt->execute()) {
      header("Location: ../create-membership-plan.php?success=1"); // Redirect on success
    } else {
      header("Location: ../create-membership-plan.php?error=" . urlencode($stmt->error)); // Redirect with error
    }
    $stmt->close();
  } else {
    header("Location: ../create-membership-plan.php?error=" . urlencode($conn->error));
  }

  $conn->close();
}
?>
