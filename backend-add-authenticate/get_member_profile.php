<?php

// Check if an ID is passed
if (isset($_GET['id'])) {
  $member_id = intval($_GET['id']);
  $query = "SELECT * FROM tbl_add_members WHERE member_id = $member_id";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) {
    $member = $result->fetch_assoc();
  } else {
    echo "Member not found.";
    exit;
  }
} else {
  echo "No member ID provided.";
  exit;
}

?>
