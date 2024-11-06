<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>
    <title>Gym Membership Enrollment - Admin Panel</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
</head>

<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal = $_POST['postal'];
    $current_weight = $_POST['currentWeight'];
    $height = $_POST['height'];
    $bmi = $_POST['bmi'];
    $goal_weight = $_POST['goalWeight'];
    $emergency_first_name = $_POST['emergencyFirstName'];
    $emergency_last_name = $_POST['emergencyLastName'];
    $relationship = $_POST['relationship'];
    $emergency_phone = $_POST['emergencyPhone'];
    $medical_conditions = $_POST['medical'];
    $membership_type = $_POST['membership_type'];
    $start_date = $_POST['startDate'];

    // Insert the data into the enrollment table
    $sql = "INSERT INTO enrollment (
                first_name, last_name, email, phone, dob, gender, address, city, state, postal, current_weight, 
                height, bmi, goal_weight, emergency_first_name, emergency_last_name, relationship, emergency_phone, 
                medical_conditions, membership_type, start_date
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param(
            "sssssssssssssssssssss", 
            $first_name, $last_name, $email, $phone, $dob, $gender, $address, $city, $state, $postal, 
            $current_weight, $height, $bmi, $goal_weight, $emergency_first_name, $emergency_last_name, 
            $relationship, $emergency_phone, $medical_conditions, $membership_type, $start_date
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "Enrollment successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>



<body>
    <div class="main-wrapper">
    <?php include 'layouts/menu.php'; ?>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Enrollment</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Enrollment</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Enrollment Form -->
           <div class="container">
    <form id="enrollmentForm" method="POST" action="enrollment.php">
        <!-- Personal Information Section -->
        <div class="form-section-custom">
            <h4>Personal Information</h4>
            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="(000) 000-0000" required>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>

            <div class="form-group-custom">
                <label>Gender</label>
                <div>
                    <label class="custom-radio-inline"><input type="radio" name="gender" value="male" required> Male</label>
                    <label class="custom-radio-inline"><input type="radio" name="gender" value="female" required> Female</label>
                    <label class="custom-radio-inline"><input type="radio" name="gender" value="other" required> Other</label>
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="form-section-custom">
            <h4>Address</h4>
            <div class="form-group-custom">
                <label for="address">Street Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Street Address" required>
                <input type="text" class="form-control mt-2" placeholder="Street Address Line 2">
            </div>

            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                    </div>
                    <div class="col-md-6">
                        <label for="state">State / Province</label>
                        <select class="form-control" id="state" name="state" required>
                            <option value="" disabled selected>Please Select</option>
                            <option value="state1">State / Province 1</option>
                            <option value="state2">State / Province 2</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label for="postal">Postal / Zip Code</label>
                <input type="text" class="form-control" id="postal" name="postal" placeholder="Postal / Zip Code" required>
            </div>
        </div>

        <!-- Health Section -->
        <div class="form-section-custom">
            <h4>Health Information</h4>
            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="currentWeight">Current Weight</label>
                        <input type="text" class="form-control" id="currentWeight" name="currentWeight" placeholder="Weight" required>
                    </div>
                    <div class="col-md-6">
                        <label for="height">Height</label>
                        <input type="text" class="form-control" id="height" name="height" placeholder="Height" required>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="bmi">BMI</label>
                        <input type="text" class="form-control" id="bmi" name="bmi" placeholder="BMI" required>
                    </div>
                    <div class="col-md-6">
                        <label for="goalWeight">Goal Weight</label>
                        <input type="text" class="form-control" id="goalWeight" name="goalWeight" placeholder="Goal Weight" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact Information Section -->
        <div class="form-section-custom">
            <h4>Emergency Contact Information</h4>
            <div class="form-group-custom">
                <div class="row">
                    <div class="col-md-6">
                        <label for="emergencyFirstName">First Name</label>
                        <input type="text" class="form-control" id="emergencyFirstName" name="emergencyFirstName" placeholder="First Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="emergencyLastName">Last Name</label>
                        <input type="text" class="form-control" id="emergencyLastName" name="emergencyLastName" placeholder="Last Name" required>
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label for="relationship">Relationship</label>
                <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Relationship" required>
            </div>

            <div class="form-group-custom">
                <label for="emergencyPhone">Emergency Contact Phone Number</label>
                <input type="text" class="form-control" id="emergencyPhone" name="emergencyPhone" placeholder="(000) 000-0000" required>
            </div>
        </div>

        <!-- Medical Information Section -->
        <div class="form-section-custom">
            <h4>Medical Information</h4>
            <div class="form-group-custom">
                <p>Do you have any medical conditions or allergies?</p>
                <label class="custom-radio-inline"><input type="radio" name="medical" value="yes" required> Yes</label>
                <label class="custom-radio-inline"><input type="radio" name="medical" value="no" required> No</label>
                <textarea class="form-control mt-2" name="medicalConditions" placeholder="If yes, please provide details" rows="3"></textarea>
            </div>
        </div>

        <!-- Membership Information Section -->
        <div class="form-section-custom">
            <h4>Membership Information</h4>
            <div class="form-group-custom">
                <label>Choose Membership Type</label>
                <div>
                    <label class="custom-radio-inline"><input type="radio" name="membership_type" value="weekly" required> Weekly Membership</label>
                    <label class="custom-radio-inline"><input type="radio" name="membership_type" value="monthly" required> Monthly Membership</label>
                    <label class="custom-radio-inline"><input type="radio" name="membership_type" value="annual" required> Annual Membership</label>
                </div>
            </div>

            <div class="form-group-custom">
                <label for="startDate">Preferred Start Date</label>
                <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>

            <div class="form-group-custom">
                <input type="checkbox" required> I agree to <a href="#">terms & conditions</a>
            </div>
        </div>

        <!-- Submit Button to trigger the modal -->
        <div class="form-group-custom text-center">
            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </div>
    </form>
</div>

            <!-- /Enrollment Form -->

            
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>




</body>

</html>
