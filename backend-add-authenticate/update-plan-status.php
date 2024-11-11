<?php
include '../layouts/db-connection.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$plan_id = $input['plan_id'];
$status = $input['status'];

// Validate inputs
if (!isset($plan_id) || !isset($status)) {
  echo json_encode(['success' => false, 'message' => 'Invalid input.']);
  exit;
}

// Prepare the update query
$query = "UPDATE tbl_membership_plan SET status = ? WHERE plan_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $plan_id);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Database update failed.']);
}

$stmt->close();
$conn->close();
?>
