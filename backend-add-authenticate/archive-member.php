<?php
include 'layouts/db-connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $member_id = $conn->real_escape_string(trim($_POST['id']));

    // Update the database to archive the member
    $query = "UPDATE tbl_add_members SET archive_status = 'Archived', archive_at = NOW() WHERE member_id = '$member_id' AND status = 'Inactive'";

    if ($conn->query($query) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>