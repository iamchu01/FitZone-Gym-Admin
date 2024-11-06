<?php
include 'layouts/db-connection.php';

if (isset($_GET['category_id'])) {
    $categoryId = intval($_GET['category_id']);
    
    $sql = "SELECT * FROM category WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        // Convert image to base64 if necessary
        if ($category['category_image']) {
            $category['category_image'] = base64_encode(file_get_contents($category['category_image']));
        }
        echo json_encode($category);
    } else {
        echo json_encode([]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>
