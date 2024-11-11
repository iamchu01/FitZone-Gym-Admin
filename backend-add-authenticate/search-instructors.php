<?php
include 'layouts/db-connection.php';

$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = $conn->real_escape_string(trim($_GET['search']));
    $search_query = "AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR phone_number LIKE '%$search%')";
}

// Fetch instructors based on search criteria
$query = "SELECT * FROM tbl_add_instructors WHERE archive_status = 'Unarchived' $search_query ORDER BY instructor_id DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<table class="table table-striped custom-table datatable">';
    echo '<thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Gender</th>
                <th>Specialization</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
          </thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
        $phone_number = htmlspecialchars($row['phone_number']);
        $gender = htmlspecialchars($row['gender']);
        $specialization = htmlspecialchars($row['specialization']);
        $status = htmlspecialchars($row['status']);
        $status_label = $status === 'Active' ? 'text-success' : 'text-danger';

        echo "<tr>
                <td>
                    <h2 class='table-avatar'>
                        <a href='instructor-profile.php?id={$row['instructor_id']}' class='avatar'><img src='assets/img/profiles/avatar-19.jpg' alt=''></a>
                        <a href='instructor-profile.php?id={$row['instructor_id']}'>$full_name</a>
                    </h2>
                </td>
                <td>$phone_number</td>
                <td>$gender</td>
                <td>$specialization</td>
                <td>
                    <div class='dropdown action-label'>
                        <a href='#' class='btn btn-white btn-sm btn-rounded dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                            <i id='status-{$row['instructor_id']}' class='fa fa-dot-circle-o $status_label'></i> 
                            <span id='status-text-{$row['instructor_id']}'>$status</span>
                        </a>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' href='#' onclick='updateStatus({$row['instructor_id']}, \"Active\")'><i class='fa fa-dot-circle-o text-success'></i> Active</a>
                            <a class='dropdown-item' href='#' onclick='updateStatus({$row['instructor_id']}, \"Inactive\")'><i class='fa fa-dot-circle-o text-danger'></i> Inactive</a>
                        </div>
                    </div>
                </td>
                <td class='text-end'>
                    <div class='dropdown dropdown-action'>
                        <a href='#' class='action-icon dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'><i class='material-icons'>more_vert</i></a>
                        <div class='dropdown-menu dropdown-menu-right'>
                            <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#edit_client'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                            <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#delete_client'><i class='fa fa-trash-o m-r-5'></i> Delete</a>
                        </div>
                    </div>
                </td>
              </tr>";
    }
    echo '</tbody></table>';
} else {
    echo "<p class='text-center'>No instructors found</p>";
}
?>
