<?php
include 'layouts/db-connection.php';  // Include your database connection file

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the member from the database using the provided ID
    $sql = "SELECT * FROM members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();  // Fetch the member details
        echo json_encode($row);  // Return the member data as JSON
    } else {
        echo json_encode(['error' => 'Member not found']);
    }
}
?>
