<?php
session_start();
include 'layouts/db-connection.php';

if (isset($_GET['muscle_group'])) {
    $muscle_group = $_GET['muscle_group'];

    // Prepare the SQL statement to fetch exercises by muscle group
    $stmt = $conn->prepare("SELECT me_id, me_name, me_description FROM muscle_exercise WHERE muscle_category = ?");
    $stmt->bind_param("s", $muscle_group);
    $stmt->execute();
    $result = $stmt->get_result();

    $exercises = [];
    while ($row = $result->fetch_assoc()) {
        $exercises[] = [
            'me_id' => $row['me_id'],
            'me_name' => $row['me_name'],
            'me_description' => $row['me_description']
        ];
    }

    if (!empty($exercises)) {
        echo json_encode($exercises); // Return the exercises as JSON
    } else {
        echo json_encode(['error' => 'No exercises found for this muscle group']);
    }

    $stmt->close(); 
    $conn->close();
} else {
    echo json_encode(['error' => 'No muscle group provided']);
}
?>
