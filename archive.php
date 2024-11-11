<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'restored'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#successModal').modal('show');
            if (history.pushState) {
                var newUrl = window.location.href.split('?')[0];
                window.history.pushState({ path: newUrl }, '', newUrl);
            }
        });
    </script>
<?php endif; ?>


<head>

    <title>Archive - HRMS admin template</title>

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
                        <h3 class="page-title">Archived Instructors</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Archive</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Archive Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Gender</th>
                                    <th>Specialization</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'layouts/db-connection.php'; // Include the $conn variable for database connection

                                // Fetch archived instructors from the database
                                $query = "SELECT * FROM tbl_add_instructors WHERE archive_status = 'Archived' ORDER BY instructor_id DESC";
                                $result = $conn->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                        $phone_number = htmlspecialchars($row['phone_number']);
                                        $gender = htmlspecialchars($row['gender']);
                                        $specialization = htmlspecialchars($row['specialization']);
                                        ?>
                                        <tr data-id="<?php echo $row['instructor_id']; ?>">
                                            <td><?php echo $full_name; ?></td>
                                            <td><?php echo $phone_number; ?></td>
                                            <td><?php echo $gender; ?></td>
                                            <td><?php echo $specialization; ?></td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" onclick="restoreInstructor(<?php echo $row['instructor_id']; ?>)"><i class="fa fa-undo m-r-5"></i> Restore</a>
                                                        <a class="dropdown-item" href="#" onclick="deleteInstructor(<?php echo $row['instructor_id']; ?>)"><i class="fa fa-trash m-r-5"></i> Permanently Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No archived instructors found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Archive Table -->
            
        </div>
        <!-- /Page Content -->

        <!-- Restore Confirmation Modal -->
        <div class="modal fade" id="restoreConfirmationModal" tabindex="-1" aria-labelledby="restoreConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restoreConfirmationModalLabel">Confirm Restore</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to restore this instructor?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmRestoreBtn">Restore</button>
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
                        Instructor restored successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->


<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<script>
let selectedInstructorId;

function restoreInstructor(instructorId) {
    // Store the instructor ID to use when confirming the action
    selectedInstructorId = instructorId;
    // Show the confirmation modal
    $('#restoreConfirmationModal').modal('show');
}

// Function to confirm the restore when the modal's button is clicked
document.getElementById('confirmRestoreBtn').addEventListener('click', function() {
    if (selectedInstructorId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "restore-archive-instructor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText.trim();
                if (response.includes('successfully')) {
                    $('#restoreConfirmationModal').modal('hide'); // Hide the modal after success

                    // Display the success modal
                    $('#successModal .modal-body').text('Instructor restored successfully!');
                    $('#successModal').modal('show');

                    // Remove the row from the table after showing the modal
                    setTimeout(function() {
                        var row = document.querySelector(`tr[data-id='${selectedInstructorId}']`);
                        if (row) {
                            row.remove();
                        }
                    }, 500); // Delay to let the user see the modal
                } else {
                    console.error("Error restoring instructor");
                }
            }
        };
        xhr.send("id=" + selectedInstructorId);
    }
});




function deleteInstructor(instructorId) {
    if (confirm('Are you sure you want to permanently delete this instructor? This action cannot be undone.')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete-instructor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
                location.reload(); // Refresh the page to reflect the changes
            }
        };
        xhr.send("id=" + instructorId);
    }
}
</script>

</body>

</html>
