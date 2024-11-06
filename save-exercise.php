<?php
session_start(); // Start the session if not already started
include 'layouts/db-connection.php';


// Handle add/edit action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exercise_name = $_POST['exerciseName'];
    $exercise_description = $_POST['exerciseDescription'];
    $muscle_category = $_POST['muscleCategory'];
    $exercise_id = $_POST['exercise_id'] ?? null; // Use null if not set

    // Initialize variable for image
    $me_image = null; // Default value for the image

    // Handle image upload (optional)
    if (isset($_FILES['exerciseImage']) && $_FILES['exerciseImage']['error'] == UPLOAD_ERR_OK) {
        $me_image = file_get_contents($_FILES['exerciseImage']['tmp_name']);
    }

    if ($exercise_id) {
        // Fetch existing exercise data for update
        $stmt = $conn->prepare("SELECT me_image FROM muscle_exercise WHERE me_id = ?");
        $stmt->bind_param("i", $exercise_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $existing_exercise = $result->fetch_assoc();

            // Use existing image if no new image is uploaded
            if (!$me_image) {
                $me_image = $existing_exercise['me_image'];
            }

            // Update logic
            $update_stmt = $conn->prepare("UPDATE muscle_exercise SET me_name=?, me_description=?, muscle_category=?, me_image=? WHERE me_id=?");
            $update_stmt->bind_param("ssssi", $exercise_name, $exercise_description, $muscle_category, $me_image, $exercise_id);

            if ($update_stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Exercise updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating exercise: ' . htmlspecialchars($update_stmt->error)]);
            }
            $update_stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: Exercise not found.']);
        }
        $stmt->close(); // Close the statement after execution
    } else {
        // Ensure that me_image is set before adding
        if ($me_image) {
            // Prepare the INSERT statement
            $add_stmt = $conn->prepare("INSERT INTO muscle_exercise (me_name, me_description, muscle_category, me_image) VALUES (?, ?, ?, ?)");
            $add_stmt->bind_param("ssss", $exercise_name, $exercise_description, $muscle_category, $me_image);
        
            // Execute the statement
            if ($add_stmt->execute()) {
                $last_id = $conn->insert_id; // This gets the auto-generated ID
                echo json_encode(['success' => true, 'message' => 'Exercise added successfully with ID: ' . $last_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error adding exercise: ' . htmlspecialchars($add_stmt->error)]);
            }
            $add_stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: An image is required when adding a new exercise.']);
        }   
    }

    // End script execution after sending the response
    exit();
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit();
}
?>
