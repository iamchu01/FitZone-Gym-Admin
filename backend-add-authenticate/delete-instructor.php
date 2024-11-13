<?php
include '../layouts/db-connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $instructor_id = $conn->real_escape_string(trim($_POST['id']));

  $sql = "DELETE FROM tbl_instructors WHERE instructor_id = '$instructor_id'";

  if ($conn->query($sql) === TRUE) {
    echo "Instructor deleted permanently.";
  } else {
    echo "Error deleting instructor: " . $conn->error;
  }
}
?>
