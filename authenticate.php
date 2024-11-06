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
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Check if email and password are provided
        if (!empty($email) && !empty($password)) {
            // Query to get the admin record
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && $password === $admin['password']) {
                // Password matches (note: consider hashing in production)
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['name'];
                header("Location: admin-dashboard.php");
                exit;
            } else {
                // Invalid credentials
                echo "<script>alert('Invalid email or password');</script>";
                header("Refresh: 0; URL=index.php");
            }
        } else {
            echo "<script>alert('Please fill in all fields');</script>";
            header("Refresh: 0; URL=login.php");
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
