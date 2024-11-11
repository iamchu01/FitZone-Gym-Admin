<?php
include 'layouts/session.php';
include 'layouts/head-main.php';
include 'layouts/db-connection.php';

?>

<head>
  <title>Members - HRMS admin template</title>
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
                                  <i class="fa fa-eye m-r-5"></i> View Details
                                </a>
                                <a id="archive-link-<?php echo $row['member_id']; ?>"
                                  class="dropdown-item <?php echo ($status === 'Inactive') ? '' : 'disabled'; ?>" href="#"
                                  data-bs-toggle="modal" data-bs-target="#archive_member"
                                  data-id="<?php echo $row['member_id']; ?>"
                                  title="<?php echo ($status === 'Inactive') ? '' : 'Only inactive members can be archived'; ?>">
                                  <i class="fa fa-archive m-r-5"></i> Archive
                                </a>
                                <a class="dropdown-item" href="#"
                                  onclick="renewMembership(<?php echo $row['member_id']; ?>)">
                                  <i class="fa fa-refresh m-r-5"></i> Renew
                                  Membership
                                </a>
                                <a class="dropdown-item" href="#"
                                  onclick="cancelMembership(<?php echo $row['member_id']; ?>)">
                                  <i class="fa fa-ban m-r-5"></i> Cancel
                                  Membership
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
              <div class="profile-img-wrap edit-img">
                <img class="inline-block" src="assets/img/profiles/avatar-02.jpg" alt="user">
                <div class="fileupload btn">
                  <span class="btn-text">Add</span>
                  <input class="upload" type="file" required>
                </div>
              </div>

              <form id="addMemberForm" class="needs-validation member-info" method="POST"
                action="backend-add-authenticate/process-add-member.php">
                <div class="row">
                  <!-- Firstname -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input id="memberFirstname" class="form-control" type="text" name="firstname"
                        placeholder="Enter First Name" required pattern="[A-Za-z\s]+">
                      <div class="invalid-feedback">Please enter a valid first name.
                      </div>
                    </div>
                  </div>
                  <!-- Lastname -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input id="memberLastname" class="form-control" type="text" name="lastname"
                        placeholder="Enter Last Name" required pattern="[A-Za-z\s]+">
                      <div class="invalid-feedback">Please enter a valid last name.
                      </div>
                    </div>
                  </div>
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
                      <label>Gender <span style="color:red;">*</span></label>
                      <div class="position-relative">
                        <select class="form-select py-2" name="Gender" id="gender-selector" required>
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
                        <input type="text" id="memberDateOfBirth" class="form-control datetimepicker" name="dateOfBirth"
                          placeholder="Select Date of Birth">
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
                  <!-- Email -->
                  <div class="col-sm-6">
                    <label>Email Address <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="email" class="form-control" id="memberEmail" name="email" placeholder="Enter Email"
                        required>
                      <div class="invalid-feedback">Please enter a valid email address.
                      </div>
                    </div>
                  </div>
                  <!-- Password -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Password</label>
                      <div class="input-group">
                        <input id="memberPassword" class="form-control" type="password" name="password" value="12345"
                          readonly>
                        <button class="btn btn-outline-secondary" type="button" id="toggleMemberPassword">
                          <i class="fa fa-eye-slash" id="passwordIcon"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- Address Form -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="region">Region <span class="text-danger">*</span></label>
                      <select id="region" class="form-select" name="region" required>
                        <option value="" disabled selected>Select Region</option>
                      </select>
                      <input type="hidden" name="region_text" id="region-text">
                      <div class="invalid-feedback">Please select a region.</div>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="province">Province <span class="text-danger">*</span></label>
                      <select id="province" class="form-select" name="province" required>
                        <option value="" disabled selected>Select Province</option>
                        <!-- Populate provinces dynamically -->
                      </select>
                      <input type="hidden" name="province_text" id="province-text">
                      <div class="invalid-feedback">Please select a province.</div>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="city">City/Municipality <span class="text-danger">*</span></label>
                      <select id="city" class="form-select" name="city" required>
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
                      <label for="barangay">Barangay <span class="text-danger">*</span></label>
                      <select id="barangay" class="form-select" name="barangay" required>
                        <option value="" disabled selected>Select Barangay</option>
                        <!-- Populate barangays dynamically -->
                      </select>
                      <input type="hidden" name="barangay_text" id="barangay-text">
                      <div class="invalid-feedback">Please select a barangay.</div>
                    </div>
                  </div>
                </div>
                <div class="submit-section" style="margin-top: 10px;">
                  <button class="btn btn-primary submit-btn" type="submit">Add
                    Member</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- //*Add Member Modal -->

      <!-- //* Add Member Success Modal -->
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
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
        // Show the success modal if the page has a query parameter indicating success
        <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
          $('#successModal').modal('show');
          if (history.pushState) {
            var newUrl = window.location.href.split('?')[0];
            window.history.pushState({
              path: newUrl
            }, '', newUrl);
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


</body>

</html>
