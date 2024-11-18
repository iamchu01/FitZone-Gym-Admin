<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>

  <title>Blank</title>

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
              <h3 class="page-title">Blank</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Blank</li>
              </ul>
            </div>
            <div class="col-auto float-end ms-auto">
              <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_member"><i class="fa fa-plus">
                </i> Blank</a>
            </div>
          </div>
        </div>
        <!-- /Page Header -->

        <!-- //*Search Filter -->
        <div class="row filter-row">
          <div class="col-md-6 col-md-3">
            <div class="form-group form-focus">
              <input type="text" class="form-control floating">
              <label class="focus-label">Search</label>
            </div>
          </div>
        </div>
        <!-- //*Search Filter -->

        <!-- Content Starts -->
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
                <form id="addMemberForm" class="needs-validation member-info" method="POST"
                  action="backend-add-authenticate/process-add-member.php">
                  <div class="row">
                    <!-- Basic Info -->
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>First Name <span style="color:red;">*</span></label>
                        <input id="firstname" class="form-control" type="text" name="firstname"
                          placeholder="Enter First Name" required pattern="[A-Za-z\s]+">
                        <div class="invalid-feedback">Please enter a valid first name.</div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Last Name <span style="color:red;">*</span></label>
                        <input id="lastname" class="form-control" type="text" name="lastname"
                          placeholder="Enter Last Name" required pattern="[A-Za-z\s]+">
                        <div class="invalid-feedback">Please enter a valid last name.</div>
                      </div>
                    </div>
                    <!-- Mobile Number -->
                    <div class="col-sm-6">
                      <label>Mobile Number <span style="color:red;">*</span></label>
                      <div class="form-group">
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">+63</span>
                          <input type="text" class="form-control" id="mobile" name="mobile" placeholder="ex. 9123456789"
                            required minlength="10" maxlength="10" pattern="9[0-9]{9}">
                          <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Hidden Additional Info Section -->
                  <div id="additionalInfoSection"
                    style="overflow: hidden; max-height: 0; transition: max-height 0.5s ease;">
                    <h5 style="text-align:center; font-size: 20px; margin-top: 20px;">Additional Information</h5>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Additional Info Category</label>
                          <select class="form-select" name="additional_info_category">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Student">Student</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="Corporate Employee">Corporate Employee</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Notes</label>
                          <input type="text" class="form-control" name="additional_info_notes"
                            placeholder="Enter additional notes">
                        </div>
                      </div>
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

        <!-- /Content End -->

      </div>
      <!-- //* Page Content -->


    </div>
    <!-- //* Page Wrapper -->


  </div>
  <!-- end main wrapper-->


  <?php include 'layouts/customizer.php'; ?>
  <!-- JAVASCRIPT -->
  <?php include 'layouts/vendor-scripts.php'; ?>

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

</body>

</html>
