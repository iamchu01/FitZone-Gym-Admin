<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>
<?php 
$_SESSION['last_accessed'] = $_SERVER['PHP_SELF'];

 ?>

 
<head>
    <title>Dashboard - GYYMS Admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <style>
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

    </style>
</head>

<body>
    <?php include 'layouts/body.php'; ?>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title"> Shoulder Exercises</h3>
                            <ul class="breadcrumb">
                            <li class="breadcrumb-item active"> Shoulder Exercises</li>
                            <li class="breadcrumb-item "><a href="targeted-exercise.php"> Muscle groups</a></li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">                   
                            <a href="" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#exerciseModal"><i class="fa fa-plus"></i>Add Exercises</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row card-container">
                <div class="col card" id="anterior-deltiod" onclick="showExercises('anterior deltiod', this)">       
                    <img src="assets/img/s1.JPG" alt="Anterior deltiod">
                    <div class="card-body">
                        <h5 class="card-title">Anterior deltiod</h5> 
                    </div>
                </div>
                <div class="col card" id="medial-deltiod" onclick="showExercises('medial deltiod', this)">              
                    <img src="assets/img/s2.JPG" alt="Medial deltiod">
                    <div class="card-body">
                        <h5 class="card-title">Medial deltiod</h5> 
                    </div>
                </div>
                <div class="col card" id="posterior-deltiod" onclick="showExercises('posterior deltiod', this)">             
                    <img src="assets/img/s3.JPG" alt="Posterior deltiod">
                    <div class="card-body">
                        <h5 class="card-title">Posterior deltiod</h5> 
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="exercise-list">
                <!-- Exercise items will be inserted here dynamically -->
            </div>

            <!-- Add Exercise Modal -->
            <div class="modal fade" id="exerciseModal" tabindex="-1" aria-labelledby="exerciseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exerciseModalLabel">Exercise Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form id="exerciseFormAdd" enctype="multipart/form-data">
                                <input type="hidden" id="exercise_id" name="exercise_id" value="">
                                <div class="mb-3 text-center">
                                    <label for="exerciseImage" class="form-label">Exercise Image/GIF</label>
                                    <input type="file" class="form-control" id="exerciseImage" name="exerciseImage" accept="image/*,video/*" required onchange="previewImage(event)">
                                </div>
                                <div class="mb-3 text-center">
                                    <img id="imagePreview" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto;">
                                </div>
                                <div class="mb-3">
                                    <label for="exerciseName" class="form-label">Exercise Name</label>
                                    <input type="text" class="form-control" id="exerciseName" name="exerciseName" placeholder="Enter exercise name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="exerciseDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="exerciseDescription" name="exerciseDescription" rows="4" placeholder="Enter exercise description" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="muscleCategory" class="form-label">Muscle Category</label>
                                    <select class="form-select" id="muscleCategory" name="muscleCategory" required>
                                        <option value="anterior deltiod">Anterior deltiod</option>
                                        <option value="medial deltiod">Medial deltiod</option>
                                        <option value="posterior deltiod">Posterior deltiod</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create exercise</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Edit Exercise Modal -->
                <div class="modal fade" id="exerciseModalEdit" tabindex="-1" aria-labelledby="exerciseModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exerciseModalLabel">Exercise Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form id="exerciseFormEdit" enctype="multipart/form-data">
                            <input type="hidden" id="exerciseIdEdit" name="exercise_id" value="">


                                    <div class="mb-3 text-center">
                                        <label for="exerciseImageEdit" class="form-label">Exercise Image/GIF</label>
                                        <input type="file" class="form-control" id="exerciseImageEdit" name="exerciseImage" accept="image/*,video/*" onchange="previewImage(event)">
                                    </div>
                                    <div class="mb-3 text-center">
                                        <img id="imagePreviewEdit" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exerciseNameEdit" class="form-label">Exercise Name</label>
                                        <input type="text" class="form-control" id="exerciseNameEdit" name="exerciseName" placeholder="Enter exercise name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exerciseDescriptionEdit" class="form-label">Description</label>
                                        <textarea class="form-control" id="exerciseDescriptionEdit" name="exerciseDescription" rows="4" placeholder="Enter exercise description" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="muscleCategoryEdit" class="form-label">Muscle Category</label>
                                        <select class="form-select" id="muscleCategoryEdit" name="muscleCategory" required>
                                            <option value="anterior deltiod">Anterior deltiod</option>
                                            <option value="medial deltiod">Medial deltiod</option>
                                            <option value="posterior deltiod">Posterior deltiod</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save exercise</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

       <!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

         <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this exercise?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a id="confirmDelete" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex">
                <!-- Left side: Image -->
                <div class="me-3" style="flex: 1; max-width: 300px;">
                    <img id="viewModalImage" class="img-fluid" alt="Exercise Image" style="border: 1px solid #dee2e6; width: 100%;">
                </div>
                <!-- Right side: Details -->
                <div style="flex: 2; border-left: 1px solid #dee2e6; padding-left: 20px;">
                    <h5 class="card-title" id="viewModalTitle">Exercise Name</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Target Muscle: <span id="viewModalCategory"></span></h6>
                    <p class="card-text" id="viewModalDescription">Exercise Description</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


            <?php include 'layouts/customizer.php'; ?>
            <?php include 'layouts/vendor-scripts.php'; ?>
        </div>
    </div>

    <script>
        // Image preview for exercise form
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; 
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        }

        // Show exercises based on muscle group
        function showExercises(category, element) {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => card.classList.remove('active'));
            element.classList.add('active');

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch-exercises.php?category=' + encodeURIComponent(category), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('exercise-list').innerHTML = this.responseText;
                } else {
                    document.getElementById('exercise-list').innerHTML = '<p>No exercises found.</p>';
                }
            };
            xhr.send();
        }

        document.getElementById('exerciseFormEdit').addEventListener('submit', handleFormSubmit);

// Attach the event listener to another form (for example, exerciseFormAdd)
document.getElementById('exerciseFormAdd').addEventListener('submit', handleFormSubmit);
function handleFormSubmit(event) {
    event.preventDefault();
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // Get form data

        fetch('save-exercise.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            if (data.success) {
                // Display the success message in the modal
                document.getElementById('successMessage').innerText = data.message; // Set message
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show(); // Show the modal
                // Optionally, close the edit modal
                const editModal = bootstrap.Modal.getInstance(document.getElementById('exerciseModalEdit'));
                editModal.hide();
            } else {
                alert('Failed to update the exercise: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle error case (show error message in modal or alert)
            document.getElementById('successMessage').innerText = 'An error occurred while updating the exercise.';
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show(); // Show the modal with error message
        });
    };
     
        function openEditModal(exerciseId, exerciseName, exerciseDescription, muscleCategory, exerciseImage) {
    // Populate modal fields with existing data
    document.getElementById('exerciseNameEdit').value = exerciseName;
    document.getElementById('exerciseDescriptionEdit').value = exerciseDescription;
    document.getElementById('muscleCategoryEdit').value = muscleCategory;

    // Set the hidden input with exercise_id
    document.getElementById('exerciseIdEdit').value = exerciseId;

    // Set the image preview (if available)
    if (exerciseImage) {
        const imagePreview = document.getElementById('imagePreviewEdit');
        imagePreview.src = 'data:image/jpeg;base64,' + exerciseImage;
        imagePreview.style.display = 'block'; // Show the image preview
    } else {
        document.getElementById('imagePreviewEdit').style.display = 'none';
    }

    // Show the edit modal
    const exerciseModalEdit = new bootstrap.Modal(document.getElementById('exerciseModalEdit'));
    exerciseModalEdit.show();
}

    function openViewModal(id, name, description, category, image) {
        // Populate your modal with exercise details here
        document.getElementById('viewModalTitle').innerText = name;
        document.getElementById('viewModalCategory').innerText = category;
        document.getElementById('viewModalDescription').innerText = description;
        document.getElementById('viewModalImage').src = 'data:image/jpeg;base64,' + image;

        // Show the modal (assuming you're using Bootstrap modals)
        $('#viewModal').modal('show');
    }


    // JavaScript to handle delete modal
    document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var exerciseId = button.getAttribute('data-id');
        var redirectUrl = button.getAttribute('data-url');
        
        var confirmDelete = deleteModal.querySelector('#confirmDelete');
        confirmDelete.href = 'delete-exercise.php?id=' + exerciseId + '&redirect_url=' + redirectUrl;
    });
});

        
    </script>
</body>
</html>
