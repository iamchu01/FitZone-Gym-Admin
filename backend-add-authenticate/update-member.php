<?php
include '.layouts/db-connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $member_id = intval($_POST['member_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Construct query
    $update_query = "UPDATE tbl_add_members SET 
        first_name = '$first_name',
        middle_name = '$middle_name',
        last_name = '$last_name',
        email = '$email',
        phone_number = '$phone_number',
        date_of_birth = '$date_of_birth',
        gender = '$gender',
        address = '$address'
        WHERE member_id = $member_id";

    // Execute query
    if (mysqli_query($conn, $update_query)) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
        // Log and return error
        error_log("Update failed: " . mysqli_error($conn)); // For server logs
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
