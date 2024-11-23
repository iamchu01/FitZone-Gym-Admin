<?php
include 'layouts/session.php';
include 'layouts/head-main.php';
include 'layouts/db-connection.php';
require_once 'PHPMailer-OTP/classes/OTPVerification.php';

$message = ''; // Variable to display messages
$otpVerified = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otpHandler = new OTPVerification($conn);

    // Check if email is verified
    if ($otpHandler->isEmailVerified($email)) {
        $otpVerified = true; // Set OTP verified flag

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
              header('Location: add-member.php?success=added');
              exit;
          }else {
                error_log("Database insertion failed: " . $stmt->error);
                $message = "Failed to add member to the database.";
            }
        } else {
          error_log("Database insertion failed: " . $stmt->error);
          $message = "Failed to add member to the database.";
        }
    } else {
        $message = "Email not verified. Please verify your email first.";
    }
}

// AJAX handler for checking email existence
if (isset($_POST['action']) && $_POST['action'] === 'check_email') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tbl_add_members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode([
        'exists' => $row['count'] > 0,
        'status' => $row['count'] > 0 ? 'exists' : 'available',
        'message' => $row['count'] > 0 ? 'Email already exists.' : 'Email is available.'
    ]);
    exit;
}
?>




<head>
  <title>Members</title>
  <?php include 'layouts/title-meta.php'; ?>
  <?php include 'layouts/head-css.php'; ?>
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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


        <!-- //*success message after adding member -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
            <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                Member added successfully!
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
                              <a href="member-profile.php?member_id=<?php echo $row['member_id']; ?>" class="dropdown-item">
                                    <i class="fa fa-eye"></i> View Profile
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
              

              <!-- //*Email exist message -->
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
                        placeholder="Enter First Name" 
                        pattern="[A-Za-z\s]+" 
                        title="First name should only contain letters and spaces." 
                        required />
                    </div>
                  </div>

                  <!-- Middle Name -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Middle Name</label>
                      <input id="memberMiddlename" class="form-control" type="text" name="middlename" 
                        placeholder="Enter Middle Name" 
                        pattern="[A-Za-z\s]*" 
                        title="Middle name should only contain letters and spaces." />
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input id="memberLastname" class="form-control" type="text" name="lastname" 
                        placeholder="Enter Last Name" 
                        pattern="[A-Za-z\s]+" 
                        title="Last name should only contain letters and spaces." 
                        required />
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Email Address <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="email" class="form-control" id="memberEmail" name="email" 
                          placeholder="Enter Email" 
                          title="Please enter a valid email address." 
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

                    <div class="col-sm-6">
        <div class="form-group mb-2">
            <label>Date of Birth <span class="text-danger">*</span></label>
            <div class="cal-icon">
                <input type="text" id="dateOfBirth" class="form-control datetimepicker" name="dateOfBirth"
                    placeholder="Select Date of Birth" required>
                <small id="dateWarning" class="text-danger" style="display: none;">Please select a valid date of birth.</small>
            </div>
        </div>
          </div>
          <div class="col-sm-6">
              <div class="form-group mb-2">
                  <label>Age</label>
                  <input type="text" id="age" name="member_age" class="form-control" placeholder="Age" readonly>
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


      <!-- //*success add modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Member has been successfully added!
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="backend-add-authenticate/add-member.js"></script>

<!-- Include Bootstrap Datepicker CSS and JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

</body>

</html>
