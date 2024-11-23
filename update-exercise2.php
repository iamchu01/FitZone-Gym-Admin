<?php
// Include your database connection
include 'layouts/db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $exerciseId = $_POST['exercise_id'];
    $exerciseName = $_POST['exercise_name'];
    $description = $_POST['exercise_description'];

    // File handling
    $mediaColumn = '';
    $mediaFileName = '';
    $mediaType = ''; // To track file type (e.g., image/png, video/mp4)

    if (isset($_FILES['exercise_image']) && $_FILES['exercise_image']['error'] == 0) {
        $fileTmpName = $_FILES['exercise_image']['tmp_name'];
        $fileName = $_FILES['exercise_image']['name'];
        $fileType = mime_content_type($fileTmpName); // Get MIME type dynamically
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($fileName);

        // Validate and determine file type
        if (strpos($fileType, 'image/') === 0) {
            $mediaColumn = 'me_image';
            $mediaType = $fileType; // Store MIME type (e.g., image/png)
        } elseif (strpos($fileType, 'video/') === 0) {
            $mediaColumn = 'me_video';
            $mediaType = $fileType; // Store MIME type (e.g., video/mp4)
        } else {
            die("Invalid file type. Only images and videos are allowed.");
        }

        // Move uploaded file to the server
        if (!move_uploaded_file($fileTmpName, $filePath)) {
            die("Error uploading file.");
        }

        $mediaFileName = $fileName;
    }

    // Begin database update
    $conn->begin_transaction();

    try {
        // Clear existing media columns if new media is uploaded
        if ($mediaColumn && $mediaFileName) {
            // Determine the column to nullify
            $nullifyColumn = $mediaColumn === 'me_image' ? 'me_video' : 'me_image';

            // Update media column and nullify the other one
            $query = "UPDATE muscle_exercise 
                      SET me_name = ?, me_description = ?, $mediaColumn = ?, $nullifyColumn = NULL, me_media_type = ? 
                      WHERE me_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $exerciseName, $description, $mediaFileName, $mediaType, $exerciseId);
        } else {
            // Update without media changes
            $query = "UPDATE muscle_exercise 
                      SET me_name = ?, me_description = ? 
                      WHERE me_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $exerciseName, $description, $exerciseId);
        }

        // Execute the query
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect on success
        header("Location: shoulder-exercises.php?success=1");
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        die("Error updating exercise: " . $e->getMessage());
    }
}
?>