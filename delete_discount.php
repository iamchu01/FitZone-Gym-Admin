<?php
include 'layouts/db-connection.php'; // Include your DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_discount'])) {
    $discount_id = intval($_POST['discount_id']);
    
    // Prepare the SQL statement to prevent SQL injection
    $sql = "DELETE FROM discount WHERE discount_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $discount_id);

    if ($stmt->execute()) {
        // Redirect to the discount list with a success message
        header("Location: discount.php?delete=success");
        exit();
    } else {
        // Handle the error accordingly
        echo "Error deleting record: " . $conn->error;
    }
}
?>
