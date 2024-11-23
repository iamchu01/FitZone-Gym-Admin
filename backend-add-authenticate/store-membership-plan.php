<?php
include '../layouts/db-connection.php'; // Ensure this path is correct
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plan_type = $_POST['plan_type'] ?? '';
  $duration_days = (int) ($_POST['duration_days'] ?? 0);
  $price = (float) ($_POST['price'] ?? 0.00);
  $rate_type = $_POST['rate_type'] === 'Regular' ? 'Regular' : 'Student'; // Default to Student
  $description = $_POST['description'] ?? '';
  $status = 'active';

  // Prepare the statement
  $stmt = $conn->prepare("
      INSERT INTO tbl_membership_plan (plan_type, duration_days, price, rate_type, description, status)
      VALUES (?, ?, ?, ?, ?, ?)
  ");

  if ($stmt) {
      // Correct bind_param with 6 placeholders: `sids` for types and strings
      $stmt->bind_param('sidsss', $plan_type, $duration_days, $price, $rate_type, $description, $status);

      if ($stmt->execute()) {
          header('Location: ../create-membership-plan.php?success=added');
          exit;
      } else {
          echo "Database insertion failed: " . $stmt->error;
      }

      $stmt->close();
  } else {
      echo "Failed to prepare the database statement: " . $conn->error;
  }
}


$conn->close();
?>
