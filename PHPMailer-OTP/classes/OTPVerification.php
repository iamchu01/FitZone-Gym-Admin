<?php
date_default_timezone_set('Asia/Manila'); // Adjust as needed

class OTPVerification
{
  private $conn;

  public function __construct($dbConnection)
  {
    $this->conn = $dbConnection;
  }

  /**
   * Check if the email is verified in the email verification table.
   */
  public function isEmailVerified($email)
  {
    $stmt = $this->conn->prepare("SELECT is_verified FROM tbl_email_verification WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['is_verified'] == '1';
    }

    return false; // Email not found or not verified
  }

  /**
   * Generate OTP and store/update it in the email verification table.
   */
  public function generateOTP($email)
  {
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // 6-digit OTP
    $otpExpiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Insert or update OTP for the email
    $stmt = $this->conn->prepare("
      INSERT INTO tbl_email_verification (email, otp_code, otp_expiry, is_verified)
      VALUES (?, ?, ?, 0)
      ON DUPLICATE KEY UPDATE otp_code = VALUES(otp_code), otp_expiry = VALUES(otp_expiry), is_verified = 0
    ");
    $stmt->bind_param("sss", $email, $otp, $otpExpiry);
    $stmt->execute();

    return $otp;
  }

  /**
   * Verify the OTP and mark the email as verified if OTP is valid.
   */
  public function verifyOTP($email, $otp)
  {
      $stmt = $this->conn->prepare("
          SELECT * FROM tbl_email_verification
          WHERE email = ? AND otp_code = ? AND otp_expiry > NOW() LIMIT 1
      ");
      $stmt->bind_param("ss", $email, $otp);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
          // Mark the email as verified
          $update = $this->conn->prepare("
              UPDATE tbl_email_verification
              SET is_verified = 1, otp_code = NULL, otp_expiry = NULL
              WHERE email = ?
          ");
          $update->bind_param("s", $email);
          if ($update->execute()) {
              return true; // OTP verified successfully
          }
      }
  
      return false; // OTP verification failed
  }
  

  /**
   * Move verified email data to the main table `tbl_add_members`.
   */
  public function moveToMainTable($email, $data)
  {
      if (!$this->isEmailVerified($email)) {
          return false; // Cannot move unverified email
      }
  
      // Insert verified member into the `tbl_add_members` table
      $stmt = $this->conn->prepare("
          INSERT INTO tbl_add_members (first_name, last_name, phone_number, gender, date_of_birth, age, address, email, status)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')
      ");
      $stmt->bind_param(
          "ssssssss",
          $data['first_name'],
          $data['last_name'],
          $data['phone_number'],
          $data['gender'],
          $data['date_of_birth'],
          $data['age'],
          $data['address'],
          $email
      );
  
      if ($stmt->execute()) {
          return true;
      }
  
      error_log("Error inserting into tbl_add_members: " . $stmt->error);
      return false;
  }
  
}
