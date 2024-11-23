<?php
// Include the database connection file
include 'layouts/db-connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $exerciseName = $_POST['exercise_name'];
    $muscleCategory = $_POST['muscle_category'];  // This will be 'chest' by default
    $description = $_POST['exercise_description'];

    // Handle file upload (image or video)
    if (isset($_FILES['exercise_image']) && $_FILES['exercise_image']['error'] == 0) {
        // Get the file data
        $fileTmpName = $_FILES['exercise_image']['tmp_name'];
        $fileType = $_FILES['exercise_image']['type'];
        $fileName = $_FILES['exercise_image']['name']; // File name for storage
        $uploadDir = 'uploads/';  // Define your upload directory
        
        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Determine where to save the file
        $filePath = $uploadDir . basename($fileName);
        
        // Check if the uploaded file is an image or a video
        if (strpos($fileType, 'image') !== false) {
            move_uploaded_file($fileTmpName, $filePath);
            $mediaColumn = 'me_image';
            $mediaData = $filePath;  // Store the file path in the database
            $mediaType = $fileType;
        } 
        elseif (strpos($fileType, 'video') !== false) {
            move_uploaded_file($fileTmpName, $filePath);
            $mediaColumn = 'me_video';
            $mediaData = $filePath;  // Store the file path in the database
            $mediaType = $fileType;
        } else {
            echo "The uploaded file is neither an image nor a video.";
            exit;
        }

        // Insert the exercise into the database
        $stmt = $conn->prepare("INSERT INTO muscle_exercise (me_name, muscle_category, me_description, $mediaColumn, me_media_type) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sssss", $exerciseName, $muscleCategory, $description, $fileName, $mediaType);
            if ($stmt->execute()) {
                // Success message
              
                // Optionally redirect after a brief delay
                header("refresh:3;url=chest-exercises.php"); // Replace with your exercises page URL
                exit;
            } else {
                echo "Error executing query: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Statement preparation failed: " . $conn->error;
        }
    } else {
        echo "Error with file upload.";
        exit;
    }
}

$conn->close();
?>
