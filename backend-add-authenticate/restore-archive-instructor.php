<?php
include 'layouts/db-connection.php'; // Include the $conn variable for database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructorId = intval($_POST['id']);

    // Update the archive_status to 'Unarchived' and set the status to 'Active'
    $query = "UPDATE tbl_add_instructors SET archive_status = 'Unarchived', status = 'Active' WHERE instructor_id = $instructorId";

    if ($conn->query($query) === TRUE) {
        echo "Instructor restored successfully.";
    } else {
        echo "Error restoring instructor: " . $conn->error;
    }

    $conn->close();
}
?>
