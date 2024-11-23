<?php
include '../layouts/db-connection.php';
require_once '../PHPMailer-OTP/classes/OTPVerification.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and sanitize form data
    $first_name = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $middle_name = isset($_POST['middlename']) ? trim($_POST['middlename']) : null;
    $last_name = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $phone_number = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
    $gender = isset($_POST['Gender']) ? trim($_POST['Gender']) : 'Others';
    $date_of_birth = isset($_POST['dateOfBirth']) ? trim($_POST['dateOfBirth']) : null;
    $age = isset($_POST['member_age']) ? intval($_POST['member_age']) : null;

    // Capture address fields
    $region_text = isset($_POST['region_text']) ? trim($_POST['region_text']) : null;
    $province_text = isset($_POST['province_text']) ? trim($_POST['province_text']) : null;
    $city_text = isset($_POST['city_text']) ? trim($_POST['city_text']) : null;
    $barangay_text = isset($_POST['barangay_text']) ? trim($_POST['barangay_text']) : null;

    // Create address
    $addressParts = array_filter([$region_text, $province_text, $city_text, $barangay_text]);
    $address = implode(', ', $addressParts);
    $status = 'Active';

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email)) {
        header('Location: ../add-member.php?error=empty_fields');
        exit;
    }

    // Validate date of birth
    if (empty($date_of_birth) || !strtotime($date_of_birth) || date('Y-m-d', strtotime($date_of_birth)) >= date('Y-m-d')) {
        header('Location: ../add-member.php?error=invalid_date_of_birth');
        exit;
    }

    // Check if email already exists
    $checkQuery = "SELECT email FROM tbl_add_members WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        header('Location: ../add-member.php?error=email_exists');
        exit;
    }
    $stmt->close();

    // Instantiate OTPVerification class
    $otpHandler = new OTPVerification($conn);

    // Check if the email is verified in `tbl_email_verification`
    if (!$otpHandler->isEmailVerified($email)) {
        header('Location: ../add-member.php?error=email_not_verified');
        exit;
    }

    // Insert the verified member into `tbl_add_members`
    $insertQuery = "INSERT INTO tbl_add_members 
                    (first_name, middle_name, last_name, phone_number, gender, date_of_birth, age, address, email, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param(
        "ssssssssss",
        $first_name,
        $middle_name,
        $last_name,
        $phone_number,
        $gender,
        $date_of_birth,
        $age,
        $address,
        $email,
        $status
    );

    if ($stmt->execute()) {
        // Success
        header('Location: ../add-member.php?success=added');
    } else {
        // Log detailed error for debugging
        error_log("Database error: " . $stmt->error);
        header('Location: ../add-member.php?error=database_error');
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
