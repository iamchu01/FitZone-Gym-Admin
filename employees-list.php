<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<?php
// Check if form is submitted (this will handle form submission through AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['password']; // Collect the password value

    // Insert data into database
    $sql = "INSERT INTO tbl_members (FName, LName, Email, Phone_Number, DOB, Gender, Address, Password) 
            VALUES ('$firstname', '$lastname', '$email', '$phonenumber', '$dob', '$gender', '$address', '$password')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $response = array(
            "id" => $last_id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "phonenumber" => $phonenumber
        );
        echo json_encode($response);
        exit();
    } else {
        echo json_encode(array("error" => $conn->error));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member List - Gym Management System</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

</head>
<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Members</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Add Member</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Member ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Member Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <a href="#" class="btn btn-primary w-100">Search</a>  
                    </div>     
                    <div class="col-sm-6 col-md-3"> 
                        <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add_member"><i class="fa fa-plus"></i> Add Member</a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Member ID</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Join Date</th>
                                        <th>Membership Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tbl_members";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>
                                                    <h2 class='table-avatar'>
                                                        <a href='profile.php' class='avatar'><img alt='' src='assets/img/profiles/avatar-02.jpg'></a>
                                                        <a href='profile.php'>" . $row["FName"] . " " . $row["LName"] . "</a>
                                                    </h2>
                                                </td>
                                                <td>MEM-" . $row["member_id"] . "</td>
                                                <td>" . $row["Email"] . "</td>
                                                <td>" . $row["Phone_Number"] . "</td>
                                                <td>" . date('d M Y', strtotime($row["Date_Created"])) . "</td>
                                                <td><a class='btn bg-info btn-sm text-dark'>No Membership</a></td>
                                                <td class='text-end'>
                                                    <div class='dropdown dropdown-action'>
                                                        <a href='#' class='action-icon dropdown-toggle' data-bs-toggle='dropdown'><i class='material-icons'>more_vert</i></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <a class='dropdown-item edit-member' href='#' data-id='" . $row['member_id'] . "' data-bs-toggle='modal' data-bs-target='#edit_member'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                                                            <a class='dropdown-item' href='#' data-id='" . $row['member_id'] . "' data-bs-toggle='modal' data-bs-target='#archive_member'><i class='fa fa-trash-o m-r-5'></i> Archive</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No members found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Member Modal -->
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
                            <form id="addMemberForm" method="POST">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="first_name" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="last_name" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Password</label>
                                            <div class="input-group">
                                                <input id="password1" class="form-control" type="password" name="password" placeholder="Enter Password" value="12345">
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-top-right-radius: 0.375rem; border-bottom-right-radius: 0.375rem;">
                                                    <i class="fa fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            <!-- <div class="invalid-feedback">Please enter a password.</div> -->
                                        </div>
                                    </div>
                 
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input class="form-control" type="tel" name="phone" required>
                                        </div>
                                    </div>
                                    

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="dobInput" required onchange="calculateAge()">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Age</label>
                                <input id="ageInput" class="form-control" type="text" readonly>
                            </div>
                        </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- //* Remove Address -->
                                    <!-- <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Address</label>
                                            <input class="form-control" type="text" name="address">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn mb-3" type="submit">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Add Member Modal -->

        </div>
    </div>
    <!-- /Main Wrapper -->

    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>

    <script>
$(document).ready(function() {
            var table = $('.datatable').DataTable();
            $('#addMemberForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: '', // Current PHP page handles the form submission
        type: 'POST',
        data: formData,
        success: function(response) {
            $('#add_member').modal('hide');
            var member = JSON.parse(response);

            table.row.add([
                '<h2 class="table-avatar"><a href="profile.php" class="avatar"><img alt="" src="assets/img/profiles/avatar-02.jpg"></a><a href="profile.php">' + member.firstname + ' ' + member.lastname + '</a></h2>',
                'MEM-' + member.id,
                member.email,
                member.phonenumber,
                new Date().toLocaleDateString(),
                '<a class="btn bg-info btn-sm text-dark">No Membership</a>',
                '<div class="dropdown dropdown-action">' +
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="material-icons">more_vert</i></a>' +
                    '<div class="dropdown-menu dropdown-menu-right">' +
                        '<a class="dropdown-item edit-member" href="#" data-id="' + member.id + '" data-bs-toggle="modal" data-bs-target="#edit_member"><i class="fa fa-pencil m-r-5"></i> Edit</a>' +
                        '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#archive_member"><i class="fa fa-trash-o m-r-5"></i> Archive</a>' +
                    '</div>' +
                '</div>'
            ]).draw(false);
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
});

});


//*DOB 
     function calculateAge() {
        const dobInput = document.getElementById('dobInput').value;
        if (dobInput) {
            const birthdate = new Date(dobInput);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDifference = today.getMonth() - birthdate.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            // Validate age between 15 and 100
            if (age < 15 || age > 100) {
                alert('Age must be between 15 and 100 years. Please select a valid date of birth.');
                document.getElementById('ageInput').value = '';
            } else {
                document.getElementById('ageInput').value = age;
            }
        } else {
            document.getElementById('ageInput').value = '';
        }
    }


//*Password Toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password1');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.innerHTML = type === 'password' ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
});



    </script>
</body>
</html>
