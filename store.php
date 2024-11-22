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

            // Button to show the 'Add Product' modal with an icon
            $('.btn-success').on('click', function() {
                // Show the product table when the 'Add New Product' button is clicked
                $('#productTable').removeClass('d-none');
                // Optionally, you can also show the modal if it's part of the functionality
                $('#addProductModal').modal('show');
            });

            // Button to show pending products in a modal
            $('.btn-pending-products').on('click', function() {
                $('#pendingProductsModal .modal-body').html(''); // Clear the modal content

                // Fetch pending products via AJAX
                $.ajax({
                    url: 'fetch_pending_products.php', // Fetch pending products from a separate PHP file
                    method: 'GET',
                    success: function(response) {
                        $('#pendingProductsModal .modal-body').html(response); // Insert the products in the modal
                        $('#pendingProductsModal').modal('show'); // Show the modal
                    }
                });
            });

            // Button to select products for the online store
            $('.btn-select-category').on('click', function() {
                var categoryId = $(this).data('category-id'); // Get the category ID
                $('#selectItemsModal .modal-body').html(''); // Clear the modal content

                // Fetch products by category via AJAX
                $.ajax({
                    url: 'fetch_products.php', // Fetch products from a separate PHP file
                    method: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        $('#selectItemsModal .modal-body').html(response); // Insert the products in the modal
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

        /* Ensures table is hidden initially */
        #productTable {
            display: none;
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
     <style>
        .panel-box:hover{
            transition: transform 0.3s ease;
            transform: scale(1.05);
            background-color: #bff7d3;
        }
    </style>
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

                <div class="row">
        <div class="col-md-3">
                    <a href="#" style="color:black;">
                        <div class="panel panel-box clearfix">
                            <div class="panel-icon pull-left bg-success">
                            <i class="fa fa-list-ol"></i>                            
                            </div>
                            <div class="panel-value pull-right">
                                <h2 class="margin-top"></h2>
                                <p class="text-title">Add Product</p>
                            </div>
                        </div>
                    </a>
                </div>
        
            <!-- Category Panel -->
            <div class="col-md-3">
                <a href="#" style="color:black;">
                    <div class="panel panel-box clearfix">
                        <div class="panel-icon pull-left bg-red">
                            <i class="fa fa-eye"></i>
                        </div>
                        <div class="panel-value pull-right">
                            <h2 class="margin-top"></h2>
                            <p class="text-title">View in store Product</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Product Panel -->
            <div class="col-md-3">
                <a href="#" style="color:black;">
                    <div class="panel panel-box clearfix">
                        <div class="panel-icon pull-left bg-blue2">
                            <i class="fa fa-eye"></i>
                        </div>
                        <div class="panel-value pull-right">
                            <h2 class="margin-top"></h2>
                            <p class="text-title">View Track Order </p>
                        </div>
                    </div>
                </a>
            </div>
            </div>
                

                <!-- Table to show all products (initially hidden) -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped" id="productTable1">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch products to display in the online store
                            $products = join_product_table();
                            foreach ($products as $product):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['categorie']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo '₱' . number_format($product['sale_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-select-category" data-category-id="<?php echo $product['batch_id']; ?>">Select</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div> <!-- End Page Content -->

        </div> <!-- End Page Wrapper -->

        <!-- Modal to Display Pending Products -->
        <div class="modal" id="pendingProductsModal" tabindex="-1" role="dialog" aria-labelledby="pendingProductsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pendingProductsModalLabel">Pending Products</h5>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Pending products will be dynamically loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal to Display Products Based on Category -->
        <div class="modal" id="selectItemsModal" tabindex="-1" role="dialog" aria-labelledby="selectItemsModalLabel" aria-hidden="true">
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
