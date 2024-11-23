<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>
<?php require_once('vincludes/load.php'); ?>

<head>

  <title>Instructors - HRMS admin template</title>

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
              <h3 class="page-title">Instructors</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Instructors</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_instructor"><i
                  class="fa fa-plus"></i> Add Instructor</a>
            </div>
          </div>
        </div>
        <!-- /Page Header -->

        <!-- //*Search Filter -->
        <div class="row filter-row">
          <div class="col-md-6 col-md-3">
            <div class="form-group form-focus">
              <input type="text" id="searchInput" class="form-control floating" placeholder="">
              <label class="focus-label">Search</label>
            </div>
          </div>
        </div>
        <!-- //*Search Filter -->

        <div id="instructorsTable">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table datatable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Phone Number</th>
                      <th>Gender</th>
                      <th>Specialization</th>
                      <th>Status</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch instructors from the database
                    $query = "SELECT * FROM tbl_add_instructors WHERE archive_status = 'Unarchived' ORDER BY instructor_id DESC";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        // Concatenate first and last name for full name display
                        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        $phone_number = htmlspecialchars($row['phone_number']);
                        $gender = htmlspecialchars($row['gender']);
                        $specialization = htmlspecialchars($row['specialization']);
                        $status = htmlspecialchars($row['status']);

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
                          <td><?php echo $phone_number; ?></td>
                          <td><?php echo $gender; ?></td>
                          <td><?php echo $specialization; ?></td>
                          <td>
                            <div class="dropdown action-label">
                              <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i id="status-<?php echo $row['instructor_id']; ?>"
                                  class="fa fa-dot-circle-o <?php echo $status === 'Active' ? 'text-success' : 'text-danger'; ?>"></i>
                                <span id="status-text-<?php echo $row['instructor_id']; ?>"><?php echo $status; ?></span>
                              </a>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="#"
                                  onclick="updateStatus(<?php echo $row['instructor_id']; ?>, 'Active')"><i
                                    class="fa fa-dot-circle-o text-success"></i>
                                  Active</a>
                                <a class="dropdown-item" href="#"
                                  onclick="updateStatus(<?php echo $row['instructor_id']; ?>, 'Inactive')"><i
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
                                <a class="dropdown-item"
                                  href="instructor-profile.php?id=<?php echo $row['instructor_id']; ?>">
                                  <i class="fa fa-eye m-r-5"></i> View Profile
                                </a>
                                <a id="archive-link-<?php echo $row['instructor_id']; ?>"
                                  class="dropdown-item <?php echo ($status === 'Inactive') ? '' : 'disabled'; ?>" href="#"
                                  data-bs-toggle="modal" data-bs-target="#archive_instructor"
                                  data-id="<?php echo $row['instructor_id']; ?>"
                                  title="<?php echo ($status === 'Inactive') ? '' : 'Only inactive instructors can be archived'; ?>">
                                  <i class="fa fa-archive m-r-5"></i> Archive
                                </a>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <?php
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center'>No instructors found</td></tr>";
                    }
                    ?>
                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /Page Content -->

      <!-- //* add instructor modal -->
      <div id="add_instructor" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Instructor</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- <div class="profile-img-wrap edit-img">
                  <img class="inline-block" src="assets/img/profiles/avatar-02.jpg" alt="user">
                  <div class="fileupload btn">
                    <span class="btn-text">Add</span>
                    <input class="upload" type="file" required>
                  </div>
              </div> -->

              <!-- //* Add Instructor Form -->
              <form id="addUserForm" class="needs-validation instructor-info" method="POST"
                action="backend-add-authenticate/process-add-instructor.php">
                <div class="row">

                  <!-- //* firstname -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input id="firstname" class="form-control" type="text" name="firstname"
                        placeholder="Enter First Name" required pattern="[A-Za-z\s]+">
                      <div class="invalid-feedback">Please enter a valid first name.
                      </div>
                    </div>
                  </div>

                  <!-- //* lastname -->
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Last Name <span class="text-danger">*</span></label>
                      <input id="lastname" class="form-control" type="text" name="lastname"
                        placeholder="Enter Last Name" required pattern="[A-Za-z\s]+">
                      <div class="invalid-feedback">Please enter a valid last name.
                      </div>
                    </div>
                  </div>

                  <!-- //* phone number -->
                  <div class="col-sm-6">
                    <label>Mobile Number <span style="color:red;">*</span></label>
                    <div class="form-group">
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">+63</span>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="ex. 9123456789"
                          required minlength="10" maxlength="10" pattern="9[0-9]{9}">
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

                  <!-- //* date of birth -->
                  <div class="col-sm-6">
                    <div class="form-group mb-2">
                      <label>Date of Birth <span class="text-danger">*</span></label>
                      <div class="cal-icon">
                        <input type="text" id="dateOfBirth" class="form-control datetimepicker" name="dateOfBirth"
                          placeholder="Select Date of Birth" required>
                        <small id="dateWarning" class="text-danger" style="display: none;">Please select a
                          valid date of
                          birth.</small>
                      </div>
                    </div>
                  </div>

                  <!-- //* age -->
                  <div class="col-sm-6">
                    <div class="form-group mb-2">
                      <label>Age</label>
                      <input type="text" id="age" name="instructor_age" class="form-control" placeholder="Age" readonly>
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

                  <!-- //* new specialized field -->
                  <div class="form-group mt-3">
                    <label>Specialization <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <!-- Textarea for entering multiple specializations -->
                      <textarea class="form-control" id="specializationTextarea" name="specialization" rows="4"
                        placeholder="Enter specializations, separated by commas or new lines" required></textarea>
                    </div>
                  </div>
                  <!-- //* new specialized field -->

                </div>

                <div class="submit-section" style="margin-top: 10px;">
                  <button class="btn btn-primary submit-btn" type="submit">Add
                    Instructor</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- //* add instructor modal -->


      <!-- Archive Instructor Modal -->
      <div class="modal custom-modal fade" id="archive_instructor" tabindex="-1" role="dialog"
        aria-labelledby="archiveInstructorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="archiveInstructorLabel">Archive Instructor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to archive this instructor?</p>
              <input type="hidden" id="instructorIdToArchive" value="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onclick="confirmArchive()">Archive</button>
            </div>
          </div>
        </div>
      </div>
      <!-- /Archive Instructor Modal -->

      <!-- Archive Success Modal -->
      <div class="modal fade" id="archiveSuccessModal" tabindex="-1" aria-labelledby="archiveSuccessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="archiveSuccessModalLabel">Success</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Instructor archived successfully!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Modal -->
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="successModalLabel">Success</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Instructor added successfully!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
          </div>
        </div>
      </div>


      <!-- Error Modal -->
      <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="errorModalLabel">Error</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


    </div>
    <!-- /Page Wrapper -->


  </div>
  <!-- end main wrapper-->
  <script src="backend-add-authenticate/add-instructor.js"></script>
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>

  <!-- //* update status instructor modal -->
  <script>
    function updateStatus(instructorId, newStatus) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "backend-add-authenticate/instructor-update-status.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Update the status text
          var statusTextElement = document.querySelector(`#status-text-${instructorId}`);
          statusTextElement.textContent = newStatus;

          // Update the icon class based on the new status
          var statusIconElement = document.querySelector(`#status-${instructorId}`);
          statusIconElement.className =
            `fa fa-dot-circle-o ${newStatus === "Active" ? "text-success" : "text-danger"}`;

          // Enable or disable the archive option based on the new status
          var archiveLink = document.querySelector(`#archive-link-${instructorId}`);
          if (newStatus === "Inactive") {
            archiveLink.classList.remove('disabled');
            archiveLink.title = '';
          } else {
            archiveLink.classList.add('disabled');
            archiveLink.title = 'Only inactive instructors can be archived';
          }
        }
      };

      xhr.send("id=" + instructorId + "&status=" + newStatus);
    }
  </script>

  <!-- //* archive instructor success modal -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      $('#archive_instructor').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var instructorId = button.data('id');
        $('#instructorIdToArchive').val(instructorId);
      });
    });

    function confirmArchive() {
      var instructorId = document.getElementById('instructorIdToArchive').value;

      if (instructorId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "backend-add-authenticate/archive-instructor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText.trim();
            if (response === "success") {
              // Close the archive modal
              $('#archive_instructor').modal('hide');

              // Show the archive success modal
              setTimeout(function () {
                $('#archiveSuccessModal').modal('show');
              }, 500);

              // Optionally, refresh or update the table after the modal closes
              $('#archiveSuccessModal').on('hidden.bs.modal', function () {
                location.reload();
              });
            } else {
              console.error("Error archiving instructor: " + response);
            }
          }
        };

        xhr.send("id=" + instructorId);
      }
    }
  </script>

  <!-- //* add instructor success modal -->
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

    function handleAddInstructor() {
      // Simulate form submission and show success modal
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "backend-add-authenticate/process-add-instructor.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var response = xhr.responseText.trim();
          if (response === "success") {
            // Show the success modal
            $('#successModal').modal('show');

            // Reload the page after the modal is closed
            $('#successModal').on('hidden.bs.modal', function () {
              location.reload();
            });
          } else {
            console.error("Error adding instructor: " + response);
          }
        }
      };

      // Gather form data (use actual data collection logic as needed)
      var formData = "firstName=John&lastName=Doe"; // Replace with real data
      xhr.send(formData);
    }
  </script>

  <!-- //* search -->
  <script>
    document.getElementById('searchInput').addEventListener('input', function () {
      const searchValue = this.value;

      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'backend-add-authenticate/search-instructors.php?search=' + encodeURIComponent(searchValue), true);
      xhr.onload = function () {
        if (this.status === 200) {
          document.getElementById('instructorsTable').innerHTML = this.responseText;
        }
      };
      xhr.send();
    });
  </script>
</body>

</html>
