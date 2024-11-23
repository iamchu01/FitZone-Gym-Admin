<?php

if (isset($_GET['category'])) {
    $category = $_GET['category'];

    $stmt = $conn->prepare("SELECT * FROM muscle_exercise WHERE muscle_category = ?");
    $stmt->bind_param("s", $category);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped custom-table mb-0 datatable">';
            echo '<thead><tr><th>Illustration</th><th>Name</th><th>Muscle Category</th><th>Description</th><th>Actions</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr">';
                echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['me_image']) . '" alt="' . htmlspecialchars($row['me_name'] . ' exercise image') . '" style="width: 100px;"></td>';
                echo '<td>' . htmlspecialchars($row['me_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['muscle_category']) . '</td>';
                echo '<td class="description-cell" data-bs-toggle="tooltip" title="' . htmlspecialchars($row['me_description']) . '">' . 
                nl2br(htmlspecialchars($row['me_description'])) . '</td>';
                           echo '<td class="text-end">
                        <div class="dropdown dropdown-action" dropdown-toggle">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <div class="dropdown-menu" >
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"  onclick="openViewModal(' . $row['me_id'] . ', \'' . addslashes($row['me_name']) . '\', \'' . addslashes($row['me_description']) . '\', \'' . addslashes($row['muscle_category']) . '\', \'' . base64_encode($row['me_image']) . '\')">
                                    <i class="fa fa-eye m-r-5"></i> View
                                </a>
                               <a class="dropdown-item" href="save-exercise.php" data-bs-toggle="modal"  
                                onclick="openEditModal(' . $row['me_id'] . ', \'' . addslashes($row['me_name']) . '\', \'' . addslashes($row['me_description']) . '\', \'' . addslashes($row['muscle_category']) . '\', \'' . base64_encode($row['me_image']) . '\')">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                                </a>

                                <a class="dropdown-item" href="delete-exercise.php?id=' . $row['me_id'] . '" onclick="return confirm(\'Are you sure you want to delete this exercise?\');">
                                    <i class="fa fa-trash-o m-r-5"></i> Delete
                                </a>
                            </div>
                        </div>
                      </td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>'; 
        } else {
            echo '<p>No exercises found in this category.</p>';
        }
    } else {
        echo '<p>Error executing query.</p>';
    }

    $query = "SELECT * FROM muscle_exercise WHERE me_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $exerciseId);
$stmt->execute();
$result = $stmt->get_result();
$exercise = $result->fetch_assoc();

if ($exercise) {
    if ($exercise['me_media_type'] == 'image/jpeg' || $exercise['me_media_type'] == 'image/png') {
        echo '<img src="' . $exercise['me_image'] . '" alt="Exercise Image" width="300">';
    } elseif ($exercise['me_media_type'] == 'video/mp4') {
        echo '<video width="320" height="240" controls>
                <source src="' . $exercise['me_video'] . '" type="video/mp4">
                Your browser does not support the video tag.
              </video>';
    }
}

    $stmt->close();
}
?>
