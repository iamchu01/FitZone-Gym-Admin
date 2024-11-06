<?php
// Include database connection
include 'layouts/db-connection.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'add_discount' is set
    if (isset($_POST['add_discount'])) {
        // Retrieve form data
        $discount_name = $_POST['discount_name'];
        $discount_percentage = $_POST['discount_percentage'];

        // Validate input
        if (!empty($discount_name) && !empty($discount_percentage)) {
            // Prepare the SQL statement
            $sql = "INSERT INTO discount (discount_name, discount_percentage) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            // Bind parameters to the SQL query
            $stmt->bind_param("si", $discount_name, $discount_percentage);

            // Execute the query and check if successful
            if ($stmt->execute()) {
                // Success: redirect with a success message
                header("Location: discount.php?success=1");
                exit();
            } else {
                // Error occurred: display the error message
                echo "Error adding discount: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Please fill out all fields.";
        }
    }
}

// Close the database connection
$conn->close();
?>
