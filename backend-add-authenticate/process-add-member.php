<?php
include '../layouts/db-connection.php';
require_once '../PHPMailer-OTP/classes/OTPVerification.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Capture and sanitize form data
  $first_name = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
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

  // Instantiate OTPVerification class
  $otpHandler = new OTPVerification($conn);

  // Check if the email is verified
  if (!$otpHandler->isEmailVerified($email)) {
    header('Location: ../add-member.php?error=email_not_verified');
    exit;
  }

  // Update member information for the verified email
  $updateQuery = "UPDATE tbl_add_members
                SET first_name = ?, last_name = ?, phone_number = ?, gender = ?, date_of_birth = ?, age = ?, address = ?, status = ?
                WHERE email = ? AND is_verified = '1'";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param(
    "sssssssss", // Corrected placeholders
    $first_name,
    $last_name,
    $phone_number,
    $gender,
    $date_of_birth,
    $age,
    $address,
    $status,
    $email // Now included
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


  if ($stmt->execute()) {
    // Success
    header('Location: ../add-member.php?success=added');
  } else {
    // Log detailed error for debugging (optional, remove in production)
    error_log("Database error: " . $stmt->error);
    header('Location: ../add-member.php?error=database_error');
  }

  // Close statement and connection
  $stmt->close();
  $conn->close();
}
?>
