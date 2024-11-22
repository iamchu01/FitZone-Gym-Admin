<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>
    <title>Program Creation - GYYMS Admin</title>
  
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php 
    $trainers = find_all('tbl_add_instructors');
    $all_programs = join_pr_program();
$special_programs = join_sp_program();
$special_enrolled = join_sp_program();
    ?>
   <?php

if (isset($_POST['create_special_program'])) {
    // Required fields for the special program form
    $req_fields = array('program-title', 'trainer-id', 'slots', 'membership-fee', 'duration-days', 'schedule-start', 'schedule-end', 'program-description');
    validate_fields($req_fields);

    if (empty($errors)) {
        // Retrieve and sanitize form inputs
        $program_title = remove_junk($db->escape($_POST['program-title']));
        $trainer_id = remove_junk($db->escape($_POST['trainer-id']));
        $slots = remove_junk($db->escape($_POST['slots']));
        $membership_fee = remove_junk($db->escape($_POST['membership-fee']));
        $duration_days = remove_junk($db->escape($_POST['duration-days']));
        $schedule_start = remove_junk($db->escape($_POST['schedule-start']));
        $schedule_end = remove_junk($db->escape($_POST['schedule-end']));
        $program_description = remove_junk($db->escape($_POST['program-description']));

        // Optional start and end dates
        $start_date = isset($_POST['start-date']) && $_POST['start-date'] !== "" ? remove_junk($db->escape($_POST['start-date'])) : NULL;
        $end_date = isset($_POST['end-date']) && $_POST['end-date'] !== "" ? remove_junk($db->escape($_POST['end-date'])) : NULL;

        // Get current date for creation
        $created_at = make_date(); // Assuming this generates the current date

        // Insert query for adding a new special program
        $query = "INSERT INTO tbl_special_programs (program_title, trainer_id, slots, membership_fee, duration_days, schedule_start, schedule_end, start_date, end_date, program_description, created_at) ";
        $query .= "VALUES ('{$program_title}', '{$trainer_id}', '{$slots}', '{$membership_fee}', '{$duration_days}', '{$schedule_start}', '{$schedule_end}', '{$start_date}', '{$end_date}', '{$program_description}', '{$created_at}')";

        // Execute the query and handle success or failure
        if ($db->query($query)) {
            $session->msg('s', "Special program created successfully!");
         
        } else {
            $session->msg('d', 'Sorry, failed to create special program!');
            redirect('offered-programs.php', false);
        }
    } else {
        $session->msg("d", $errors); // Display validation errors
        redirect('offered-programs.php', false);
    }
}
?>
<?php
if (isset($_POST['create_program'])) {
    // Required fields
    $req_fields = array('program-title', 'trainer-id', 'duration-days', 'program-description');
    validate_fields($req_fields);

    if (empty($errors)) {
        // Escape and sanitize input data
        $program_title = remove_junk($db->escape($_POST['program-title']));
        $trainer_id = remove_junk($db->escape($_POST['trainer-id']));
        $duration_days = remove_junk($db->escape($_POST['duration-days']));
        $program_description = remove_junk($db->escape($_POST['program-description']));
       

        // Get the current timestamp
        $created_at = make_date();

        // Insert query for the program
        $query = "INSERT INTO tbl_programs (program_title, trainer_id, duration_days,  program_description, created_at)";
        $query .= " VALUES ('{$program_title}', '{$trainer_id}', '{$duration_days}', '{$program_description}', '{$created_at}')";

        // Execute the query and check for success
        if ($db->query($query)) {
            $session->msg('s', "Program created successfully!");
            echo "<script>
            setTimeout(function(){
                window.location.href = 'offered-programs.php';
            }, 100);
            </script>";
        } else {
            $session->msg('d', 'Sorry, failed to create program!');
            redirect('offered-programs.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('offered-programs.php', false);
    }
}
?>


    <style>
   
   #creationbtn{
 
        border: none;
        color: white;
        margin-bottom: 2%;
       
    }

    #creationbtn{
     
        padding-bottom: 2%;
        border: none;
        color: white;
        height: 50px;
      
    }

    #creationbtn:hover {
        transform: translateY(-3px);
        box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
    }

    #creationbtn:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.75);
    }

    </style>
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
                            <h3 class="page-title">Program Creation</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>  
                                <li class="breadcrumb-item">Program list</li>                  
                            </ul>
                        </div>
                     
                    </div>
                </div>
                <!-- /Page Header -->
                 
                <div class="col">
                            <button class="btn btn-success" id="creationbtn" data-bs-toggle="modal" data-bs-target="#createProgramModal">
                                <i class="fa fa-plus-square-o"></i> Create Regular Program
                            </button>
                        </div>
                        <div class="col">
                            <button class="btn btn-secondary" id="creationbtn"  data-bs-toggle="modal" data-bs-target="#createSpecialProgramModal">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Create Special Program
                            </button>
                        </div>
                        <div class="col-md-12" style="margin-top:2%;">
    <div class="panel panel-default">
        <div class="panel-heading clearfix"> 
            <div class="col">Search Programs</div>     
            <div class="col-md-4">
                <div class="input-group">                                             
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="program-search" class="form-control" placeholder="Type Program name...">
                </div>
            </div>
        </div>
        <div class="panel-body">
            <!-- Programs Table -->
            <div class="table-responsive">
                <h4>Regular Programs</h4>
                <table class="table custom-table datatable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Programs List</th>
                            <th class="text-center" style="width: 100px;">Enrolled</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_programs as $program): ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucfirst($program['program_title'])); ?></td>
                                <td><?php echo remove_junk(ucfirst($program['instructor_name'])); ?></td>
                              
                                <td class="text-center">
                                    <div class="dropdown action-label">
                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-dot-circle-o text-primary"></i> Actions
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#editProgramModal" 
                                                onclick="setEditProgram(<?php echo $program['program_id']; ?>, '<?php echo remove_junk(ucfirst($program['program_title'])); ?>')">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="programs.php" method="post" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo (int)$program['program_id']; ?>">
                                                <button type="submit" name="delete_program" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this Program?');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Special Programs Table -->


        </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading clearfix bg-secondary" > 
            <div class="col">Search Programs</div>     
            <div class="col-md-4">
                <div class="input-group">                                             
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" id="program-search" class="form-control" placeholder="Type Program name...">
                </div>
            </div>
        </div>
        <div class="panel-body">
   
    <div class="table-responsive">
    <h4>Special Programs</h4>
    <table class="table custom-table datatable">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Special Programs List</th>
                <th class="text-center" style="width: 100px;">Trainor</th>
                <th class="text-center" style="width: 100px;">Enrolled</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($special_programs as $special): ?>
                <tr>
                    <td class="text-center"><?php echo count_id(); ?></td>
                    <td><?php echo remove_junk(ucfirst($special['program_title'])); ?></td>
                    <!-- Display the instructor's name -->
                    <td><?php echo remove_junk(ucfirst($special['instructor_name'])); ?></td>
                    <td class="text-center">
                        <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-dot-circle-o text-primary"></i> Actions
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#editSpecialProgramModal" 
                                   onclick="setEditSpecialProgram(<?php echo $special['program_id']; ?>, '<?php echo remove_junk(ucfirst($special['program_title'])); ?>')">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form action="programs.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo (int)$special['program_id']; ?>">
                                    <button type="submit" name="delete_special_program" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this Special Program?');">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    </div>
</div> 
</div>

                <!-- Create Program Modal -->
                <div class="modal" id="createProgramModal" tabindex="-1" aria-labelledby="createProgramModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="createProgramModalLabel">Create Program</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form action="" method="POST">
                                <!-- Program Title -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Program Title</label>
                                    <div class="col-md-10">
                                        <input type="text" name="program-title" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Select Trainer -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Select Trainer</label>
                                    <div class="col-md-10">
                                        <select name="trainer-id" class="form-control" required>
                                            <option value="">Select a trainer</option>
                                            <?php foreach ($trainers as $trainer): ?>
                                                <option value="<?php echo $trainer['instructor_id']; ?>">
                                                    <?php echo $trainer['first_name'] . ' ' . $trainer['last_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                          

                                <!-- Duration in Days -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Duration (Days)</label>
                                    <div class="col-md-10">
                                        <input type="number" name="duration-days" class="form-control" placeholder="Enter duration in days" required>
                                    </div>
                                </div>

                                <!-- Program Description -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Program Description</label>
                                    <div class="col-md-10">
                                        <textarea name="program-description" rows="5" cols="5" class="form-control" placeholder="Enter program description"></textarea>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group mb-0 row">
                                    <div class="col-md-12 text-end">
                                        <button class="btn btn-primary" type="submit" name="create_program">Create Program</button>
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
              
<!-- Create Special Program Modal -->
<div class="modal" id="createSpecialProgramModal" tabindex="-1" aria-labelledby="createSpecialProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title" id="createSpecialProgramModalLabel" style="color:white;">Create Special Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    <!-- Program Title -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Program Title</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="program-title" placeholder="Enter program title" required>
                        </div>
                    </div>

                    <!-- Select Trainer -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Select Trainer</label>
                        <div class="col-md-10">
                            <select class="form-control" name="trainer-id" required>
                                <option value="">Select a trainer</option>
                                <?php foreach ($trainers as $trainer): ?>
                                    <option value="<?php echo $trainer['instructor_id']; ?>"><?php echo $trainer['first_name'] . ' ' . $trainer['last_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Slots -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Slots</label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name="slots" required>
                        </div>
                    </div>

                    <!-- Membership Fee -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Membership Fee</label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name="membership-fee" id="membershipFee" placeholder="Enter membership fee">
                        </div>
                    </div>

                    <!-- Duration in Days -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Duration (Days)</label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name="duration-days" id="duration" placeholder="Enter duration in days" required>
                        </div>
                    </div>                  

                    <!-- Schedule -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Schedule Start</label>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="schedule-start" id="schedule-start" required>
                        </div>
                        <label class="col-form-label col-md-2">Schedule End</label>
                        <div class="col-md-4">
                            <input type="time" class="form-control" name="schedule-end" id="schedule-end" required>
                        </div>
                    </div>

                    <!-- Toggle Start and End Date -->
                    <div class="form-group row">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-secondary rounded-pill" id="toggleDatesBtn">
                                + Click to add specific dates
                            </button>
                        </div>
                    </div>

                    <!-- Optional Start and End Date (Hidden Initially) -->
                    <div class="form-group row" id="dateFields" style="display: none;">
                        <label class="col-form-label col-md-2">Start Date</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="start-date" id="startDate" placeholder="Select start date">
                        </div>
                        <label class="col-form-label col-md-2">End Date</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="end-date" id="endDate" placeholder="Select end date">
                        </div>
                    </div>

                    <!-- Program Description -->
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Program Description</label>
                        <div class="col-md-10">
                            <textarea rows="5" cols="5" class="form-control" name="program-description" placeholder="Enter program description"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group mb-0 row">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-secondary" type="submit" name="create_special_program">Create Special Program</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper-->

    <?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>


    <script>
document.getElementById('toggleDatesBtn').addEventListener('click', function () {
    const dateFields = document.getElementById('dateFields');
    const durationInput = document.getElementById('duration');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    if (dateFields.style.display === 'none') {
        dateFields.style.display = 'flex';
        durationInput.disabled = true; // Disable duration input
        durationInput.value = ''; // Clear the input value
        this.textContent = '- Remove specific dates';
        this.classList.replace('btn-secondary', 'btn-danger');
    } else {
        dateFields.style.display = 'none';
        durationInput.disabled = false; // Enable duration input
        startDateInput.value = ''; // Clear date fields
        endDateInput.value = '';
        this.textContent = '+ Click to add specific dates';
        this.classList.replace('btn-danger', 'btn-secondary');
    }
});

// Calculate and display the duration in days when dates are selected
document.getElementById('startDate').addEventListener('change', calculateDuration);
document.getElementById('endDate').addEventListener('change', calculateDuration);

function calculateDuration() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const durationInput = document.getElementById('duration');

    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (startDate && endDate && endDate >= startDate) {
        // Calculate duration in days
        const durationInDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1; // Include both start and end day
        durationInput.value = durationInDays; // Display the calculated duration
    } else if (endDate < startDate) {
        alert('End Date cannot be earlier than Start Date.');
        endDateInput.value = ''; // Clear invalid end date
        durationInput.value = ''; // Clear duration
    }
}

    </script>

</body>
</html>
