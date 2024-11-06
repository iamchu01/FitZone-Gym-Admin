<?php
session_start();
include 'layouts/db-connection.php';

if (isset($_GET['exercise_id'])) {
    $exercise_id = $_GET['exercise_id'];

    // Prepare the SQL statement to fetch the exercise details
    $stmt = $conn->prepare("SELECT me_id, me_name, me_description, muscle_category, me_image FROM muscle_exercise WHERE me_id = ?");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $exercise = $result->fetch_assoc();

        // Convert BLOB image data to base64 for displaying in the modal
        if ($exercise['me_image']) {
            $exercise['me_image'] = 'data:image/jpeg;base64,' . base64_encode($exercise['me_image']);
        }

        // Return the exercise details as JSON
        echo json_encode($exercise);
    } else {
        echo json_encode(['error' => 'Exercise not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'No exercise ID provided']);
}
?>
