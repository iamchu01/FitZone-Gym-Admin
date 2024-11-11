<?php
include 'layouts/db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method_name = $_POST['method_name'];
    $method_type = $_POST['method_type'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $description = $_POST['description'];

    $query = "INSERT INTO tbl_payment_methods (method_name, method_type, account_name, account_number, description, status)
              VALUES (?, ?, ?, ?, ?, 'inactive')";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssss", $method_name, $method_type, $account_name, $account_number, $description);

        if ($stmt->execute()) {
            header("Location: create-payment-method.php?success=1");
        } else {
            header("Location: create-payment-method.php?error=" . urlencode($stmt->error));
        }
        $stmt->close();
    } else {
        header("Location: create-payment-method.php?error=" . urlencode($conn->error));
    }

    $conn->close();
}
?>