<?php
include 'layouts/db-connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructor_id = $conn->real_escape_string(trim($_POST['id']));
    $new_status = $conn->real_escape_string(trim($_POST['status']));

    // Check that both values are present
    if (!empty($instructor_id) && ($new_status === 'Active' || $new_status === 'Inactive')) {
        $sql = "UPDATE tbl_add_instructors SET status = '$new_status' WHERE instructor_id = '$instructor_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Status updated successfully";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } else {
        echo "Invalid input";
    }
}
?>
