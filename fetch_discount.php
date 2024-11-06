<?php
// Include the database connection
include 'layouts/db-connection.php';

if (isset($_GET['discount_id'])) {
    $discount_id = intval($_GET['discount_id']);

    // Query to fetch the discount details by discount_id
    $sql = "SELECT * FROM discount WHERE discount_id = $discount_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $discount = $result->fetch_assoc();
        // Return the discount details as JSON
        echo json_encode($discount);
    } else {
        echo json_encode(['error' => 'Discount not found']);
    }
}
?>
