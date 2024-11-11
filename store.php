<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - HRMS Admin Template</title>

    <?php include 'layouts/head-main.php'; ?>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/session.php'; ?>
    
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#category-search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('tbody tr').filter(function() {
                    $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.btn-select-category').on('click', function() {
                var categoryId = $(this).data('category-id'); // Get the category ID
                $('#selectItemsModal .modal-body').html(''); // Clear the modal content

                // Fetch products by category via AJAX
                $.ajax({
                    url: 'fetch_products.php', // Fetch products from a separate PHP file
                    method: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        // Insert the products in the modal body
                        $('#selectItemsModal .modal-body').html(response);
                        $('#selectItemsModal').modal('show'); // Show the modal
                    }
                });
            });
        });
    </script>
    
    <style>
    .main-wrapper .modal-dialog {
        max-width: 80%;
        width: 100%; /* Ensures the modal takes the full width up to max-width */
    }
</style>


    <?php
    // Fetch all categories and their respective product quantities
    $all_categories = find_all('categories');
    $category_stock = [];

    foreach ($all_categories as $cat) {
        $query = "SELECT SUM(quantity) AS total_quantity FROM products WHERE categorie_id = '{$cat['id']}'";
        $result = $db->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            $category_stock[$cat['id']] = $row['total_quantity'] ? $row['total_quantity'] : 0;
        } else {
            $category_stock[$cat['id']] = 0; // Default to 0 if no products found
        }
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
                            <h3 class="page-title">E-store Management</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin.php">Inventory</a></li>
                                <li class="breadcrumb-item active">E-store</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Search and Products Table -->
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <div class="col">Search Product</div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="category-search" class="form-control" placeholder="Type Product name...">
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>Products List</th>
                                            <th class="text-center" style="width: 100px;">In-Stock</th>
                                            <th class="text-center" style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_categories as $cat): ?>
                                            <tr>
                                                <!-- Product ID -->
                                                <td class="text-center"><?php echo count_id(); ?></td>

                                                <!-- Product Name -->
                                                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>

                                                <!-- Display In-Stock quantity -->
                                                <td class="text-center"><?php echo $category_stock[$cat['id']]; ?></td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-select-category" data-category-id="<?php echo $cat['id']; ?>">Add to store</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- End Page Content -->

        </div> <!-- End Page Wrapper -->
          <!-- Modal to Display Products Based on Category -->
    <div class="modal " id="selectItemsModal" tabindex="-1" role="dialog" aria-labelledby="selectItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectItemsModalLabel">Select Products</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Products will be dynamically loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

    </div> <!-- End Main Wrapper -->

  
    <?php include_once('vlayouts/footer.php'); ?>
    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>
</body>
</html>
