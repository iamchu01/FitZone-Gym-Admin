<?php
include 'layouts/session.php';
include 'layouts/head-main.php';
include 'layouts/db-connection.php';

// Include the necessary OTP classes
require_once 'PHPMailer-OTP/classes/OTPVerification.php';

$message = ''; // Variable to display messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otpHandler = new OTPVerification($conn);

    // Check if email is verified
    if ($otpHandler->isEmailVerified($email)) {
        // Prepare data for moving to `tbl_add_members`
        $data = [
            'first_name' => $_POST['firstname'] ?? '',
            'middle_name' => $_POST['middlename'] ?? '',
            'last_name' => $_POST['lastname'] ?? '',
            'phone_number' => $_POST['mobile'] ?? '',
            'gender' => $_POST['Gender'] ?? 'Others',
            'date_of_birth' => $_POST['dateOfBirth'] ?? '',
            'age' => $_POST['member_age'] ?? '',
            'address' => implode(', ', array_filter([
                $_POST['region_text'] ?? '',
                $_POST['province_text'] ?? '',
                $_POST['city_text'] ?? '',
                $_POST['barangay_text'] ?? ''
            ])),
            'password' => password_hash($_POST['password'] ?? '12345', PASSWORD_DEFAULT),
        ];

        // Insert verified member into `tbl_add_members`
        $stmt = $conn->prepare("
            INSERT INTO tbl_add_members (first_name, middle_name, last_name, phone_number, gender, date_of_birth, age, address, email, password, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')
        ");

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssss",
                $data['first_name'],
                $data['middle_name'],
                $data['last_name'],
                $data['phone_number'],
                $data['gender'],
                $data['date_of_birth'],
                $data['age'],
                $data['address'],
                $email,
                $data['password']
            );

            if ($stmt->execute()) {
                $message = "Member added successfully!";
                header('Location: add-member.php?success=added');
                exit;
            } else {
                error_log("Database insertion failed: " . $stmt->error);
                $message = "Failed to add member to the database.";
            }
        } else {
            error_log("Statement preparation failed: " . $conn->error);
            $message = "Failed to prepare database query.";
        }
    } else {
        $message = "Email not verified. Please verify your email first.";
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'check_email') {
  error_log("check_email action called");
  $email = trim($_POST['email']);

  $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tbl_add_members WHERE email = ?");
  if (!$stmt) {
      error_log("Failed to prepare statement: " . $conn->error);
      echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed.']);
      exit;
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row['count'] > 0) {
      echo json_encode(['status' => 'exists', 'message' => 'Email already exists. Please use a different email.']);
  } else {
      echo json_encode(['status' => 'available', 'message' => 'Email is available.']);
  }
  exit;
}

?>



<head>
  <title>Members</title>
  <?php include 'layouts/title-meta.php'; ?>
  <?php include 'layouts/head-css.php'; ?>
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                <li class="breadcrumb-item active">Members</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_member"><i
                  class="fa fa-plus"></i> Add Member</a>
            </div>
          </div>
        </div>
        <!-- /Page Header -->

        <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Email already exists. Please use a different email.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="row filter-row">
          <div class="col-md-6 col-md-3">
            <div class="form-group form-focus">
              <input type="text" id="searchMemberInput" class="form-control floating">
              <label class="focus-label">Search</label>
            </div>
          </div>
        </div>
        <!-- /Search Bar -->

        <!-- //*Data Table -->
        <div id="membersTable">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table datatable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Membership</th>
                      <th>Membership Status</th>
                      <th>Status</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch members from the database
                    $query = "SELECT * FROM tbl_add_members WHERE archive_status = 'Unarchived' ORDER BY member_id DESC";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        // Concatenate first and last name for full name display
                        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        $email = htmlspecialchars($row['email']);
                        $membership = htmlspecialchars($row['membership']);
                        $membership_status = htmlspecialchars($row['membership_status']);
                        $status = htmlspecialchars($row['status']);

                        // Check for membership and set default to "No Membership" wrapped in a red button
                        if (empty($membership) || $membership === 'No Membership') {
                          $membership_display = '<button class="btn btn-danger btn-sm">No Membership</button>';
                        } else {
                          $membership_display = htmlspecialchars($membership);
                        }

                        // Check for membership status and set default to "No Membership"
                        if (empty($membership_status)) {
                          $membership_status_display = "No Membership";
                        } else {
                          $membership_status_display = $membership_status;
                        }

                        // Display status with appropriate label color
                        $status_label = $status === 'Active' ? 'text-success' : 'text-danger';
                        ?>
                        <tr>
                          <td>
                            <h2 class="table-avatar">
                              <a class="avatar"><img src="assets/img/profiles/avatar-19.jpg" alt=""></a>
                              <a><?php echo $full_name; ?></a>
                            </h2>
                          </td>
                          <td><?php echo $email; ?></td>
                          <td><?php echo $membership_display; ?></td>
                          <td><?php echo $membership_status_display; ?></td>
                          <td>
                            <div class="dropdown action-label">
                              <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i id="status-<?php echo $row['member_id']; ?>"
                                  class="fa fa-dot-circle-o <?php echo $status === 'Active' ? 'text-success' : 'text-danger'; ?>"></i>
                                <span id="status-text-<?php echo $row['member_id']; ?>"><?php echo $status; ?></span>
                              </a>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"
                                  onclick="updateStatus(<?php echo $row['member_id']; ?>, 'Active')"><i
                                    class="fa fa-dot-circle-o text-success"></i>
                                  Active</a>
                                <a class="dropdown-item" href="#"
                                  onclick="updateStatus(<?php echo $row['member_id']; ?>, 'Inactive')"><i
                                    class="fa fa-dot-circle-o text-danger"></i>
                                  Inactive</a>
                              </div>
                            </div>
                          </td>
                          <td class="text-end">
                            <div class="dropdown dropdown-action">
                              <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="member-profile.php?id=<?php echo $row['member_id']; ?>">
                                  <i class="fa fa-eye m-r-5"></i> View Profile
                                </a>
                                <a id="archive-link-<?php echo $row['member_id']; ?>"
                                  class="dropdown-item <?php echo ($status === 'Inactive') ? '' : 'disabled'; ?>" href="#"
                                  data-bs-toggle="modal" data-bs-target="#archive_member"
                                  data-id="<?php echo $row['member_id']; ?>"
                                  title="<?php echo ($status === 'Inactive') ? '' : 'Only inactive members can be archived'; ?>">
                                  <i class="fa fa-archive m-r-5"></i> Archive
                                </a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <?php
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center'>No members found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- //*Data Table -->

      </div>
      <!-- /Page Content -->

      <!-- //*Add Member Modal -->
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
              <!-- Message Section -->
              <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $otpSent ? 'success' : 'danger'; ?>">
                  <?php echo $message; ?>
                </div>
              <?php endif; ?>

              <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Email already exists. Please use a different email.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <form id="addMemberForm" class="needs-validation member-info" method="POST" action="add-member.php">


                <!-- Alert Section -->
                <div class="col-12">
                  <div id="alert-container" class="mt-3"></div>
                </div>

                <div class="row">
                  <!-- Basic Info -->

                  <!-- First Name -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input id="memberFirstname" class="form-control" type="text" name="firstname"
                        placeholder="Enter First Name" required />
                    </div>
                  </div>

                  <!-- Middle Name -->
<div class="col-sm-6">
    <div class="form-group">
        <label>Middle Name</label>
        <input id="memberMiddlename" class="form-control" type="text" name="middlename" placeholder="Enter Middle Name" />
    </div>
</div>


                  <!-- Last Name -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input id="memberLastname" class="form-control" type="text" name="lastname"
                        placeholder="Enter Last Name" required />
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Email Address <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="email" class="form-control" id="memberEmail" name="email" placeholder="Enter Email"
                          required>
                        <button type="button" id="sendOtpBtn" class="btn btn-outline-primary">Send OTP</button>
                      </div>
                    </div>
                  </div>

                  <!-- OTP -->
                  <div class="col-sm-6 ">
                    <div class="form-group">
                      <label>OTP</label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
                        <button type="button" id="verifyOtpBtn" class="btn btn-outline-success">Verify OTP</button>
                      </div>
                    </div>
                  </div>



                  <!-- Password -->
                  <!-- <div class="col-sm-6">
                    <div class="form-group">
                      <label>Password</label>
                      <div class="input-group">
                        <input id="memberPassword" class="form-control" type="password" name="password" value="12345"
                          required>
                        <button class="btn btn-outline-secondary" type="button" id="toggleMemberPassword">
                          <i class="fa fa-eye-slash" id="passwordIcon"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div> -->

                <!-- Hidden Additional Info Section -->
                <div id="additionalInfoSection"
                  style="overflow: hidden; max-height: 0; transition: max-height 0.5s ease;">
                  <h5 style="text-align:center; font-size: 20px; margin-top: 20px;">Additional Information</h5>
                  <div class="row">
                    <!-- //* add field here -->
                    <!-- Phone Number -->
                    <div class="col-sm-6">
                      <label>Mobile Number</label>
                      <div class="form-group">
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">+63</span>
                          <input type="text" class="form-control" id="memberMobile" name="mobile"
                            placeholder="ex. 9123456789" minlength="10" maxlength="10" pattern="9[0-9]{9}">
                          <div class="invalid-feedback">Please enter a valid mobile
                            number.</div>
                        </div>
                      </div>
                    </div>
                    <!-- //* Gender -->
                    <div class="col-sm-6">
                      <div class="form-group mb-2">
                        <label>Gender</label>
                        <div class="position-relative">
                          <select class="form-select py-2" name="Gender" id="gender-selector">
                            <option value="" disabled selected>Select Gender
                            </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                            <!-- Ensure this option has the value "Others" -->
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- Date of Birth -->
                    <div class="col-sm-6">
                      <div class="form-group mb-2">
                        <label>Date of Birth </label>
                        <div class="cal-icon">
                          <input type="text" id="memberDateOfBirth" class="form-control datetimepicker"
                            name="dateOfBirth" placeholder="Select Date of Birth">
                          <small id="memberDateWarning" class="text-danger" style="display: none;">Please
                            select a valid date of
                            birth.</small>
                        </div>
                      </div>
                    </div>
                    <!-- Age -->
                    <div class="col-sm-6">
                      <div class="form-group mb-2">
                        <label>Age</label>
                        <input type="text" id="memberAge" name="member_age" class="form-control" placeholder="Age"
                          readonly>
                      </div>
                    </div>
                    <!-- Address Form -->
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="region">Region</label>
                        <select id="region" class="form-select" name="region">
                          <option value="" disabled selected>Select Region</option>
                        </select>
                        <input type="hidden" name="region_text" id="region-text">
                        <div class="invalid-feedback">Please select a region.</div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="province">Province</label>
                        <select id="province" class="form-select" name="province">
                          <option value="" disabled selected>Select Province</option>
                          <!-- Populate provinces dynamically -->
                        </select>
                        <input type="hidden" name="province_text" id="province-text">
                        <div class="invalid-feedback">Please select a province.</div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="city">City/Municipality </label>
                        <select id="city" class="form-select" name="city">
                          <option value="" disabled selected>Select City/Municipality
                          </option>
                          <!-- Populate cities dynamically -->
                        </select>
                        <input type="hidden" name="city_text" id="city-text">
                        <div class="invalid-feedback">Please select a city or
                          municipality.</div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" class="form-select" name="barangay">
                          <option value="" disabled selected>Select Barangay</option>
                          <!-- Populate barangays dynamically -->
                        </select>
                        <input type="hidden" name="barangay_text" id="barangay-text">
                        <div class="invalid-feedback">Please select a barangay.</div>
                      </div>
                    </div>
                    <!-- //* add field here -->
                  </div>
                </div>

                <!-- Toggle Button for Additional Info -->
                <div class="col-sm-12 text-center m-t-20">
                  <div class="add-info-toggle" style="float:right;">
                    <a href="javascript:void(0);" onclick="toggleAdditionalInfoSection()">
                      <i class="fa fa-plus-circle"></i> Add Additional Info
                    </a>
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
      <!-- //*Add Member Modal -->

      <!-- //* Add Member Success Modal -->
      <div class="modal " id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="successModalLabel">Success</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Member added successfully!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>
      <!-- //* Add Member  Success Modal -->


      <!-- //*Confirm Archive Member Modal -->
      <div class="modal fade" id="archive_member" tabindex="-1" role="dialog" aria-labelledby="confirmArchiveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmArchiveModalLabel">Confirm Archive</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to archive this member?</p>
              <input type="hidden" id="memberIdToArchive" value="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onclick="confirmArchiveMember()">Archive</button>
            </div>
          </div>
        </div>
      </div>


      <!-- //*Archive Success Modal -->
      <div class="modal fade" id="archiveSuccessModal" tabindex="-1" aria-labelledby="archiveSuccessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="archiveSuccessModalLabel">Success</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Member archived successfully!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>


    </div>
    <!-- end main wrapper-->

    <script src="backend-add-authenticate/add-member.js"></script>
    <!-- Toastr JS -->

    <?php include 'layouts/customizer.php'; ?>
    <!-- JAVASCRIPT -->
    <?php include 'layouts/vendor-scripts.php'; ?>

    <!-- //* handles Add Member Success Modal Trigger -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
              $('#successModal').modal('show');
              if (history.pushState) {
                  var newUrl = window.location.href.split('?')[0];
                  window.history.pushState({ path: newUrl }, '', newUrl);
              }
          <?php endif; ?>
      });
    </script>


    <!-- //* confirm archive modal pop up -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        $('#archive_member').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget);
          var memberId = button.data('id');
          $('#memberIdToArchive').val(memberId);
        });
      });

      function confirmArchiveMember() {
        var memberId = document.getElementById('memberIdToArchive').value;

        if (memberId) {
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "backend-add-authenticate/archive-member.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              var response = xhr.responseText.trim();
              if (response === "success") {
                // Close the archive confirmation modal
                $('#archive_member').modal('hide');

                // Show the success modal
                setTimeout(function () {
                  $('#archiveSuccessModal').modal('show');
                }, 500);

                // Optionally, refresh or update the table after the modal closes
                $('#archiveSuccessModal').on('hidden.bs.modal', function () {
                  location.reload();
                });
              } else {
                console.error("Error archiving member: " + response);
              }
            }
          };

          xhr.send("id=" + memberId);
        }
      }
    </script>


    <!-- //* handle update status for active and inactive -->
    <script>
      function updateStatus(memberId, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "backend-add-authenticate/member-update-status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText.trim() === "success") {
              // Update the status text and icon dynamically
              document.querySelector(`#status-text-${memberId}`).textContent = newStatus;
              document.querySelector(`#status-${memberId}`).className =
                `fa fa-dot-circle-o ${newStatus === "Active" ? "text-success" : "text-danger"}`;

              // Enable or disable the archive option based on the new status
              var archiveLink = document.querySelector(`#archive-link-${memberId}`);
              if (newStatus === "Inactive") {
                archiveLink.classList.remove('disabled');
                archiveLink.title = '';
              } else {
                archiveLink.classList.add('disabled');
                archiveLink.title = 'Only inactive members can be archived';
              }
            } else {
              console.error("Failed to update status.");
              alert("An error occurred while updating the status. Please try again.");
            }
          }
        };

        xhr.send("id=" + memberId + "&status=" + newStatus);
      }
    </script>

    <!-- //* Toggle Password -->
    <script>
      document.getElementById("toggleMemberPassword").addEventListener("click", function () {
        const passwordInput = document.getElementById("memberPassword");
        const passwordIcon = document.getElementById("passwordIcon");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          passwordIcon.classList.remove("fa-eye-slash");
          passwordIcon.classList.add("fa-eye");
        } else {
          passwordInput.type = "password";
          passwordIcon.classList.remove("fa-eye");
          passwordIcon.classList.add("fa-eye-slash");
        }
      });
    </script>

    <!-- //* Dropdown more info -->
    <script>
      function toggleAdditionalInfoSection() {
        var additionalInfoSection = document.getElementById('additionalInfoSection');

        // Toggle max-height for smooth transition
        if (additionalInfoSection.style.maxHeight === "0px" || additionalInfoSection.style.maxHeight === "") {
          additionalInfoSection.style.maxHeight = additionalInfoSection.scrollHeight + "px";
          additionalInfoSection.scrollIntoView({ behavior: 'smooth' });
        } else {
          additionalInfoSection.style.maxHeight = "0px";
        }
      }

    </script>
<!-- //* OTP -->
  <script>
 document.addEventListener("DOMContentLoaded", function () {
    const sendOtpBtn = document.querySelector("#sendOtpBtn");
    const emailInput = document.querySelector("#memberEmail");
    const alertContainer = document.querySelector("#alert-container");

    sendOtpBtn.addEventListener("click", function () {
        const email = emailInput.value.trim();

        if (!email) {
            showAlert("danger", "Please enter an email.");
            return;
        }

        console.log("Checking email:", email); // Debugging log

        // Call check-email.php to verify if the email exists
        fetch("backend-add-authenticate/check-email.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email: email }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Response data:", data); // Debugging log

                if (data.exists) {
                    // Email already exists
                    showAlert("danger", data.message);
                } else {
                    // Email is available, proceed to send OTP
                    sendOtp(email);
                }
            })
            .catch((error) => {
                console.error("Error during email check:", error);
                showAlert("danger", "An error occurred while checking the email. Please try again.");
            });
    });

    function sendOtp(email) {
        fetch("PHPMailer-OTP/otp_backend.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email: email, action: "send" }),
        })
            .then((response) => response.json())
            .then((data) => {
                showAlert(data.status === "success" ? "success" : "danger", data.message);
            })
            .catch(() => {
                showAlert("danger", "An error occurred while sending OTP. Please try again.");
            });
    }

    function showAlert(type, message) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }
});


</script>


<script>
  document.querySelector("#verifyOtpBtn").addEventListener("click", function () {
    const email = document.querySelector("#memberEmail").value.trim();
    const otp = document.querySelector("#otp").value.trim();

    if (!email || !otp) {
        showAlert("danger", "Please fill in both email and OTP fields.");
        return;
    }

    // AJAX request to verify OTP
    fetch("PHPMailer-OTP/otp_backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ email: email, otp: otp, action: "verify" }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Verify OTP response:", data); // Debugging log
            if (data.status === "success") {
                showAlert("success", "OTP verified successfully!");
            } else {
                showAlert("danger", data.message || "Invalid or expired OTP.");
            }
        })
        .catch((error) => {
            console.error("Error during OTP verification:", error);
            showAlert("danger", "An error occurred while verifying OTP. Please try again.");
        });
});

function showAlert(type, message) {
    const alertContainer = document.getElementById("alert-container");
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
}

</script>




</body>

</html>
