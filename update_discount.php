<?php
// Include the database connection
include 'layouts/db-connection.php';
session_start();
if (isset($_POST['edit_discount'])) {
    // Retrieve form data
    $discount_id = intval($_POST['discount_id']);
    $discount_name = mysqli_real_escape_string($conn, $_POST['discount_name']);
    $discount_percentage = floatval($_POST['discount_percentage']);

    // SQL query to update the discount
    $sql = "UPDATE discount SET discount_name = '$discount_name', discount_percentage = $discount_percentage WHERE discount_id = $discount_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the discount list page after a successful update
        header("Location: discount.php?update=success");
    } else {
        echo "Error updating discount: " . $conn->error;
    }
}
?>
