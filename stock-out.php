<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <style>
  
</style>
</head>

<?php

function is_about_to_expire($expiration_date) {
    $current_date = new DateTime();
    $expiration_date = new DateTime($expiration_date);
    $interval = $current_date->diff($expiration_date);
    
    // Check if the expiration date is within the next month (31 days)
    return ($interval->days <= 31 && $interval->invert == 0);
}

$products = join_product_table(); // Fetching product data
$all_categories = find_all('categories');
$all_photo = find_all('media');



// Include your database connection fil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize missing fields array
    $missingFields = [];

    // Check for required fields
    foreach (['product_id', 'stockOutQuantity', 'reason', 'product_description'] as $field) {
        if (empty($_POST[$field])) $missingFields[] = ucfirst(str_replace('_', ' ', $field));
    }

    if (!empty($missingFields)) {
        $session->msg('d', 'Missing fields: ' . implode(', ', $missingFields));
        redirect('stock-out.php', false);
        exit;
    }

    // Sanitize and retrieve the inputs
    $product_id = $db->escape($_POST['product_id']);
    $quantity_out = (int)$_POST['stockOutQuantity'];
    $reason = $db->escape($_POST['reason']);
    $date_out = date('Y-m-d');
    

    // Check product existence and quantity
    $sql = "SELECT p.id, c.name, p.quantity, p.item_code, p.description FROM products p JOIN categories c ON p.categorie_id = c.id WHERE p.id = ? LIMIT 1";
    $stmt = $db->con->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        
        if ($product['quantity'] >= $quantity_out) {
            // Start a transaction
            $db->con->begin_transaction();
            try {
                // Insert stock-out record
                $sql = "INSERT INTO stock_out (product_name, quantity, date, item_code, reason, description) VALUES (?, ?, NOW(), ?, ?, ?)";
                $stmt = $db->con->prepare($sql);
                $stmt->bind_param("sssss", $product['name'], $quantity_out, $product['item_code'], $reason, $product['description']);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error); // Throw an exception if execution fails
                }

                // Update the quantity in the products table
                $new_quantity = $product['quantity'] - $quantity_out;
                $sql = "UPDATE products SET quantity = ? WHERE id = ?";
                $stmt = $db->con->prepare($sql);
                $stmt->bind_param("is", $new_quantity, $product_id);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error); // Throw an exception if execution fails
                }

                // Commit transaction
                $db->con->commit();
                $session->msg('s', "Stock out successful for product ID: {$product_id}");
            } catch (Exception $e) {
                $db->con->rollback();
                $session->msg('d', 'Failed to register stock out! Error: ' . $e->getMessage());
            }
        } else {
            $session->msg('d', 'Insufficient stock available!');
        }
    } else {
        $session->msg('d', 'Product not found!');
    }

    redirect('stock-out.php', false);
}


?>   

<?php include 'layouts/menu.php'; ?>
<body>
<div class="page-wrapper" style="padding-top: 2%">
    <div class="content container-fluid">
        <div class="row">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Stock out</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
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
                        <div class="color-legend" style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: #ffc107; margin-right: 5px; border-radius: 3px;"></div>
                                <span>In Stock: Low (below 10)</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background-color: #dc3545; margin-right: 5px; border-radius: 3px;"></div>
                                <span>Out of Stock/soon to expire items span 1 month 31 days</span>
                            </div>
                        </div>
                        <div class="pull-right">
                            <a href="stock-in.php" class="btn btn-primary">Stock in</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 30px;">#</th>
                                        <th class="text-center" style="width: 10%;">Photo</th>
                                        <th class="text-center" style="width: 50%;">Name</th>
                                        <th class="text-center" style="width: 10%;">Item description</th>
                                        <th class="text-center" style="width: 10%;">Item Code</th>
                                        <th class="text-center" style="width: 10%;">In-Stock</th>
                                        <th class="text-center" style="width: 10%;">Buying Price</th>
                                        <th class="text-center" style="width: 10%;">Selling Price</th>
                                        <th class="text-center" style="width: 10%;">Expire Date</th>
                                        <th class="text-center" style="width: 10%;">Product Batch</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <?php 
                                            // Determine the row class based on quantity and expiration
                                            $rowClass = '';
                                            if ($product['quantity'] == 0) {
                                                $rowClass = 'bg-danger';
                                            } elseif ($product['quantity'] < 10) {
                                                $rowClass = 'bg-warning';
                                            }
                                            // Check if the product is about to expire within one month
                                            if (isset($product['expiration_date']) && is_about_to_expire($product['expiration_date'])) {
                                                $rowClass = 'bg-danger'; // Override with secondary color if about to expire
                                            }
                                        ?>
                                        <tr class="<?php echo $rowClass; ?>">
                                            <td class="text-center"><?php echo count_id(); ?></td>
                                            <td>
                                                <?php if ($product['media_id'] === '0'): ?>
                                                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                                                <?php else: ?>
                                                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['media_id']; ?>" alt="">
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk($product['name']); ?></td>
                                            <td><?php echo remove_junk($product['description']); ?></td>
                                            <td><?php echo remove_junk($product['item_code']); ?></td>
                                            <td class="text-center"><?php echo (int)$product['quantity']; ?></td>
                                            <td><?php echo remove_junk($product['buy_price']); ?></td>
                                            <td><?php echo remove_junk($product['sale_price']); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                if (isset($product['is_perishable']) && $product['is_perishable'] == 0) {
                                                    echo 'Non-Perishable';
                                                } else {
                                                    echo remove_junk($product['expiration_date']);
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk($product['date']); ?></td>
                                            <td class="text-center">
                                            <button class="btn btn-danger stock-out fa fa-trash" 
    data-product-id="<?php echo (int)$product['id']; ?>" 
    data-product-name="<?php echo remove_junk($product['name']); ?>" 
    data-product-description="<?php echo remove_junk($product['description']); ?>"  
    data-product-quantity="<?php echo remove_junk($product['quantity']); ?>" 
    data-product-batch="<?php echo remove_junk($product['date']); ?>"
    data-product-item-code="<?php echo remove_junk($product['item_code']); ?>"> Stock Out</button>

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
      <!-- Stock Out Modal -->
<div class="modal" id="stockOutModal" tabindex="-1" role="dialog" aria-labelledby="stockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="stockOutForm" method="POST" action="stock-out.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockOutModalLabel">Stock Out Product</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id" value="">
                    <div class="form-group">
                        <label for="item_code">Item Code</label>
                        <input type="text" class="form-control" name="item_code" id="item_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="product_description">Product description</label>
                        <input type="text" class="form-control" name="product_description" id="product_description" readonly>
                    </div>
                    <div class="form-group">
                        <label for="product_quantity">Available Quantity</label>
                        <input type="number" class="form-control" name="product_quantity" id="product_quantity" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="stockOutQuantity">Stock Out Quantity</label>
                        <input type="number" class="form-control" name="stockOutQuantity" id="stockOutQuantity" required>
                        <div id="error-message" style="color: red; display: none;"></div> <!-- Error message container -->
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Stock Out</label>
                        <textarea class="form-control" name="reason" id="reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm Stock Out</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

<script>
    $(document).on('click', '.stock-out', function() {
    var productName = $(this).data('product-name');   
    var productQuantity = $(this).data('product-quantity'); 
    var itemCode = $(this).data('product-item-code');
    var productDescription = $(this).data('product-description')

    $('#product_name').val(productName); 
    $('#product_description').val(productDescription);
    $('#product_id').val($(this).data('product-id')); 
    $('#product_quantity').val(productQuantity); 
    $('#item_code').val(itemCode); // Set the item code in the modal
    $('#stockOutModal').modal('show'); 
});

    $('#stockOutForm').on('submit', function(e) {
        var stockOutQuantity = parseInt($('#stockOutQuantity').val());
        var availableQuantity = parseInt($('#product_quantity').val());

        if (stockOutQuantity <= 0 || stockOutQuantity > availableQuantity) {
            e.preventDefault(); // Prevent form submission
            $('#stockOutQuantity').addClass('invalid-input');
            $('#error-message').text("Please enter a valid quantity. It must be greater than 0 and not exceed the available quantity.").show();
            $('#stockOutQuantity').focus(); // Optionally focus on the invalid field
        }
    });


    // Implementing search functionality
    $('#category-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.datatable tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(3)').text().toLowerCase().indexOf(value) > -1);
        });
    });
</script>
