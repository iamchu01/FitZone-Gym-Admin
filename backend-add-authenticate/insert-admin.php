<?php
// Database connection
$host = 'localhost';
$dbname = 'gymms';
$username = 'root'; // replace with your DB username
$password = ''; // replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hash the default admin password
    $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);

    // Insert the default admin user
    $stmt = $pdo->prepare("INSERT INTO tbl_users (username, password, name, role) VALUES (:username, :password, :name, :role)");
    $stmt->execute([
        ':username' => 'admin',
        ':password' => $hashedPassword,
        ':name' => 'Admin User',
        ':role' => 'admin'
    ]);

    echo "Default admin user inserted successfully.";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
