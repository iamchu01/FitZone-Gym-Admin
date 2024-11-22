<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>

<head>
<?php require_once('vincludes/load.php'); ?>
    <title>Discount List - GYYMS Admin</title>
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
                            <h3 class="page-title">Discount List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Discount List</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <!-- Button to trigger the add discount modal -->
                            <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_discount_modal"><i class="fa fa-plus"></i> Add Discount</button>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Discount List Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Discount Name</th>
                                        <th>Percentage</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query to retrieve discounts from the database
                                    $sql = "SELECT * FROM discount";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Loop through each discount and display it
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['discount_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['discount_percentage']) . "%</td>";
                                            echo '<td class="text-end">
                                                        <a href="#" class="btn btn-sm btn-info edit-btn la la-edit" data-id="' . $row['discount_id'] . '" data-bs-toggle="modal" data-bs-target="#edit_discount_modal"></a>
                                                        <a href="#" class="btn btn-sm btn-danger delete-btn la la-trash" data-id="' . $row['discount_id'] . '" data-bs-toggle="modal" data-bs-target="#delete_discount_modal"></a>
                                                    </td>';
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No discounts found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /Discount List Table -->

                <div class="modal custom-modal fade" id="add_discount_modal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Discount</h5>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="add_discount.php">
                                    <div class="form-group">
                                        <label for="discount-name">Discount Name</label>
                                        <input type="text" class="form-control" id="discount-name" name="discount_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount-percentage">Percentage</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="discount-percentage" name="discount_percentage" required oninput="updatePercentage()">
                                            <span class="input-group-text" id="percentage-display">%</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_discount">Add Discount</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                        <!-- Success Modal -->
                        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Discount has been updated successfully!
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                <!-- Edit Discount Modal -->
                <div class="modal custom-modal fade" id="edit_discount_modal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Discount</h5>
                                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="update_discount.php">
                                    <input type="hidden" id="edit-discount-id" name="discount_id">
                                    <div class="form-group">
                                        <label for="edit-discount-name">Discount Name</label>
                                        <input type="text" class="form-control" id="edit-discount-name" name="discount_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-discount-percentage">Percentage</label>
                                        <input type="number" class="form-control" id="edit-discount-percentage" name="discount_percentage" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="edit_discount">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Edit Discount Modal -->

                <!-- Delete Discount Modal -->
                <div class="modal custom-modal fade" id="delete_discount_modal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete Discount</h3>
                                    <p>Are you sure you want to delete this discount?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <form method="POST" action="delete_discount.php">
                                                <input type="hidden" id="delete-discount-id" name="discount_id">
                                                <button type="submit" class="btn btn-primary continue-btn" name="delete_discount">Delete</button>
                                            </form>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Delete Discount Modal -->

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper -->

    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>

    <script>
        function updatePercentage() {
        const percentageInput = document.getElementById('discount-percentage').value;
        document.getElementById('percentage-display').textContent = percentageInput ? percentageInput + '%' : '%';
    }
     
// Edit Discount Button Handling
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const discountId = this.getAttribute('data-id');
        document.getElementById('edit-discount-id').value = discountId;
        fetchDiscountDetails(discountId);  // Fetch details to populate the modal
    });
});

// Function to fetch discount details and populate the edit modal
function fetchDiscountDetails(discountId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_discount.php?discount_id=' + discountId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var discount = JSON.parse(xhr.responseText);

            // Populate the edit modal fields with the fetched discount data
            document.getElementById('edit-discount-name').value = discount.discount_name;
            document.getElementById('edit-discount-percentage').value = discount.discount_percentage;
        }
    };
    xhr.send();
}

// Function to trigger the success modal if the update was successful
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('delete') && urlParams.get('delete') === 'success') {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
        successModal.show(); 
        urlParams.delete('delete');
        window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());
    }
});
// Delete Discount Button Handling
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const discountId = this.getAttribute('data-id');
        document.getElementById('delete-discount-id').value = discountId;
    });
});
</script>
    
</body>
</html>
