<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'gymms';
$username = 'root'; // replace with your DB username
$password = ''; // replace with your DB password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
      $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE username = :username");
      $stmt->execute(['username' => $username]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($password, $user['password'])) {
        // Password matches
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
          header("Location: ../admin-dashboard.php");
        } elseif ($user['role'] === 'staff') {
          header("Location: staff-dashboard.php");
        }
        exit;
      } else {
        // Redirect back with error for invalid credentials
        header("Location: ../admin-login.php?error=invalid_credentials");
        exit;
      }
    } else {
      // Redirect back with error for empty fields
      header("Location: ../admin-login.php?error=empty_fields");
      exit;
    }
  }
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>
