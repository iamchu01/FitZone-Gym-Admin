<?php require_once('vincludes/load.php'); ?>
<?php

include 'layouts/session.php';
include 'layouts/head-main.php';
include 'layouts/db-connection.php';

$editing = false; // Default to adding a member

// Check if editing
if (isset($_GET['member_id'])) {
    $editing = true;
    $member_id = intval($_GET['member_id']); // Secure member_id input

    $query = "SELECT * FROM tbl_add_members WHERE member_id = $member_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $member = mysqli_fetch_assoc($result);
    
        // Split the address into parts
        $address_parts = explode(', ', $member['address']);
        $region = $address_parts[0] ?? '';
        $province = $address_parts[1] ?? '';
        $city = $address_parts[2] ?? '';
        $barangay = $address_parts[3] ?? '';
    } else {
        echo "<p class='text-center'>Member not found.</p>";
        exit;
    }
}

// Handle form submission for adding/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middlename']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['mobile']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['dateOfBirth']);
    $gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $region = mysqli_real_escape_string($conn, $_POST['region']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);

    // Combine the address parts into a single string
    $address = implode(', ', array_filter([$region, $province, $city, $barangay]));

    if ($editing) {
        // Update the member
        $update_query = "UPDATE tbl_add_members SET 
                            first_name = '$first_name',
                            middle_name = '$middle_name',
                            last_name = '$last_name',
                            email = '$email',
                            phone_number = '$phone_number',
                            date_of_birth = '$date_of_birth',
                            gender = '$gender',
                            address = '$address'
                         WHERE member_id = $member_id";

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Member details updated successfully!'); window.location.href='view-profile.php?member_id=$member_id';</script>";
        } else {
            echo "<script>alert('Failed to update member details.');</script>";
        }
    } else {
        // Add a new member
        $insert_query = "INSERT INTO tbl_add_members (first_name, middle_name, last_name, email, phone_number, date_of_birth, gender, address) 
                         VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$date_of_birth', '$gender', '$address')";

        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Member added successfully!'); window.location.href='members-list.php';</script>";
        } else {
            echo "<script>alert('Failed to add member.');</script>";
        }
    }
}
?>





<head>

    <title>Member Profile</title>

    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>

</head>

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
              <h3 class="page-title">Member Profile</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
              </ul>
            </div>
          </div>
        </div>
        <!-- /Page Header -->

        <div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap mt-4">
                        <div class="profile-img">
                            <a href="#"><img alt="" src="assets/img/profiles/avatar-02.jpg"></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5 mt-3">
                                <div class="profile-info-left mx-4">
                                    <h3 class="user-name mt-4">
                                        <?php 
                                            echo htmlspecialchars($member['first_name']) . ' ' .
                                                 (!empty($member['middle_name']) ? htmlspecialchars($member['middle_name']) : '') . ' ' .
                                                 htmlspecialchars($member['last_name']);
                                        ?>
                                    </h3>
                                    <div class="staff-id">
                                        Member ID: <?php echo htmlspecialchars($member['member_id']); ?>
                                    </div>
                                    <div class="small doj text-muted">
                                        Date of Join: <?php echo (!empty($member['member_join_date']) ? date("d M Y", strtotime($member['member_join_date'])) : ''); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li class="d-flex align-items-center">
                                        <span class="title">Phone:</span>
                                        <span class="text ms-2">
                                            <?php echo (!empty($member['phone_number']) ? htmlspecialchars($member['phone_number']) : ''); ?>
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="title">Email:</span>
                                        <span class="text ms-2">
                                            <?php echo htmlspecialchars($member['email']); ?>
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="title">Birthday:</span>
                                        <span class="text ms-2">
                                            <?php 
                                            echo (!empty($member['date_of_birth']) ? date("d M Y", strtotime($member['date_of_birth'])) : ''); 
                                            ?>
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="title">Age:</span>
                                        <span class="text ms-2">
                                            <?php 
                                            echo (!empty($member['age']) ? htmlspecialchars($member['age'] . ' ') : ''); 
                                            ?>
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center">
    <span class="title">Gender:</span>
    <span class="text ms-2">
        <?php echo !empty($member['gender']) ? htmlspecialchars($member['gender']) : ''; ?>
    </span>
</li>

<li class="d-flex align-items-center">
    <span class="title">Address:</span>
    <span class="text ms-2">
        <?php echo !empty($member['address']) ? htmlspecialchars($member['address']) : ''; ?>
    </span>
</li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit">
                        <a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon" href="#">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



            
            <div class="tab-content">
            
                <!-- //*Profile Info Tab -->
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                                  <div class="card profile-box flex-fill">
                                  <div class="card-body">
                                    <h3 class="card-title">Joined Programs</h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Bank name</div>
                                            <div class="text">ICICI Bank</div>
                                        </li>
                                        <li>
                                            <div class="title">Bank account No.</div>
                                            <div class="text">159843014641</div>
                                        </li>
                                        <li>
                                            <div class="title">IFSC Code</div>
                                            <div class="text">ICI24504</div>
                                        </li>
                                        <li>
                                            <div class="title">PAN No</div>
                                            <div class="text">TC000Y56</div>
                                        </li>
                                    </ul>
                                </div>
                                  </div>
                            </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                            <div class="card-body">
                                    <h3 class="card-title">Membership Information</h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Bank name</div>
                                            <div class="text">N/A</div>
                                        </li>
                                        <li>
                                            <div class="title">Bank account No.</div>
                                            <div class="text">N/A</div>
                                        </li>
                                        <li>
                                            <div class="title">IFSC Code</div>
                                            <div class="text">N/A</div>
                                        </li>
                                        <li>
                                            <div class="title">PAN No</div>
                                            <div class="text">N/A</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Membership Information</h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Bank name</div>
                                            <div class="text">ICICI Bank</div>
                                        </li>
                                        <li>
                                            <div class="title">Bank account No.</div>
                                            <div class="text">159843014641</div>
                                        </li>
                                        <li>
                                            <div class="title">IFSC Code</div>
                                            <div class="text">ICI24504</div>
                                        </li>
                                        <li>
                                            <div class="title">PAN No</div>
                                            <div class="text">TC000Y56</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                               <div class="card-body">
                                    <h3 class="card-title">Joined Programs</h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Bank name</div>
                                            <div class="text">ICICI Bank</div>
                                        </li>
                                        <li>
                                            <div class="title">Bank account No.</div>
                                            <div class="text">159843014641</div>
                                        </li>
                                        <li>
                                            <div class="title">IFSC Code</div>
                                            <div class="text">ICI24504</div>
                                        </li>
                                        <li>
                                            <div class="title">PAN No</div>
                                            <div class="text">TC000Y56</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                        </div> -->
                    </div>
                
                </div>
                <!-- /Profile Info Tab -->
                
                
            </div>
        </div>
        <!-- /Page Content -->
        
        <!-- //* Edit Profile Modal -->
        <div id="profile_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Member Information</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Update Member Form -->
                <form id="updateMemberForm">
                    <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['member_id']); ?>">

                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                    value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
                            </div>
                        </div>

                        <!-- Middle Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" class="form-control"
                                    value="<?php echo !empty($member['middle_name']) ? htmlspecialchars($member['middle_name']) : ''; ?>">
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                    value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($member['email']); ?>" required>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number" class="form-control"
                                    value="<?php echo !empty($member['phone_number']) ? htmlspecialchars($member['phone_number']) : ''; ?>">
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birthday</label>
                                <input type="text" name="date_of_birth" class="form-control datetimepicker"
                                    value="<?php echo !empty($member['date_of_birth']) ? htmlspecialchars($member['date_of_birth']) : ''; ?>">
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="" <?php echo empty($member['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                                    <option value="Male" <?php echo ($member['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($member['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3"><?php echo !empty($member['address']) ? htmlspecialchars($member['address']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="submit-section">
                        <button type="button" id="updateMemberBtn" class="btn btn-primary submit-btn">Update Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        <!-- //* Edit Profile Modal -->
        
        <!-- Personal Info Modal -->
        <div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Personal Information</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Passport No</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Passport Expiry Date</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nationality <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Religion</label>
                                        <div class="cal-icon">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Marital status <span class="text-danger">*</span></label>
                                        <select class="select form-control">
                                            <option>-</option>
                                            <option>Single</option>
                                            <option>Married</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employment of spouse</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. of children </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Personal Info Modal -->
        
        
        
    </div>
    <!-- /Page Wrapper -->



</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>


<script>

document.addEventListener("DOMContentLoaded", function () {
    // Initialize Datepicker
    $('.datetimepicker').datepicker({
        format: 'yyyy-mm-dd', // Date format (adjust as needed)
        autoclose: true, // Close after selecting
        endDate: new Date(), // Prevent selecting future dates
        todayHighlight: true // Highlight today's date
    });

    // Calculate Age Dynamically
    $('#dateOfBirth').on('change', function () {
        const selectedDate = $(this).val(); // Get selected date
        const dateOfBirth = new Date(selectedDate); // Convert to Date object
        const today = new Date();

        if (dateOfBirth > today) {
            $('#age').val('');
            $('#dateWarning').show();
            return;
        }

        $('#dateWarning').hide();

        let age = today.getFullYear() - dateOfBirth.getFullYear();
        const monthDifference = today.getMonth() - dateOfBirth.getMonth();

        // Adjust age if birth date hasn't occurred yet this year
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dateOfBirth.getDate())) {
            age--;
        }

        // Display calculated age
        if (age >= 0) {
            $('#age').val(age + ' years old');
        } else {
            $('#age').val('');
        }
    });
});

</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Get the current address values from the hidden inputs
    const currentRegion = document.getElementById("currentRegion").value;
    const currentProvince = document.getElementById("currentProvince").value;
    const currentCity = document.getElementById("currentCity").value;
    const currentBarangay = document.getElementById("currentBarangay").value;

    // Select elements for address dropdowns
    const regionDropdown = document.getElementById("region");
    const provinceDropdown = document.getElementById("province");
    const cityDropdown = document.getElementById("city");
    const barangayDropdown = document.getElementById("barangay");

    // Set pre-selected values if the dropdowns are populated
    if (regionDropdown) regionDropdown.value = currentRegion;
    if (provinceDropdown) provinceDropdown.value = currentProvince;
    if (cityDropdown) cityDropdown.value = currentCity;
    if (barangayDropdown) barangayDropdown.value = currentBarangay;
});
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const currentRegion = document.getElementById("currentRegion").value;
    const currentProvince = document.getElementById("currentProvince").value;
    const currentCity = document.getElementById("currentCity").value;
    const currentBarangay = document.getElementById("currentBarangay").value;

    const regionDropdown = document.getElementById("region");
    const provinceDropdown = document.getElementById("province");
    const cityDropdown = document.getElementById("city");
    const barangayDropdown = document.getElementById("barangay");

    if (regionDropdown) {
        regionDropdown.value = currentRegion;
        // Trigger province population
        populateProvinces(currentRegion, function () {
            provinceDropdown.value = currentProvince;
            // Trigger city population
            populateCities(currentProvince, function () {
                cityDropdown.value = currentCity;
                // Trigger barangay population
                populateBarangays(currentCity, function () {
                    barangayDropdown.value = currentBarangay;
                });
            });
        });
    }
});

// Example functions for populating dependent dropdowns
function populateProvinces(region, callback) {
    // Simulate dynamic loading
    setTimeout(() => {
        console.log("Provinces populated for region: " + region);
        if (callback) callback();
    }, 500);
}

function populateCities(province, callback) {
    setTimeout(() => {
        console.log("Cities populated for province: " + province);
        if (callback) callback();
    }, 500);
}

function populateBarangays(city, callback) {
    setTimeout(() => {
        console.log("Barangays populated for city: " + city);
        if (callback) callback();
    }, 500);
}

</script>


  

<script>
document.getElementById("updateMemberBtn").addEventListener("click", function () {
    const form = document.getElementById("updateMemberForm");
    const formData = new FormData(form);

    fetch("backend-add-authenticate/update-member.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Response:", data); // Debug the response in the console
            if (data.success) {
                // Update the DOM
                alert(data.message || "Profile updated successfully!");
                $("#profile_info").modal("hide");
                location.reload(); // Reload the page to reflect updates
            } else {
                alert("Failed to update profile: " + (data.error || "Unknown error."));
            }
        })
        .catch((error) => {
            console.error("Fetch error:", error); // Log fetch errors
            alert("An error occurred. Please try again.");
        });
});


</script>


</body>

</html>