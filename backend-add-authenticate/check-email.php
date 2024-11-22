<?php
include '../layouts/db-connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    // Check if the email exists in the database
    $query = "SELECT email FROM tbl_add_members WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists
        echo json_encode(['exists' => true, 'status' => 'error', 'message' => 'Email already exists.']);
    } else {
        // Email doesn't exist
        echo json_encode(['exists' => false, 'status' => 'success']);
    }

    $stmt->close();
    $conn->close();
}
