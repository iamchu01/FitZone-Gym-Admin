<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

class EmailSender
{
  public static function sendOTP($recipientEmail, $otp)
  {
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'altchu02@gmail.com'; // Replace with your email
      $mail->Password = 'dkjt nyvp nqxi fucx'; // Replace with your email password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('altchu02@gmail.com', 'Your App Name');
      $mail->addAddress($recipientEmail);

      $mail->isHTML(true);
      $mail->Subject = 'Your OTP Code';
      $mail->Body = "<p>Your OTP code is: <strong>$otp</strong></p><p>This code is valid for 10 minutes.</p>";

      $mail->send();
      return true;
    } catch (Exception $e) {
      return 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
}
