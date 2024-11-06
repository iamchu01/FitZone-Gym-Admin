<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>
<head>
    

    <title>Form Basic Input - HRMS admin template</title>

    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>


</head>

<body>
    <div class="main-wrapper">
    <?php include 'layouts/menu.php'; ?>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Free Program</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="offered-programs.php">Program list</a></li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                            <a href="special-program.php" class="btn add-btn"><i class="fa fa-plus"></i> Special program</a>
                        </div>
                </div>
            </div> 
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="#">
                            <div class="form-group row">
                                    <label class="col-form-label col-md-2">Program profile</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Program Title</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Program Description</label>
                                    <div class="col-md-10">
                                        <textarea rows="5" cols="5" class="form-control" placeholder="Enter text here"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                            <label for="program_duration">Program Duration (Weeks)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary" type="button" id="decrement_duration">-</button>
                                </div>
                                <input type="text" class="input-group-prepend" id="program_duration" name="program_duration" placeholder="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="increment_duration">+</button>
                                </div>
                            </div>
                            
                        </div>  
                        <div class="card mb-0">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Weeks</h4>
                            <div id="week-table-container"></div>
                        </div>
                        <div class="card-body">
                        
                        </div>
                    </div>
                                <div class="form-group mb-0 row">
                                    <dclass="col-md-10">
                                        <div class="input-group">
                                            <button class="btn btn-primary" type="button">Create Program</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                   
                </div>
            </div>
        
        </div>          
    </div>
<!-- Create exercise per day -->
<div class="modal fade custom-modal" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Make modal larger -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dayModalLabel">Day Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Display the selected week and day -->
        <div class="mb-3">
          <p id="selectedWeekDay" class="fw-bold">Week 1 - Monday</p> <!-- This will be updated dynamically -->
        </div>

        <!-- Title input for the day (e.g., "Chest Day") -->
        <div class="mb-3">
          <label for="dayTitle" class="form-label">Day Title</label>
          <input type="text" class="form-control" id="dayTitle" placeholder="Enter day title (e.g., Chest Day)">
        </div>

        <!-- Exercise controls -->
        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-success" id="addExercise">+ Add Exercise</button>
            <button type="button" class="btn btn-danger" id="removeExercise">Clear All</button>
          </div>
        </div>

        <!-- Container for exercises -->
        <div id="exerciseContainer" class="mb-3"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="saveDayPlan">Save Plan</button>
      </div>
    </div>
  </div>
</div>
<!-- Exercise Selection Modal -->
<div class="modal fade" id="exerciseSelectionModal" tabindex="-1" aria-labelledby="exerciseSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exerciseSelectionModalLabel">Select Exercise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="exerciseListTable">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Exercise Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="exerciseList">
                        <!-- Exercises will be populated here -->
                    </tbody>
                </table>
                <div class="text-center mt-3">
                    <button class="btn btn-primary" id="confirmExercise">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>
<script src="assets/js/create-program.js"></script>




</body>

</html>