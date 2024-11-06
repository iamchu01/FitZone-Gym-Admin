<?php
include 'layouts/db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];

    // Prepare and execute the SQL statement to fetch product details
    $sql = "SELECT products.product_name, products.product_description, category.category_name, 
            products.product_price, products.product_quantity, products.expire_date, products.created_at, 
            products.product_image 
            FROM products 
            INNER JOIN category ON products.category_id = category.category_id 
            WHERE products.product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Convert the image blob to a base64 string for displaying in an <img> tag
        if ($product['product_image']) {
            $product['product_image'] = 'data:image/jpeg;base64,' . base64_encode($product['product_image']);
        }
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found.']);
    }

    $stmt->close();
    $conn->close();
}
?>
