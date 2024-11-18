<?php
date_default_timezone_set('Asia/Manila'); // Adjust as needed
class OTPVerification
{
  private $conn;

  public function __construct($dbConnection)
  {
    $this->conn = $dbConnection;
  }

  public function isEmailVerified($email)
  {
    $stmt = $this->conn->prepare("SELECT is_verified FROM tbl_add_members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['is_verified'] == '1';
    } else {
      return false; // Email not found
    }
  }

  public function generateOTP($email)
  {
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $otpExpiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $stmt = $this->conn->prepare("SELECT * FROM tbl_add_members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $stmt = $this->conn->prepare("UPDATE tbl_add_members SET otp_code = ?, otp_expiry = ?, is_verified = 0 WHERE email = ?");
      $stmt->bind_param("sss", $otp, $otpExpiry, $email);
    } else {
      $stmt = $this->conn->prepare("INSERT INTO tbl_add_members (email, otp_code, otp_expiry) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $email, $otp, $otpExpiry);
    }
    $stmt->execute();

    return $otp;
  }

  public function verifyOTP($email, $otp)
  {
    $stmt = $this->conn->prepare("SELECT * FROM tbl_add_members WHERE email = ? AND otp_code = ? AND otp_expiry > NOW() LIMIT 1");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $update = $this->conn->prepare("UPDATE tbl_add_members SET is_verified = 1, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
      $update->bind_param("s", $email);
      if ($update->execute()) {
        error_log("is_verified updated successfully for email: " . $email);
        return true;
      } else {
        error_log("Failed to update is_verified for email: " . $email);
      }
    } else {
      error_log("OTP verification failed for email: " . $email);
    }

    return false;
  }

}
