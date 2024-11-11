<?php

// Check if an ID is passed
if (isset($_GET['id'])) {
  $instructor_id = intval($_GET['id']);
  $query = "SELECT * FROM tbl_add_instructors WHERE instructor_id = $instructor_id";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
  } else {
    echo "Instructor not found.";
    exit;
  }
} else {
  echo "No instructor ID provided.";
  exit;
}

?>
