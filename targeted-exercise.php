<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <style>
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

        .table {
            margin-top: 20px;
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
                            <h3 class="page-title">Welcome Admin!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active">Targeted exercise</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Muscle Groups Section -->
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Muscle Groups</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Muscle Group</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $muscle_groups = [
                                        "Chest" => "chest-exercises.php",
                                        "Shoulders" => "shoulder-exercises.php",
                                        "Triceps" => "triceps-exercises.php",
                                        "Biceps" => "biceps-exercises.php",
                                        "Back" => "back-exercises.php",
                                        "Core" => "abs-exercises.php",
                                        "Legs" => "glutes-exercises.php",
                                       
                                    ];

                                    foreach ($muscle_groups as $muscle => $file) {
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($muscle) . '</td>
                                            <td>
                                                <a href="' . htmlspecialchars($file) . '" class="btn btn-outline-success btn-sm">View Exercises</a>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /Muscle Groups Section -->
            </div>
            <!-- /Page Content -->
        </div>
        <!-- /Page Wrapper -->
    </div>
    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>
</body>

</html>
