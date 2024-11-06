<?php
include 'layouts/session.php';
include 'layouts/head-main.php';
include 'layouts/db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $first_name = $_POST['firstname'] ?? '';
    $middle_name = $_POST['middlename'] ?? '';
    $last_name = $_POST['lastname'] ?? '';
    $phone_number = $_POST['mobile'] ?? '';
    $gender = $_POST['Gender'] ?? '';
    $date_of_birth = DateTime::createFromFormat('m/d/Y', $_POST['dateOfBirth'])->format('Y-m-d');
    $age = $_POST['member_age'] ?? '';
    $location = $_POST['location_text'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = '1234'; // Default password
    $membership = $_POST['Membership'] ?? 'No Membership'; // Default value
    $status = $_POST['Status'] ?? 'Active'; // Default value

    // Insert into database
    $sql = "INSERT INTO tbl_members (first_name, middle_name, last_name, phone_number, date_of_birth, age, gender, location, email, membership, status, password, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $first_name, $middle_name, $last_name, $phone_number, $date_of_birth, $age, $gender, $location, $email, $membership, $status, $password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Member added successfully";
    } else {
        $_SESSION['message'] = "Failed to add member";
    }
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: add-member.php");
    exit();
}
?>

<style>
    /* Additional Styles for Success Modal */
    #messageModal .modal-content {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    #messageModal .modal-header {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
    }
    #messageModal .modal-header .btn-close {
        background: black;
        opacity: 0.8;
    }
    #messageModal .modal-body {
        padding: 20px;
        font-size: 1rem;
        color: #333;
    }
    #messageModal .modal-footer {
        border-top: none;
        padding: 15px;
    }
    #messageModal .btn-primary {
        background-color: #4CAF50;
        border: none;
        padding: 8px 20px;
        font-size: 1rem;
    }
</style>

<head>
    <title>Members - HRMS admin template</title>
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
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Members</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Member</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                         <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_member"><i class="fa fa-plus"></i>Add Member</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- //* search bar -->
            <div class="row filter-row">
                <div class="col-md-6 col-md-3">  
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Search</label>
                    </div>
                </div>    
            </div>
            <!-- //* search bar -->
            
            <!-- //* data table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
    <table class="table table-striped custom-table table-dataTable">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Membership Staus</th>
                <th>Expiration Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM tbl_members";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    // Full Name
                    echo "<td><h2 class='table-avatar'><a href='member-profile.php' class='avatar'><img src='assets/img/profiles/avatar-19.jpg' alt=''></a>
                        <a href='member-profile.php'>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</a></h2></td>";
                    // Email
                    echo "<td>{$row['email']}</td>";
                    // Membership
                        echo "<td>";
                        if (!empty($row['membership']) && $row['membership'] != 'No Membership') {
                            echo $row['membership'];
                        } else {
                            echo "<a href='#' class='btn btn-danger btn-sm' style='color: white;'>No Membership</a>";
                        }
                        echo "</td>";

                    // Membership Status
                    echo "<td>" . (!empty($row['membership_status']) ? date('M d, Y', strtotime($row['membership_status'])) : 'Free User') . "</td>";
                    // Status with Action Dropdown
                    echo "<td><div class='dropdown action-label'><a href='#' class='btn btn-white btn-sm btn-rounded dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='fa fa-dot-circle-o " . ($row['status'] == 'Active' ? 'text-success' : 'text-danger') . "'></i> {$row['status']} </a>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' href='#'><i class='fa fa-dot-circle-o text-success'></i> Active</a>
                            <a class='dropdown-item' href='#'><i class='fa fa-dot-circle-o text-danger'></i> Inactive</a>
                        </div></div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No members found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

                </div>
            </div>
            <!-- //* data table-->
            
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

                    <!-- //* add member modal -->
                    <div id="add_member" class="modal custom-modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title">Add Member</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                    
                                    <!-- //* Add Member Form -->
                                   <form id="addMemberForm" class="needs-validation member-info" novalidate method="POST" action="">
                                    <div class="row">
                                        <!-- //* Firstname -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input id="firstname" class="form-control" type="text" name="firstname" placeholder="Enter First Name" required>
                                                <div class="invalid-feedback">Please enter a valid first name without numbers or symbols.</div>
                                            </div>
                                        </div>

                                        <!-- //* Middlename -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Middle Name <span style="color: gray">(Optional)</span> </label>
                                                <input id="middlename" class="form-control" type="text" name="middlename" placeholder="Enter Middle Name">
                                                <div class="invalid-feedback">Please enter a valid middle name without numbers or symbols.</div>
                                            </div>
                                        </div>

                                        <!-- //* Lastname -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-danger">*</span></label>
                                                <input id="lastname" class="form-control" type="text" name="lastname" placeholder="Enter Last Name" required>
                                                <div class="invalid-feedback">Please enter a valid last name without numbers or symbols.</div>
                                            </div>
                                        </div>
                                        <!-- //* phone number -->
                                        <div class="col-sm-6">
                                                    <label>Mobile Number <span style="color:red;">*</span></label>
                                                    <div class="form-group">
                                                        <div class="input-group has-validation">
                                                            <span class="input-group-text" id="inputGroupPrepend">+63</span>
                                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="ex. 9123456789" required minlength="10" maxlength="10" pattern="9[0-9]{9}">
                                                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <!-- //* Gender -->
                                            <div class="col-sm-6">
                                                <div class="form-group mb-2">
                                                    <label>Gender <span style="color:red;">*</span></label>
                                                    <div class="position-relative">
                                                        <select class="form-select py-2" name="Gender" required>
                                                        <option value="" disabled selected>Select Gender</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                        <option>Others</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date of Birth Field -->
                                            <!-- //* date of birth -->
                                            <div class="col-sm-6">
                                                <div class="form-group mb-2">
                                                    <label>Date of Birth <span class="text-danger">*</span></label>
                                                    <div class="cal-icon">
                                                        <input type="text" id="dateOfBirth" class="form-control datetimepicker" name="dateOfBirth" placeholder="Select Date of Birth" required>
                                                        <small id="dateWarning" class="text-danger" style="display: none;">Please select a valid date of birth.</small>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- //* age -->
                                            <div class="col-sm-6">
                                                <div class="form-group mb-2">
                                                    <label>Age</label>
                                                    <input type="text" id="age" name="member_age" class="form-control" placeholder="Age" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label>Email Address <span style="color:red;">*</span></label>
                                                <div class="form-group">
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label>Location <span style="color:red;">*</span></label>
                                                <select name="location" class="form-control form-control-md" id="location-selector" required>
                                                    <option selected="true" disabled>Choose Region</option>
                                                </select>
                                                <input type="hidden" id="location-text" name="location_text">
                                                <div class="invalid-feedback">Please select a valid location.</div>
                                            </div>

                                            <!-- Password Field (Read-Only) -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input id="password" class="form-control" type="text" name="password" value="1234" readonly>

                                                </div>
                                            </div>


                                    </div>

                                    <div class="submit-section" style="margin-top: 10px;">
                                        <button class="btn btn-primary submit-btn" type="submit">Add Member</button>
                                    </div>
                                   </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //* add member modal -->

                <!-- Success Message Modal -->
                <div id="messageModal" class="modal fade" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                            <div class="modal-header" style="background-color: #4CAF50; color: white; padding: 15px;">
                                <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: white; opacity: 0.8;"></button>
                            </div>
                            <div class="modal-body" style="padding: 20px; font-size: 1rem; color: #333;">
                                <p id="modalMessage" style="margin: 0;"></p>
                            </div>
                            <div class="modal-footer" style="border-top: none; padding: 15px;">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background-color: #4CAF50; border: none;">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<script src="ph-address-selector.js"></script>

<?php if (isset($_SESSION['message'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const message = "<?php echo $_SESSION['message']; ?>";
            document.getElementById("modalMessage").textContent = message;
            const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            messageModal.show();
        });
    </script>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!--//* Scripts for Resetting Fields, Age Calculation, and Phone Number Validation -->
<script>
    // Reset form fields when modal is closed
    document.getElementById('add_member').addEventListener('hidden.bs.modal', function () {
        document.getElementById('addMemberForm').reset();
        document.getElementById('age').value = '';
    });

    // Phone Number validation for Philippines
    const mobileInput = document.getElementById("mobile");
    mobileInput.addEventListener("input", () => {
        const philippineNumberPattern = /^9\d{9}$/;
        if (!philippineNumberPattern.test(mobileInput.value)) {
            mobileInput.classList.add("is-invalid");
        } else {
            mobileInput.classList.remove("is-invalid");
        }
    });
</script>

<!-- //* Address Dropdown for Add Member -->
<script>
    $(document).ready(function () {
        let level = "region";
        let locationText = "";
        let provinces = [], cities = [], barangays = [];

        function updateDropdown(data, placeholder, addBackOption = false) {
            const dropdown = $("#location-selector");
            dropdown.empty();

            // Add "Back" option if needed
            if (addBackOption) {
                dropdown.append(`<option value="back">⟵ Back</option>`);
            }

            dropdown.append(`<option selected="true" disabled>${placeholder}</option>`);
            $.each(data, function (index, item) {
                dropdown.append($("<option></option>").attr("value", item.code).text(item.name));
            });
            console.log(`Dropdown updated with ${data.length} items for ${placeholder}`);
        }

        function loadRegions() {
            // Load regions initially
            $.getJSON("ph-json/region.json", function (data) {
                const regions = data.map(region => ({
                    code: region.region_code,
                    name: region.region_name
                }));
                updateDropdown(regions, "Choose Region"); // No "Back" option at the region level
                level = "region"; // Set level to region
                console.log("Regions loaded:", regions);
            }).fail(function () {
                console.error("Failed to load regions from ph-json/region.json");
            });
        }

        // Initial load of regions on page load
        loadRegions();

        $("#location-selector").on("change", function () {
            const selectedCode = $(this).val();

            // If "Back" is selected, go to the previous level
            if (selectedCode === "back") {
                if (level === "province") {
                    loadRegions(); // Back to region level
                } else if (level === "city") {
                    updateDropdown(provinces, "Choose Province", true); // Back to province level
                    level = "province";
                } else if (level === "barangay") {
                    updateDropdown(cities, "Choose City/Municipality", true); // Back to city level
                    level = "city";
                }
                // Remove the last part of the locationText
                const textParts = locationText.split(" - ");
                textParts.pop();
                locationText = textParts.join(" - ");
                $("#location-text").val(locationText); // Update hidden input
                return; // Stop further execution
            }

            const selectedText = $(this).find("option:selected").text();
            console.log(`Selected ${level}: ${selectedText} (code: ${selectedCode})`);

            if (level === "region") {
                locationText = selectedText;

                // Load provinces for the selected region
                $.getJSON("ph-json/province.json", function (data) {
                    provinces = data
                        .filter(province => province.region_code === selectedCode)
                        .map(province => ({
                            code: province.province_code,
                            name: province.province_name
                        }));
                    updateDropdown(provinces, "Choose Province", true); // Add "Back" option
                    level = "province";
                    console.log("Provinces loaded:", provinces);
                }).fail(function () {
                    console.error("Failed to load provinces from ph-json/province.json");
                });

            } else if (level === "province") {
                locationText += ` - ${selectedText}`;

                // Load cities for the selected province
                $.getJSON("ph-json/city.json", function (data) {
                    cities = data
                        .filter(city => city.province_code === selectedCode)
                        .map(city => ({
                            code: city.city_code,
                            name: city.city_name
                        }));
                    updateDropdown(cities, "Choose City/Municipality", true); // Add "Back" option
                    level = "city";
                    console.log("Cities loaded:", cities);
                }).fail(function () {
                    console.error("Failed to load cities from ph-json/city.json");
                });

            } else if (level === "city") {
                locationText += ` - ${selectedText}`;

                // Load barangays for the selected city
                $.getJSON("ph-json/barangay.json", function (data) {
                    barangays = data
                        .filter(barangay => barangay.city_code === selectedCode)
                        .map(barangay => ({
                            code: barangay.brgy_code,
                            name: barangay.brgy_name
                        }));
                    updateDropdown(barangays, "Choose Barangay", true); // Add "Back" option
                    level = "barangay";
                    console.log("Barangays loaded:", barangays);
                }).fail(function () {
                    console.error("Failed to load barangays from ph-json/barangay.json");
                });

            } else if (level === "barangay") {
                locationText += ` - ${selectedText}`;
                $("#location-text").val(locationText); // Save the full address path

                // Display the final selection with the "Back" option in case users want to go back
                updateDropdown([], locationText, true); // Show final address as placeholder with "Back" option
                console.log("Full location text saved:", locationText);

                // Keep the level as barangay so users can go back from the final selection
                level = "barangay";
            }
        });
    });
</script>

<!-- //* Calculate age based on date of birth -->
<script>
    $(document).ready(function () {
        // Initialize datepicker with minDate and maxDate
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            maxDate: new Date(), // Restrict future dates
            minDate: '1924-01-01' // Restrict dates before 1924
        });

        // Simplified function to calculate age in years only
        function calculateAge(birthdate) {
            const birthDate = new Date(birthdate);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();
            
            // Adjust if birthdate hasn't occurred this year yet
            if (today.getMonth() < birthDate.getMonth() || 
                (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            return `${age} year${age > 1 ? 's' : ''} old`;
        }

        // Handle date change and validate date range
        $('.datetimepicker').on('dp.change', function (e) {
            if (e.date) {
                const selectedDate = e.date.toDate(); // Convert to JavaScript Date object
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

                const minDate = new Date('1924-01-01');

                // Check if the selected date is within the current year
                if (selectedDate.getFullYear() === today.getFullYear()) {
                    $('#dateWarning').text("Please select a valid date of birth.").show();
                    $(this).data("DateTimePicker").clear(); // Clear the selected date
                    $('#age').val(''); // Clear the age field
                    return;
                } else if (selectedDate > today || selectedDate < minDate) {
                    $('#dateWarning').text("Please select a valid date of birth.").show();
                    $(this).data("DateTimePicker").clear(); // Clear the selected date
                    $('#age').val(''); // Clear the age field
                    return;
                } else {
                    $('#dateWarning').hide(); // Hide the warning message
                }

                const age = calculateAge(e.date.format('YYYY-MM-DD'));
                $('#age').val(age);
            } else {
                // Clear the age field if no date is selected
                $('#age').val('');
            }
        });
    });
</script>

<!-- //* fnam mname lname -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to allow only letters and spaces on keypress
        function restrictInput(event) {
            const charCode = event.which || event.keyCode;
            if ((charCode >= 65 && charCode <= 90) || // A-Z
                (charCode >= 97 && charCode <= 122) || // a-z
                charCode === 32 || // space
                charCode === 8) { // backspace
                return true;
            } else {
                event.preventDefault(); // Block other characters
                return false;
            }
        }

        // Function to validate and display invalid feedback
        function validateNameField(input) {
            const namePattern = /^[A-Za-z\s]+$/;
            if (!namePattern.test(input.value)) {
                input.classList.add("is-invalid");
            } else {
                input.classList.remove("is-invalid");
            }
        }

        // Function to remove invalid feedback on blur
        function removeInvalidFeedback(input) {
            input.classList.remove("is-invalid");
        }

        // Apply restrictInput, validate, and blur functionality to each field
        const fields = ["firstname", "middlename", "lastname"];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.addEventListener("keypress", restrictInput);  // Restrict input
            field.addEventListener("input", function() { validateNameField(field); });  // Validate on input
            field.addEventListener("blur", function() { removeInvalidFeedback(field); });  // Remove warning on blur
        });
    });
</script>

<style>
/* Warning color for empty fields */
.warning {
    border: 2px solid #ffcc00; /* Warning color */
}
</style>


<!-- //* prevent adding with empty fields-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addMemberForm = document.getElementById('addMemberForm');

        addMemberForm.addEventListener('submit', function (event) {
            // Check if the form is valid
            if (!addMemberForm.checkValidity()) {
                event.preventDefault(); // Prevent form submission
                event.stopPropagation();

                // Add the warning class to each empty required field
                Array.from(addMemberForm.elements).forEach(element => {
                    if (element.hasAttribute('required') && !element.value.trim()) {
                        element.classList.add('warning');
                    } else {
                        element.classList.remove('warning');
                    }
                });
            } else {
                // If form is valid, remove warning class from all fields
                Array.from(addMemberForm.elements).forEach(element => element.classList.remove('warning'));
            }
        });
    });
</script>

<!-- //* add warning if try to add without filling the fields -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const addMemberForm = document.getElementById('addMemberForm');
    const locationSelector = document.getElementById('location-selector');
    const genderSelector = document.getElementById('gender-selector');

    addMemberForm.addEventListener('submit', function (event) {
        let valid = true;

        // Check if location is selected
        if (locationSelector.value === "Choose Region" || locationSelector.value === "") {
            valid = false;
            locationSelector.classList.add('warning');
        } else {
            locationSelector.classList.remove('warning');
        }

        // Check if gender is selected
        if (genderSelector.value === "" || genderSelector.value === "Select Gender") {
            valid = false;
            genderSelector.classList.add('warning');
        } else {
            genderSelector.classList.remove('warning');
        }

        // If any field is invalid, prevent form submission
        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    });
</script>

<!-- //* remove warning when typing the fields. -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const addMemberForm = document.getElementById('addMemberForm');
    const locationSelector = document.getElementById('location-selector');
    const genderSelector = document.getElementById('gender-selector');

    // Function to validate required fields on submit
    function validateForm(event) {
        let valid = true;

        // Check if location is selected
        if (locationSelector.value === "Choose Region" || locationSelector.value === "") {
            valid = false;
            locationSelector.classList.add('warning');
        } else {
            locationSelector.classList.remove('warning');
        }

        // Check if gender is selected
        if (genderSelector.value === "" || genderSelector.value === "Select Gender") {
            valid = false;
            genderSelector.classList.add('warning');
        } else {
            genderSelector.classList.remove('warning');
        }

        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }
    }

    // Real-time validation to remove warning class on input
    Array.from(addMemberForm.elements).forEach(element => {
        // For text inputs, use the 'input' event
        if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            element.addEventListener('input', function () {
                if (element.value.trim() !== "") {
                    element.classList.remove('warning');
                }
            });
        }
        // For select elements, use the 'change' event
        else if (element.tagName === 'SELECT') {
            element.addEventListener('change', function () {
                if (element.value !== "" && element.value !== "Select Gender" && element.value !== "Choose Region") {
                    element.classList.remove('warning');
                }
            });
        }
    });

    // Attach form submit event
    addMemberForm.addEventListener('submit', validateForm);
 });
</script>
</body>
</html>
