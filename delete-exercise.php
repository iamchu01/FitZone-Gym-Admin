<?php
session_start();
include 'layouts/db-connection.php';

if (isset($_GET['id'])) {
    $exercise_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM muscle_exercise WHERE me_id = ?");
    $stmt->bind_param("i", $exercise_id);

    if ($stmt->execute()) {
        // Redirect back to the last accessed page with a success message
        $redirect_url = $_SESSION['last_accessed'] ?? 'index.php'; // Fallback to index.php if not set
        header("Location: $redirect_url?message=Exercise%20deleted%20successfully");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo '<p>Error deleting exercise.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Invalid request.</p>';
}
?>
