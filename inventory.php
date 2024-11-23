<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Targeted Exercise - HRMS Admin Template</title>
    <?php include 'layouts/head-main.php'; ?>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>

    <?php require_once('vincludes/load.php'); ?>
    <?php
    $selected_category = isset($_GET['category']) ? $_GET['category'] : null;
    $exercises = [];

    if ($selected_category) {
        $exercises = find_by_column('muscle_exercise', 'muscle_category', $selected_category);
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
                        <?php if ($selected_category && !empty($exercises)) : ?>
                        <h4>Exercises for <?php echo htmlspecialchars(ucfirst($selected_category)); ?></h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Media</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($exercises as $exercise) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($exercise['me_name']); ?></td>
                                        <td><?php echo htmlspecialchars($exercise['me_description']); ?></td>
                                        <td>
                                            <?php if ($exercise['me_media_type'] === 'video/mp4') : ?>
                                                <video width="150" controls>
                                                    <source src="path/to/videos/<?php echo htmlspecialchars($exercise['me_video']); ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            <?php else : ?>
                                                <p>No media available</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php elseif ($selected_category) : ?>
                        <p>No exercises found for this category.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /Panel -->

            </div>
            <!-- /Page Content -->
        </div>
        <!-- /Page Wrapper -->
    </div>

    <?php include_once('vlayouts/footer.php'); ?>
    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>
</body>
</html>
