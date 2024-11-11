<?php
include '../layouts/db-connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['method_id'], $_POST['status'])) {
  $method_id = intval($_POST['method_id']);
  $new_status = $_POST['status'];

  // Prepare and execute the update query
  $query = "UPDATE tbl_payment_methods SET status = ? WHERE method_id = ?";
  if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("si", $new_status, $method_id);

    if ($stmt->execute()) {
      echo "success";
    } else {
      echo "error: " . $stmt->error;
    }
    $stmt->close();
  } else {
    echo "error: " . $conn->error;
  }

  $conn->close();
}
?>
