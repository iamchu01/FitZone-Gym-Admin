<?php
include 'layouts/db-connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data for updating category
    $category_id = intval($_POST['category_id']);
    $category_name = $_POST['category_name'];
    $category_code = $_POST['category_code'];
    $category_description = $_POST['category_description'];
    
    // Handle file upload
    $category_image = null;
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
        $category_image = file_get_contents($_FILES['category_image']['tmp_name']);
    }

    // Debug: Check the file upload status
    if ($category_image) {
        echo "File upload successful. File size: " . strlen($category_image) . " bytes.<br>";
    } else {
        echo "No file uploaded or file upload error.<br>";
    }

    // Prepare SQL statement for updating the category
    $sql = "UPDATE category SET category_name = ?, category_code = ?, category_description = ?, category_image = ? WHERE category_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssbsi", $category_name, $category_code, $category_description, $category_image, $category_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'Category updated successfully';
        } else {
            echo 'Error updating category: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Failed to prepare SQL statement: ' . $conn->error;
    }

    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Fetch category data for GET request
    $category_id = intval($_GET['id']);
    
    // Prepare SQL statement to fetch category data
    $sql = "SELECT * FROM category WHERE category_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $category = $result->fetch_assoc();
            echo json_encode(["status" => "success", "category" => $category]);
        } else {
            echo json_encode(["status" => "error", "message" => "Category not found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare SQL statement."]);
    }

    $conn->close();
} else {
    echo 'Invalid request method';
}
?>
