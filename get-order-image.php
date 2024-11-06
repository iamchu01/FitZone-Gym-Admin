<?php
include 'layouts/db-connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure the ID is an integer

    $sql = "SELECT product_image FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($image_data);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        header("Content-Type: image/*"); // Adjust content type based on your image format (e.g., image/png for PNG images)
        echo $image_data;
    } else {
        echo "Image not found.";
    }

    $stmt->close();
}

$conn->close();
?>

