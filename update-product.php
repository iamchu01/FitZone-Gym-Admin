<?php
include 'layouts/db-connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    $product_quantity = $_POST['product_quantity'];
    $product_price = $_POST['product_price'];
    $expire_date = $_POST['expire_date'];

    echo "Product ID: " . htmlspecialchars($product_id) . "<br>";
    echo "Expire Date: " . htmlspecialchars($expire_date) . "<br>";


    // Check if an image was uploaded
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        // New image is uploaded, process it
        $image = file_get_contents($_FILES['product_image']['tmp_name']);
        $image_query = "product_image = ?";
        $image_param = $image;
    } else {
        // No new image, retain the existing image
        $image_query = ""; // No update on image
        $image_param = null; // No new image parameter
    }

    // Update the product
    $sql = "UPDATE products SET 
                product_name = ?, 
                category_id = ?, 
                product_description = ?, 
                product_quantity = ?, 
                product_price = ?, 
                expire_date = ? " . 
                ($image_query ? ", $image_query" : "") . 
                " WHERE product_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    if ($image_param) {
        $stmt->bind_param("sisdsssi", $product_name, $product_category, $product_description, $product_quantity, $product_price, $expire_date, $image_param, $product_id);
    } else {
        $stmt->bind_param("sisdssi", $product_name, $product_category, $product_description, $product_quantity, $product_price, $expire_date, $product_id);
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to edit-product.php with a success status
        header("Location: edit-product.php?id=$product_id&status=success");
        exit();
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

$conn->close();
?>
