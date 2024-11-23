<?php
session_start();
include 'layouts/db-connection.php';

if (isset($_GET['id'])) {
    $exercise_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM muscle_exercise WHERE me_id = ?");
    $stmt->bind_param("i", $exercise_id);

    if ($stmt->execute()) {
        // Redirect back to the referring page after deletion
        $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php'; // Fallback to 'index.php' if not set
        header("Location: $redirect_url");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo '<p>Error deleting exercise.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Invalid request.</p>';
}

// Close the database connection
$conn->close();
?>
