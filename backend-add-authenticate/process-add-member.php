<?php
include 'layouts/db-connection.php'; // Include the $conn variable for mysqli

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and sanitize form data
    $first_name = isset($_POST['firstname']) ? $conn->real_escape_string(trim($_POST['firstname'])) : null;
    $last_name = isset($_POST['lastname']) ? $conn->real_escape_string(trim($_POST['lastname'])) : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : null;
    $phone_number = isset($_POST['mobile']) ? $conn->real_escape_string(trim($_POST['mobile'])) : null;
    $gender = isset($_POST['Gender']) ? $conn->real_escape_string(trim($_POST['Gender'])) : 'Others';
    $date_of_birth = isset($_POST['dateOfBirth']) ? $conn->real_escape_string(trim($_POST['dateOfBirth'])) : null;
    $age = isset($_POST['member_age']) ? intval($_POST['member_age']) : null;

    // Capture address fields
    $region_text = isset($_POST['region_text']) ? $conn->real_escape_string(trim($_POST['region_text'])) : null;
    $province_text = isset($_POST['province_text']) ? $conn->real_escape_string(trim($_POST['province_text'])) : null;
    $city_text = isset($_POST['city_text']) ? $conn->real_escape_string(trim($_POST['city_text'])) : null;
    $barangay_text = isset($_POST['barangay_text']) ? $conn->real_escape_string(trim($_POST['barangay_text'])) : null;

    $address = "$region_text, $province_text, $city_text, $barangay_text";
    $status = 'Active';

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($gender) || empty($date_of_birth) || empty($address)) {
        header('Location: add-member.php?error=empty_fields');
        exit;
    }

    // Check if email already exists
    $emailCheckQuery = "SELECT email FROM tbl_add_members WHERE email = '$email'";
    $emailCheckResult = $conn->query($emailCheckQuery);

    if ($emailCheckResult->num_rows > 0) {
        // Email already exists
        header('Location: add-member.php?error=email_exists');
        exit;
    }

    // Insert data into the database
    $sql = "INSERT INTO tbl_add_members (first_name, last_name, email, phone_number, gender, date_of_birth, age, address, status) 
            VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$gender', '$date_of_birth', '$age', '$address', '$status')";

    if ($conn->query($sql) === TRUE) {
        header('Location: add-member.php?success=added');
        exit;
    } else {
        // Log detailed error for debugging (optional, remove in production)
        error_log("Database error: " . $conn->error);
        header('Location: add-member.php?error=database_error');
        exit;
    }
}
?>
