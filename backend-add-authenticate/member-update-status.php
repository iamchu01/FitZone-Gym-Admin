<?php
include '../layouts/db-connection.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $memberId = isset($_POST['id']) ? intval($_POST['id']) : null;
  $newStatus = isset($_POST['status']) ? $conn->real_escape_string(trim($_POST['status'])) : null;

  if ($memberId && $newStatus) {
    $sql = "UPDATE tbl_add_members SET status = '$newStatus' WHERE member_id = $memberId";
    if ($conn->query($sql) === TRUE) {
      echo "success";
    } else {
      error_log("Database error: " . $conn->error);
      echo "error";
    }
  } else {
    echo "invalid_data";
  }
}
?>
