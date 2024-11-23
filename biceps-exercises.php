<?php include 'layouts/head-main.php'; ?>
<?php
// Include the database connection file
include 'layouts/db-connection.php';
session_start();

// Fetch biceps exercises from the database
$category = 'biceps';  

// Prepare the SQL query
$stmt = $conn->prepare("SELECT * FROM muscle_exercise WHERE muscle_category = ?");
if (!$stmt) {
    die('Statement preparation failed: ' . $conn->error);
}

// Bind the parameter to the query
$stmt->bind_param("s", $category);

// Execute the query
if ($stmt->execute()) {
    // Get the result set
    $result = $stmt->get_result();
} else {
    // Show error if query execution fails
    echo 'Error executing query: ' . $stmt->error;
    exit;
}

// Handle adding a new exercise
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $exerciseName = $_POST['exercise_name'];
    $muscleCategory = $_POST['muscle_category'];  // This will be 'biceps' by default
    $description = $_POST['exercise_description'];

    // Handle file upload (image or video)
    if (isset($_FILES['exercise_image']) && $_FILES['exercise_image']['error'] == 0) {
        // Get the file data
        $fileTmpName = $_FILES['exercise_image']['tmp_name'];
        $fileType = $_FILES['exercise_image']['type'];
        $fileName = $_FILES['exercise_image']['name'];
        
        // Define separate directories for images and videos
        $imageUploadDir = 'uploads/';  // For images
        $videoUploadDir = 'C:\\xampp\\htdocs\\sendtoal\\FitZone-Gym\\Videos\\biceps';  // Updated directory for videos
        
        // Ensure the directories exist
        if (!is_dir($imageUploadDir)) {
            mkdir($imageUploadDir, 0777, true);
        }
        if (!is_dir($videoUploadDir)) {
            mkdir($videoUploadDir, 0777, true);
        }
        
        // Check if the uploaded file is an image or a video
        if (strpos($fileType, 'image') !== false) {
            $filePath = $imageUploadDir . basename($fileName);
            move_uploaded_file($fileTmpName, $filePath);
            $mediaColumn = 'me_image';
            $mediaData = $filePath;
            $mediaType = $fileType;
        } elseif (strpos($fileType, 'video') !== false) {
            $filePath = $videoUploadDir . DIRECTORY_SEPARATOR . basename($fileName); // Use DIRECTORY_SEPARATOR for better compatibility
            move_uploaded_file($fileTmpName, $filePath);
            $mediaColumn = 'me_video';
            $mediaData = $filePath;
            $mediaType = $fileType;
        } else {
            // Invalid file type
            exit('Invalid file type.');
        }

    // Insert the exercise into the database
    $stmt = $conn->prepare("INSERT INTO muscle_exercise (me_name, muscle_category, me_description, $mediaColumn, me_media_type) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $exerciseName, $muscleCategory, $description, $fileName, $mediaType);
        if ($stmt->execute()) {
            // Success, redirect to the exercises page
            header("Location: biceps-exercises.php"); // Replace with your exercises page URL
            exit;
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Statement preparation failed: " . $conn->error;
    }
} else {
  
    exit;
}

}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biceps Exercises</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <style>
    /* Style for the table images */
    table img {
        width: 65px;         
        height: 65px;        
        object-fit: cover;    
        border-radius: 5px;  
    }

    /* Responsive image sizing for mobile devices */
    @media (max-width: 768px) {
        table img {
            width: 80px;    
            height: 80px;   
        }
        <style>
        /* Style for the table images */
        table img {
            width: 65px;         
            height: 65px;        
            object-fit: ;    
            border-radius: 5px;  
        }

        /* Responsive image sizing for mobile devices */
        @media (max-width: 768px) {
            table img {
                width: 80px;    
                height: 80px;   
            }
        }
     
        /* Optional: Hover effect to ensure the dropdown stays open */
        .dropdown:hover .dropdown-action {
            display: block;
        }

        .main-wrapper {
            width: 100%;
            height: auto;
            margin: 0%;
            flex-direction: column;
        }
        .card {
            transition: transform 0.3s ease; /* Smooth transition */
        }

        .card:hover {
            transform: scale(1.05); /* Zoom effect */
            background-color: #48c92f;
            color: #fff;
        }
       .sort-indicator {
            font-size: 0.8em;
            margin-left: 5px;
            color: #888;
}
        .card:hover {
            transition: transform 0.3s ease;
            transform: scale(1.05);
            background-color: #bff7d3;
        }  
        .card{
            width: 200px;
            align-items: center;
            height: 300px;
            margin: 1%;
            cursor: pointer;
        }
        .card img{
            width: auto;
            height: 250px;
        }
        .card.active {
            border: 2px solid #28a745;
            background-color: #48c92f;
        }
        .page-wrapper{
            width: 80%;
        }
        .dropdown-item:hover{
            transition: transform 0.3s ease;
            transform: scale(1.05); 
            background-color: #48c92f;
        }
        
        .table {
    table-layout: fixed;
    width: 100%;
    
}

    }
</style>

</head>
<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Biceps Exercises</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="targeted-exercise.php">Muscle Group</a></li>
                                <li class="breadcrumb-item active">Biceps Exercises</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExerciseModal">
                                Add Biceps Workout
                            </button>
                        </div>
                    </div>
                </div>
                

                <!-- Table displaying biceps exercises -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Illustration</th>
                                    <th>Name</th>
                                    <th>Muscle Category</th>
                                    <th>Description</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if there are any results
                                if ($result->num_rows > 0) {
                                    // Loop through the results and display them
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                        <td>
                    <?php
                    // Base path for uploads
                    $uploadDir = 'uploads/';

                    // Check if the image is available
                    if (!empty($row['me_image']) && file_exists($uploadDir . $row['me_image'])) {
                        // Display the image
                        echo '<img src="' . $uploadDir . htmlspecialchars($row['me_image']) . '" alt="' . htmlspecialchars($row['me_name']) . ' exercise image" style="width: 100px; height: 65px;">';
                    }
                    // Check if the video is available
                    elseif (!empty($row['me_video']) && file_exists($uploadDir . $row['me_video'])) {
                        // Display the video
                        echo '<video width="100" controls>
                                <source src="' . $uploadDir . htmlspecialchars($row['me_video']) . '" type="video/mp4">
                                Your browser does not support the video tag.
                              </video>';
                    } else {
                        // If neither image nor video exists, show a placeholder or default message
                        echo '<p>No media available</p>';
                    }
                    ?>
                </td>
                                            <td><?php echo htmlspecialchars($row['me_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['muscle_category']); ?></td>
                                            <td class="description-cell" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($row['me_description']); ?>">
                                                <?php echo nl2br(htmlspecialchars($row['me_description'])); ?>
                                                <td class="text-end">

                                            <div class=".dropdown:hover .dropdown-action">
                                                <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                             </a>
                                             <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#" onclick="openViewModal('<?php echo addslashes($row['me_name']); ?>', '<?php echo addslashes($row['me_description']); ?>', '<?php echo $uploadDir . htmlspecialchars($row['me_image'] ?: $row['me_video']); ?>')">
                                             <i class="fa fa-eye m-r-5"></i> View
                                             </a>
                                             <a class="dropdown-item" href="#" onclick="openEditModal(<?php echo $row['me_id']; ?>, '<?php echo addslashes($row['me_name']); ?>', '<?php echo addslashes($row['me_description']); ?>')">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                             </a>
                                             <a class="dropdown-item" href="#" onclick="openDeleteModal(<?php echo $row['me_id']; ?>)">
                                             <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                             </tr>
                          <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5">No exercises found for biceps.</td></tr>';
                             }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
 <!-- Add Modal -->
<div class="modal fade" id="addExerciseModal" tabindex="-1" aria-labelledby="addExerciseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExerciseModalLabel">Add New Biceps Exercise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>  
            </div>
            <form id="addExerciseForm" action="biceps-exercises.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Exercise Name -->
                    <div class="mb-3">
                        <label for="exerciseName" class="form-label">Exercise Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="exerciseName" 
                            name="exercise_name" 
                            placeholder="Enter the exercise name">
                    </div>

                    <!-- Muscle Category (Read-Only) -->
                    <div class="mb-3">
                        <label for="muscleCategory" class="form-label">Muscle Category</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="muscleCategory" 
                            name="muscle_category" 
                            value="biceps" 
                            readonly>
                    </div>

                    <!-- Exercise Description -->
                    <div class="mb-3">
                        <label for="exerciseDescription" class="form-label">Description</label>
                        <textarea 
                            class="form-control" 
                            id="exerciseDescription" 
                            name="exercise_description" 
                            rows="3" 
                            placeholder="Provide a brief description"></textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="exerciseImage" class="form-label">Exercise Image or Video</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="exerciseImage" 
                            name="exercise_image" 
                            accept="image/*,video/*" 
                            >
                        <small class="form-text text-muted"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addExerciseForm').addEventListener('submit', function(event) {
    // Prevent form submission to handle validation
    event.preventDefault();
    
    // Remove previous highlights
    const fields = document.querySelectorAll('.form-control');
    fields.forEach(field => {
        field.classList.remove('is-invalid');
    });

    let valid = true;
    
    // Check each required field
    const exerciseName = document.getElementById('exerciseName');
    const description = document.getElementById('exerciseDescription');
    const exerciseImage = document.getElementById('exerciseImage');

    // Validate Exercise Name
    if (!exerciseName.value.trim()) {
        exerciseName.classList.add('is-invalid');
        valid = false;

        let existingErrorMessage = exerciseName.parentElement.querySelector('.invalid-feedback');
        if (existingErrorMessage) {
            existingErrorMessage.remove();
        }

        // Create and append the new error message
        let errorMessage = document.createElement('div');
        errorMessage.classList.add('invalid-feedback');
        errorMessage.innerText = 'Exercise name is required';
        exerciseName.parentElement.appendChild(errorMessage);
    }
    
    // Validate Description
    if (!description.value.trim()) {
        description.classList.add('is-invalid');
        valid = false;

        let existingErrorMessage = description.parentElement.querySelector('.invalid-feedback');
        if (existingErrorMessage) {
            existingErrorMessage.remove();
        }

        // Create and append the new error message
        let errorMessage = document.createElement('div');
        errorMessage.classList.add('invalid-feedback');
        errorMessage.innerText = 'Description is required';
        description.parentElement.appendChild(errorMessage);
    }

    // Validate File Upload
    if (!exerciseImage.files.length) {
        exerciseImage.classList.add('is-invalid');
        valid = false;

        let existingErrorMessage = exerciseImage.parentElement.querySelector('.invalid-feedback');
        if (existingErrorMessage) {
            existingErrorMessage.remove();
        }

        let errorMessage = document.createElement('div');
        errorMessage.classList.add('invalid-feedback');
        errorMessage.innerText = 'Please upload an image or video';
        exerciseImage.parentElement.appendChild(errorMessage);
    } else {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov'];
        const file = exerciseImage.files[0];
        if (!allowedTypes.includes(file.type)) {
            exerciseImage.classList.add('is-invalid');
            valid = false;

            let existingErrorMessage = exerciseImage.parentElement.querySelector('.invalid-feedback');
            if (existingErrorMessage) {
                existingErrorMessage.remove();
            }

            let errorMessage = document.createElement('div');
            errorMessage.classList.add('invalid-feedback');
            errorMessage.innerText = 'Only image or video files are allowed';
            exerciseImage.parentElement.appendChild(errorMessage);
        }
    }

    // If all fields are valid, submit the form
    if (valid) {
        this.submit();
    }
});

// Add an event listener to remove the 'is-invalid' class when the modal is closed
var addExerciseModal = document.getElementById('addExerciseModal');
addExerciseModal.addEventListener('hidden.bs.modal', function () {
    const fields = document.querySelectorAll('.form-control');
    fields.forEach(field => {
        field.classList.remove('is-invalid');
    });
});

</script>

<style>
    .is-invalid {
        border-color: red;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    }
</style>

<!-- View Exercise Modal -->
<div class="modal fade" id="viewExerciseModal" tabindex="-1" aria-labelledby="viewExerciseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewExerciseModalLabel">Exercise Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="viewMedia"></div> <!-- Media will be displayed here -->
                <div class="mb-3">
                    <label for="viewExerciseName" class="form-label">Exercise Name</label>
                    <input type="text" class="form-control" id="viewExerciseName" readonly>
                </div>
                <div class="mb-3">
                    <label for="viewMuscleCategory" class="form-label">Muscle Category</label>
                    <input type="text" class="form-control" id="viewMuscleCategory" readonly>
                </div>
                <div class="mb-3">
                    <label for="viewExerciseDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="viewExerciseDescription" rows="3" readonly></textarea>
                </div>
            </div>
            <!-- Modal Footer with Close Button -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Delete Exercise Modal -->
<div class="modal fade" id="deleteExerciseModal" tabindex="-1" aria-labelledby="deleteExerciseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteExerciseModalLabel">Delete Exercise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this exercise? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteExerciseLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
    function openDeleteModal(exerciseId) {
    const deleteUrl = `delete-exercise.php?id=${exerciseId}`;
    document.getElementById('deleteExerciseLink').setAttribute('href', deleteUrl);
    new bootstrap.Modal(document.getElementById('deleteExerciseModal')).show();
}
</script>

<!-- JavaScript to Open View Modal -->
<script>
function openViewModal(name, description, mediaPath) {
    document.getElementById('viewExerciseName').value = name;
    document.getElementById('viewMuscleCategory').value = 'biceps'; // Fixed muscle category
    document.getElementById('viewExerciseDescription').value = description;

    const mediaContainer = document.getElementById('viewMedia');

    // Check if the media is an image or a video
    if (mediaPath.includes('.mp4')) {
        mediaContainer.innerHTML = `<video width="100%" controls><source src="${mediaPath}" type="video/mp4">Your browser does not support the video tag.</video>`;
    } else {
        mediaContainer.innerHTML = `<img src="${mediaPath}" class="img-fluid" alt="Exercise Media">`;
    }

    // Open the modal
    new bootstrap.Modal(document.getElementById('viewExerciseModal')).show();
}
</script>

<!-- Edit Exercise Modal -->
<div class="modal fade" id="editExerciseModal" tabindex="-1" aria-labelledby="editExerciseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExerciseModalLabel">Edit Biceps Exercise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExerciseForm" action="update-exercise4.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Hidden Input for Exercise ID -->
                    <input type="hidden" id="editExerciseId" name="exercise_id">

                    <!-- Exercise Name -->
                    <div class="mb-3">
                        <label for="editExerciseName" class="form-label">Exercise Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="editExerciseName" 
                            name="exercise_name" 
                           
                            >
                    </div>

                    <!-- Exercise Description -->
                    <div class="mb-3">
                        <label for="editExerciseDescription" class="form-label">Description</label>
                        <textarea 
                            class="form-control" 
                            id="editExerciseDescription" 
                            name="exercise_description" 
                            rows="3" 
                            ></textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="editExerciseImage" class="form-label">Exercise Image or Video</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="editExerciseImage" 
                            name="exercise_image" 
                            accept="image/*,video/*"
                           
                            >
                        <small class="form-text text-muted">Upload a new image or video to replace the existing media.</small>
                        <div id="fileError" class="invalid-feedback d-none">Please upload a valid image or video file.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    // Function to open the edit modal and populate the fields
    function openEditModal(id, name, description) {
        document.getElementById('editExerciseId').value = id;
        document.getElementById('editExerciseName').value = name;
        document.getElementById('editExerciseDescription').value = description;

        // Show the modal
        new bootstrap.Modal(document.getElementById('editExerciseModal')).show();
    }

    // File input validation with highlighting
    document.getElementById('editExerciseImage').addEventListener('change', function(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const fileError = document.getElementById('fileError');

        // Clear previous highlight and error message
        fileInput.classList.remove('is-invalid');
        fileError.classList.add('d-none');
        
        if (file) {
            const fileType = file.type;
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/ogg'];

            if (!allowedTypes.includes(fileType)) {
                // Highlight the field as invalid
                fileInput.classList.add('is-invalid');
                fileError.classList.remove('d-none');

                // Clear the file input
                event.target.value = '';
            }
        }
    });
</script>


    <!-- JS Libraries (including Bootstrap) -->
    <?php include 'layouts/vendor-scripts.php'; ?>
    
</body>
</html>