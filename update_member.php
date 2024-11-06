<?php
// Database connection
include 'layouts/db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['edit_id'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $middlename = $_POST['middle_name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phone'];
    $age = $_POST['age'];
    $region = $_POST['region_text'];
    $province = $_POST['province_text'];
    $city = $_POST['city_text'];
    $barangay = $_POST['barangay_text'];

    // Update the member data
    $sql = "UPDATE members SET firstname = '$firstname', lastname = '$lastname', middlename = '$middlename', email = '$email', phonenumber = '$phonenumber', age = '$age', region = '$region', province = '$province', city = '$city', barangay = '$barangay' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => "Member updated successfully"));
    } else {
        echo json_encode(array("error" => $conn->error));
    }
}
?>
