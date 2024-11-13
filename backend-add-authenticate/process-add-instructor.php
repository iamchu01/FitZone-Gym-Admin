<?php
include '../layouts/db-connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Capture and sanitize form data
  $first_name = isset($_POST['firstname']) ? $conn->real_escape_string(trim($_POST['firstname'])) : null;
  $last_name = isset($_POST['lastname']) ? $conn->real_escape_string(trim($_POST['lastname'])) : null;
  $phone_number = isset($_POST['mobile']) ? $conn->real_escape_string(trim($_POST['mobile'])) : null;
  $gender = isset($_POST['Gender']) ? $conn->real_escape_string(trim($_POST['Gender'])) : 'Others'; // Ensure 'Others' is captured
  $date_of_birth = isset($_POST['dateOfBirth']) ? $conn->real_escape_string(trim($_POST['dateOfBirth'])) : null;
  $age = isset($_POST['instructor_age']) ? intval($_POST['instructor_age']) : null; // Cast to integer for age

  // Capture the text values for the address from the hidden input fields
  $region_text = isset($_POST['region_text']) ? $conn->real_escape_string(trim($_POST['region_text'])) : null;
  $province_text = isset($_POST['province_text']) ? $conn->real_escape_string(trim($_POST['province_text'])) : null;
  $city_text = isset($_POST['city_text']) ? $conn->real_escape_string(trim($_POST['city_text'])) : null;
  $barangay_text = isset($_POST['barangay_text']) ? $conn->real_escape_string(trim($_POST['barangay_text'])) : null;

  // Concatenate full address for the address field
  $address = "$region_text, $province_text, $city_text, $barangay_text";

  $specialization = isset($_POST['specialization']) ? $conn->real_escape_string(trim($_POST['specialization'])) : null;
  $status = 'Active'; // Default status for new instructors

  // Validate required fields
  if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($gender) || empty($date_of_birth) || empty($address) || empty($specialization)) {
    header('Location: ../add-instructor.php?error=empty_fields');
    exit;
  }

  // Insert data into the database
  $sql = "INSERT INTO tbl_add_instructors (first_name, last_name, phone_number, gender, date_of_birth, age, address, specialization, status)
            VALUES ('$first_name', '$last_name', '$phone_number', '$gender', '$date_of_birth', '$age', '$address', '$specialization', '$status')";

  if ($conn->query($sql) === TRUE) {
    header('Location: ../add-instructor.php?success=added');
    exit;
  } else {
    // Log detailed error for debugging (optional, remove in production)
    error_log("Database error: " . $conn->error);
    header('Location: ../add-instructor.php?error=database_error');
    exit;
  }
}
?>
