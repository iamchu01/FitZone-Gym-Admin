<?php
include '../layouts/db-connection.php';
require_once __DIR__ . '/classes/OTPVerification.php';
require_once __DIR__ . '/classes/EmailSender.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $otp = isset($_POST['otp']) ? trim($_POST['otp']) : null;
  $action = $_POST['action'];

  $otpHandler = new OTPVerification($conn);

  if ($action === 'send') {
    // Generate and send OTP
    $generatedOTP = $otpHandler->generateOTP($email);
    $result = EmailSender::sendOTP($email, $generatedOTP);

    if ($result === true) {
      $response = ['status' => 'success', 'message' => 'OTP has been sent to your email.'];
    } else {
      $response = ['status' => 'error', 'message' => 'Failed to send OTP.'];
    }
  } elseif ($action === 'verify') {
    // Verify OTP
    if ($otpHandler->verifyOTP($email, $otp)) {
      $response = ['status' => 'success', 'message' => 'OTP verified successfully!'];
    } else {
      $response = ['status' => 'error', 'message' => 'Invalid or expired OTP.'];
    }
  }
}

echo json_encode($response);
?>
