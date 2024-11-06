<?php
include 'layouts/db-connection.php';

$result = $conn->query("SELECT * FROM tbl_instructors");
$instructors = [];

while ($row = $result->fetch_assoc()) {
    $instructors[] = $row;
}

echo json_encode($instructors);
$conn->close();
?>
