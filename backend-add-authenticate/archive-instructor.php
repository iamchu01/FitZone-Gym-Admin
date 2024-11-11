<?php
include 'layouts/db-connection.php'; // Include the $conn variable for database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructorId = intval($_POST['id']);
    
    // Update the archive_status to 'Archived' and capture the date/time
    $archiveDate = date('Y-m-d H:i:s');
    $query = "UPDATE tbl_add_instructors SET archive_status = 'Archived', archive_at = '$archiveDate', status = 'Inactive' WHERE instructor_id = $instructorId";
    
    if ($conn->query($query) === TRUE) {
        // Return a plain success message for the JavaScript to handle
        echo "success";
    } else {
        echo "error";
    }
    
    $conn->close();
}
