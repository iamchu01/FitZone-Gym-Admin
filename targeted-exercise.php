<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php require_once('vincludes/load.php'); ?>
<?php include 'layouts/title-meta.php'; ?>
<?php include 'layouts/head-css.php'; ?>
<head>
<script>
    function previewMedia(type) {
    const imageInput = document.getElementById('exercise_image');
    const videoInput = document.getElementById('exercise_video');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    
    // Clear previous previews
    imagePreview.innerHTML = '';
    videoPreview.innerHTML = '';
    
    if (type === 'image') {
        const file = imageInput.files[0];
        if (file && file.type.startsWith('image')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Exercise Image Preview" width="100">`;
            };
            reader.readAsDataURL(file);
        }
    } else if (type === 'video') {
        const file = videoInput.files[0];
        if (file && file.type.startsWith('video')) {
            const url = URL.createObjectURL(file);
            videoPreview.innerHTML = `<video width="300" controls>
                                        <source src="${url}" type="${file.type}">
                                        Your browser does not support the video tag.
                                       </video>`;
        }
    }
}
function setViewExercise(id, name, category, description, video, mediaType) {
    document.getElementById('viewExerciseName').textContent = name;
    document.getElementById('viewExerciseCategory').textContent = category;
    document.getElementById('viewExerciseDescription').textContent = description;

    const mediaDiv = document.getElementById('viewExerciseMedia');
    if (mediaType === 'video/mp4' && video) {
        mediaDiv.innerHTML = `<video width="150" controls><source src="../videos/${video}" type="video/mp4"></video>`;
    } else if (mediaType === 'image/jpeg' && video) {
        mediaDiv.innerHTML = `<img src="data:image/jpeg;base64,${video}" alt="Exercise Media" width="100">`;
    } else {
        mediaDiv.innerHTML = `<p>No media available.</p>`;
    }
}

function setEditExercise(id, name, category, description, video, mediaType) {
    document.getElementById('editExerciseId').value = id;
    document.getElementById('editExerciseName').value = name;
    document.getElementById('editExerciseCategory').value = category;
    document.getElementById('editExerciseDescription').value = description;

    // Show current media based on the type
    const currentMediaDiv = document.getElementById('currentMedia');
    if (mediaType === 'video/mp4' && video) {
        currentMediaDiv.innerHTML = `<video width="150" controls><source src="videos/${video}" type="video/mp4"></video>`;
    } else if (mediaType === 'image/jpeg' && video) {
        currentMediaDiv.innerHTML = `<img src="data:image/jpeg;base64,${video}" alt="Exercise Media" width="100">`;
    } else {
        currentMediaDiv.innerHTML = `<p>No media uploaded</p>`;
    }
}
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Targeted Exercise - HRMS Admin Template</title>
<?php
    $selected_category = isset($_GET['category']) ? $_GET['category'] : null;
    $exercises = [];

    if ($selected_category) {
        $exercises = find_by_column('muscle_exercise', 'muscle_category', $selected_category);
    }
    ?>
<?php
// Check if form is submitted
if (isset($_POST['submit'])) {

    // Get input values and sanitize them
    $exercise_name = remove_junk($db->escape($_POST['exercise_name']));
    $description = remove_junk($db->escape($_POST['description']));
    $muscle_group = remove_junk($db->escape($_POST['muscle_group']));

    // Check if an exercise image is uploaded
    if (isset($_FILES['exercise_image']) && $_FILES['exercise_image']['error'] == 0) {
        $image_name = $_FILES['exercise_image']['name'];
        $image_tmp_name = $_FILES['exercise_image']['tmp_name'];
        $image_type = $_FILES['exercise_image']['type'];

        // Ensure the file is an image
        if (in_array($image_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            // Read image content and convert to blob for storage
            $image_data = file_get_contents($image_tmp_name);
            $image_data = $db->escape($image_data); // Escape the image data before inserting into DB
        } else {
            $session->msg('d', 'Invalid image type. Please upload a valid image.');
            redirect('targeted-exercise.php', false);
            exit();
        }
    } else {
        // If no image is provided, set image data to NULL
        $image_data = NULL;
    }

    // Handle Video File Upload
    $video_name = NULL;
    if (isset($_FILES['exercise_video']) && $_FILES['exercise_video']['error'] == 0) {
        $video_tmp_name = $_FILES['exercise_video']['tmp_name'];
        $video_type = $_FILES['exercise_video']['type'];
        $video_name = $_FILES['exercise_video']['name'];

        // Ensure the file is a video (you can customize the allowed video types as needed)
        if (in_array($video_type, ['video/mp4', 'video/avi', 'video/mov', 'video/mkv'])) {
            $video_target_path = 'videos/' . basename($video_name); // Set the target path for the video
            if (move_uploaded_file($video_tmp_name, $video_target_path)) {
                // Store only the video file name (relative path)
                $video_name = basename($video_name); // Only store the file name in the database
            } else {
                $session->msg('d', 'Failed to upload the video file.');
                redirect('targeted-exercise.php', false);
                exit();
            }
        } else {
            $session->msg('d', 'Invalid video type. Please upload a valid video file.');
            redirect('targeted-exercise.php', false);
            exit();
        }
    }

    // Date when exercise is added or updated
    $date = make_date();

    // If 'exercise_id' is present, it's an update, otherwise, it's an insert (add)
    if (isset($_POST['exercise_id']) && !empty($_POST['exercise_id'])) {
        // Update logic
        $exercise_id = remove_junk($db->escape($_POST['exercise_id']));
        
        // SQL query to update the exercise
        $query = "UPDATE muscle_exercise SET
                    me_name = '{$exercise_name}',
                    me_description = '{$description}',
                    muscle_category = '{$muscle_group}',
                    me_image = '{$image_data}',
                    me_video = '{$video_name}',
                    date_updated = '{$date}'
                  WHERE me_id = '{$exercise_id}'";
        
        // Execute update query
        if ($db->query($query)) {
            $session->msg('s', 'Exercise updated successfully!');
        } else {
            $session->msg('d', 'Sorry, failed to update the exercise.');
        }

    } else {
        // Insert logic (add new exercise)
        $query = "INSERT INTO muscle_exercise (me_name, me_description, muscle_category, me_image, me_video, date_added) 
                  VALUES ('{$exercise_name}', '{$description}', '{$muscle_group}', '{$image_data}', '{$video_name}', '{$date}')";

        // Execute insert query
        if ($db->query($query)) {
            $session->msg('s', 'Exercise added successfully!');
        } else {
            $session->msg('d', 'Sorry, failed to add the exercise.');
        }
    }

    // Redirect to exercise management page
    redirect('targeted-exercise.php', false);
}
?>


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
                            <h3 class="page-title">Targeted Exercise</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Targeted Exercise</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
        </div>
                <!-- Add Exercise Button -->
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExerciseModal">
                        <i class="fa fa-plus"></i> Add New Exercise
                    </button>
                </div>

                <!-- Panel -->
                <div class="panel">
                    <div class="panel-heading">Select and View Exercises</div>
                    <div class="panel-body">
                        <!-- Muscle Group Dropdown -->
                        <form method="GET" action="">
                            <div class="form-group">
                                <label for="muscleGroup">Muscle Group</label>
                                <select id="muscleGroup" name="category" class="form-control" onchange="this.form.submit()">
                                    <option value="">Select Muscle Group</option>
                                    <option value="chest" <?php echo ($selected_category == 'chest') ? 'selected' : ''; ?>>Chest</option>
                                    <option value="shoulders" <?php echo ($selected_category == 'shoulders') ? 'selected' : ''; ?>>Shoulders</option>
                                    <option value="triceps" <?php echo ($selected_category == 'triceps') ? 'selected' : ''; ?>>Triceps</option>
                                    <option value="biceps" <?php echo ($selected_category == 'biceps') ? 'selected' : ''; ?>>Biceps</option>
                                    <option value="back" <?php echo ($selected_category == 'back') ? 'selected' : ''; ?>>Back</option>
                                    <option value="core" <?php echo ($selected_category == 'core') ? 'selected' : ''; ?>>Core</option>
                                    <option value="legs" <?php echo ($selected_category == 'legs') ? 'selected' : ''; ?>>Legs</option>
                                </select>
                            </div>
                        </form>

                        <!-- Display Exercises -->
                        <div class="table-responsive">
                            <table class="table custom-table datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th class="text-center" style="width: 100px;">Media</th>
                                        <th>Exercise Name</th>
                                        <th>Muscle group</th>
                                        <th>Description</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($exercises as $exercise): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++; ?></td>
                                        <td class="text-center">
                                            <?php if ($exercise['me_media_type'] === 'video/mp4'): ?>
                                                <video width="150" controls>
    <source src="../videos/<?php echo htmlspecialchars($exercise['me_video']); ?>" type="video/mp4">
</video>

                                            <?php else: ?>
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($exercise['me_image']); ?>" alt="Exercise Image" width="100">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($exercise['me_name']); ?></td>
                                        <td><?php echo htmlspecialchars($exercise['muscle_category']); ?></td>
                                        <td><?php echo htmlspecialchars($exercise['me_description']); ?></td>
                                        <td class="text-center">
                                            <div class="dropdown action-label">
                                                <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-primary"></i> Actions
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <!-- View Button -->
                                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#viewExerciseModal" 
                                                       onclick="setViewExercise(<?php echo $exercise['me_id']; ?>, '<?php echo htmlspecialchars($exercise['me_name']); ?>', '<?php echo htmlspecialchars($exercise['muscle_category']); ?>', '<?php echo htmlspecialchars($exercise['me_description']); ?>', '<?php echo htmlspecialchars($exercise['me_video']); ?>', '<?php echo htmlspecialchars($exercise['me_media_type']); ?>')">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#editExerciseModal" 
                                                       onclick="setEditExercise(<?php echo $exercise['me_id']; ?>, '<?php echo htmlspecialchars($exercise['me_name']); ?>', '<?php echo htmlspecialchars($exercise['muscle_category']); ?>', '<?php echo htmlspecialchars($exercise['me_description']); ?>', '<?php echo htmlspecialchars($exercise['me_video']); ?>', '<?php echo htmlspecialchars($exercise['me_media_type']); ?>')">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form action="delete-exercise.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?php echo (int)$exercise['me_id']; ?>">
                                                        <button type="submit" name="delete_exercise" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this exercise?');">
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
        </div>
    </div>

<!-- Add Exercise Modal -->
<div class="modal" id="addExerciseModal" tabindex="-1" aria-labelledby="addExerciseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExerciseModalLabel">Add Exercise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="targeted-exercise.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exercise_name">Exercise Name</label>
                        <input type="text" class="form-control" id="exercise_name" name="exercise_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="muscle_group">Muscle Group</label>
                        <select class="form-control" id="muscle_group" name="muscle_group" required>
                            <option value="chest">Chest</option>
                            <option value="shoulders">Shoulders</option>
                            <option value="triceps">Triceps</option>
                            <option value="biceps">Biceps</option>
                            <option value="back">Back</option>
                            <option value="core">Core</option>
                            <option value="legs">Legs</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exercise_image">Upload Image</label>
                        <input type="file" class="form-control" id="exercise_image" name="exercise_image" accept="image/*" onchange="previewMedia('image')">
                        <div id="imagePreview" style="margin-top: 10px;"></div>
                    </div>
                    <div class="form-group">
                        <label for="exercise_video">Upload Video</label>
                        <input type="file" class="form-control" id="exercise_video" name="exercise_video" accept="video/*" onchange="previewMedia('video')">
                        <div id="videoPreview" style="margin-top: 10px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Save Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Edit Exercise Modal -->
    <div class="modal fade" id="editExerciseModal" tabindex="-1" aria-labelledby="editExerciseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExerciseModalLabel">Edit Exercise</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="targeted-exercise.php" enctype="multipart/form-data">
                    <input type="hidden" name="exercise_id" id="editExerciseId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editExerciseName">Exercise Name</label>
                            <input type="text" class="form-control" id="editExerciseName" name="exercise_name" required>
                        </div>
                        <div class="form-group">
                            <label for="editExerciseCategory">Muscle Category</label>
                            <select class="form-control" id="editExerciseCategory" name="muscle_category" required>
                                <option value="chest">Chest</option>
                                <option value="shoulders">Shoulders</option>
                                <option value="triceps">Triceps</option>
                                <option value="biceps">Biceps</option>
                                <option value="back">Back</option>
                                <option value="core">Core</option>
                                <option value="legs">Legs</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editExerciseDescription">Description</label>
                            <textarea class="form-control" id="editExerciseDescription" name="exercise_description" rows="3" required></textarea>
                        </div>
                        <div class="form-group" id="currentMedia">
                            <!-- Current media will appear here -->
                        </div>
                        <div class="form-group">
                            <label for="editExerciseImage">Upload New Image/Video (Optional)</label>
                            <input type="file" class="form-control" id="editExerciseImage" name="exercise_image" accept="image/*,video/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Exercise</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Exercise Modal -->
    <div class="modal fade" id="viewExerciseModal" tabindex="-1" aria-labelledby="viewExerciseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewExerciseModalLabel">View Exercise</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="viewExerciseName"></h5>
                    <p><strong>Muscle Group:</strong> <span id="viewExerciseCategory"></span></p>
                    <p><strong>Description:</strong> <span id="viewExerciseDescription"></span></p>
                    <div id="viewExerciseMedia" class="text-center"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

   
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

</body>
</html>
