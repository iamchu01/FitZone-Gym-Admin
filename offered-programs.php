<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>
    <title>Product Category List - GYYMS Admin</title>
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
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Program List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>                   
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#createProgramModal">
                                <i class="fa fa-plus"></i> Create Program
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Create Program Modal -->
                <div class="modal fade" id="createProgramModal" tabindex="-1" aria-labelledby="createProgramModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createProgramModalLabel">Create Program</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="#">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Program Profile</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Program Title</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Select Trainer</label>
                                        <div class="col-md-10">
                                            <select class="form-control" required>
                                                <option value="">Select a trainer</option>
                                                <option value="trainer1">Trainer 1</option>
                                                <option value="trainer2">Trainer 2</option>
                                                <option value="trainer3">Trainer 3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Slots</label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Program Duration (days)</label>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Is the Program Free?</label>
                                        <div class="col-md-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="isFree" onclick="toggleFeeInput()">
                                                <label class="form-check-label" for="isFree">Check if the program is free</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Membership Fee</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="membershipFee" placeholder="Enter amount" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2">Program Description</label>
                                        <div class="col-md-10">
                                            <textarea rows="5" cols="5" class="form-control" placeholder="Enter text here"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 row">
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <button class="btn btn-primary" type="submit">Create Program</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Create Program Modal -->

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper-->

    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>

    <script>
        function toggleFeeInput() {
            const isFreeCheckbox = document.getElementById('isFree');
            const feeInput = document.getElementById('membershipFee');
            feeInput.disabled = isFreeCheckbox.checked;
            if (isFreeCheckbox.checked) {
                feeInput.value = ''; // Clear the input if the program is free
            }
        }
    </script>

</body>
</html>
