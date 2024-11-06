<?php
include 'layouts/db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id'])) {
        $productId = intval($_POST['product_id']);
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
?>
