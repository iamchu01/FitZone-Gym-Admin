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

$products = join_product_table1(); // Fetching product data
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Handle POST request for stock out
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $missingFields = [];

    foreach (['product_id', 'stockOutQuantity', 'reason'] as $field) {
        if (empty($_POST[$field])) $missingFields[] = ucfirst(str_replace('_', ' ', $field));
    }

    if (!empty($missingFields)) {
        $session->msg('d', 'Missing fields: ' . implode(', ', $missingFields));
        redirect('stock-out.php', false);
        exit;
    }

    $product_id = $db->escape($_POST['product_id']);
    $quantity_out = (int)$_POST['stockOutQuantity'];
    $reason = $db->escape($_POST['reason']);

    $sql = "SELECT id, name, item_code, description FROM products WHERE id = ? LIMIT 1";
    $stmt = $db->con->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();

        // Fetch total available quantity from batches for the product
        $batch_sql = "SELECT id, batch_quantity, expiration_date FROM batches WHERE product_id = ? AND batch_quantity > 0 ORDER BY id ASC";
        $batch_stmt = $db->con->prepare($batch_sql);
        $batch_stmt->bind_param("i", $product_id);
        $batch_stmt->execute();
        $batch_result = $batch_stmt->get_result();

        // Sum up all batch quantities
        $available_quantity = 0;
        $batches_to_deduct = [];
        while ($batch = $batch_result->fetch_assoc()) {
            $available_quantity += $batch['batch_quantity'];
            $batches_to_deduct[] = $batch;
        }

        // Check if the available quantity is sufficient for the stock out request
        if ($available_quantity >= $quantity_out) {
            $db->con->begin_transaction();
            try {
                // Deduct from batches
                $quantity_to_deduct = $quantity_out;
                foreach ($batches_to_deduct as $batch) {
                    if ($quantity_to_deduct <= 0) break; // No more quantity to deduct

                    // Deduct from this batch
                    $deduct_quantity = min($batch['batch_quantity'], $quantity_to_deduct);
                    $updated_quantity = $batch['batch_quantity'] - $deduct_quantity;

                    // Update batch quantity
                    $update_batch_sql = "UPDATE batches SET batch_quantity = ? WHERE id = ?";
                    $update_batch_stmt = $db->con->prepare($update_batch_sql);
                    $update_batch_stmt->bind_param("ii", $updated_quantity, $batch['id']);
                    $update_batch_stmt->execute();

                    // Deduct the quantity from the remaining amount
                    $quantity_to_deduct -= $deduct_quantity;
                }

                // After deducting from batches, update product quantity
                // $new_product_quantity = $available_quantity - $quantity_out;
                // $update_product_sql = "UPDATE products SET quantity = ? WHERE id = ?";
                // $update_product_stmt = $db->con->prepare($update_product_sql);
                // $update_product_stmt->bind_param("ii", $new_product_quantity, $product_id);
                // $update_product_stmt->execute();

                // Insert record into stock_out table
                $stock_out_sql = "INSERT INTO stock_out (product_name, quantity, date, item_code, reason, description) 
                                  VALUES (?, ?, NOW(), ?, ?, ?)";
                $stock_out_stmt = $db->con->prepare($stock_out_sql);
                $stock_out_stmt->bind_param("sisss", $product['name'], $quantity_out, $product['item_code'], $reason, $product['description']);
                $stock_out_stmt->execute();

                // Commit the transaction
                $db->con->commit();

                $session->msg('s', "Stock out successful for product: {$product['name']}");
            } catch (Exception $e) {
                $db->con->rollback();
                $session->msg('d', 'Failed to process stock out: ' . $e->getMessage());
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
                                        <th class="text-center" style="width: 10%;">Name</th>
                                        <th class="text-center" style="width: 10%;">Category</th>
                                        <th class="text-center" style="width: 10%;">Unit of measure</th>
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
                                                <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                            <?php endif; ?>
                                        </td>
                                           
                                            <td class="text-center"><?php echo remove_junk($product['name']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['uom_name']); ?></td>
                                            <td><?php echo remove_junk($product['description']); ?></td>
                                            <td><?php echo remove_junk($product['item_code']); ?></td>
                                            <td class="text-center"><?php echo (int)$product['quantity']; ?></td>
                                            <td><?php echo remove_junk($product['buy_price']); ?></td>
                                            <td><?php echo remove_junk($product['sale_price']); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                if (!empty($product['expiration_date']) && $product['expiration_date'] !== '0000-00-00') {
                                                    echo remove_junk($product['expiration_date']);
                                                } else {
                                                    echo 'Non-Perishable';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk(date('F j, Y h:i A', strtotime($product['product_batch']))); ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-primary"></i> Actions
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="#" class="stock-out" 
                                                            data-product-id="<?php echo (int)$product['id']; ?>" 
                                                            data-product-name="<?php echo remove_junk($product['name']); ?>"                                           
                                                            data-product-description="<?php echo remove_junk($product['description']); ?>"  
                                                            data-product-quantity="<?php echo remove_junk($product['quantity']); ?>" 
                                                            data-product-batch="<?php echo remove_junk($product['date']); ?>"
                                                            data-product-item-code="<?php echo remove_junk($product['item_code']); ?>">
                                                            <i class="fa fa-minus-circle text-warning"></i> Stock Out
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="delete-product" 
                                                            data-product-id="<?php echo (int)$product['id']; ?>" 
                                                            data-product-name="<?php echo remove_junk($product['name']); ?>">
                                                            <i class="fa fa-trash text-danger"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
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
                <input type="hidden" name="product_id" id="product_id">
                <input type="hidden" class="form-control" name="product_batch" id="product_batch">
                    <div class="form-group">
                        <label for="item_code">Item Code</label>
                        <input type="text" class="form-control" name="item_code" id="item_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" readonly>
                    </div>
                    <!-- <div class="form-group">
                        <label for="uom_name">Unit of measure</label>
                        <input type="text" class="form-control" name="uom_name" id="uom_name" readonly>
                    </div> -->
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
    // Get the data attributes from the clicked button
    var productName = $(this).data('product-name');
    var productQuantity = $(this).data('product-quantity');
    var itemCode = $(this).data('product-item-code');
    var productDescription = $(this).data('product-description');
    var productId = $(this).data('product-id'); // Get the product ID
    var productBatch = $(this).data('product-batch'); // Get product batch information

    // Fill the modal fields with the data
    $('#product_name').val(productName); 
    $('#product_quantity').val(productQuantity); 
    $('#item_code').val(itemCode); 
    $('#product_description').val(productDescription);
    $('#product_id').val(productId); // Set product ID in the hidden field
    $('#product_batch').val(productBatch); // Set the batch info if needed

    // Optional: You could add a check to display a warning if the quantity is very low, or any other logic.
    if (productQuantity <= 0) {
        $('#error-message').text('This product is out of stock!').show();
    } else {
        $('#error-message').hide();
    }

    // Show the modal
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
