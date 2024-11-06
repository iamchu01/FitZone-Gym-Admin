<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <script>
$(document).ready(function() {
    // Check selling price against buying price
    $('input[name="saleing-price"], input[name="buying-price"]').on('input', function() {
        var buyingPrice = parseFloat($('input[name="buying-price"]').val());
        var sellingPrice = parseFloat($('input[name="saleing-price"]').val());

        // Check if selling price is less than buying price
        if (!isNaN(sellingPrice) && !isNaN(buyingPrice) && sellingPrice < buyingPrice) {
            // Show validation message
            $('input[name="saleing-price"]').addClass('is-invalid');
            $('.selling-price-feedback').text('Selling price must be greater than or equal to buying price.').show();
            $('button[type="submit"]').prop('disabled', true); // Disable submit button
        } else {
            // Clear validation message
            $('input[name="saleing-price"]').removeClass('is-invalid');
            $('.selling-price-feedback').text('').hide();
            $('button[type="submit"]').prop('disabled', false); // Enable submit button if valid
        }
    });
    $('#is-perishable').change(function() {
        if ($(this).is(':checked')) {
            $('#expiration-date-group').show();
        } else {
            $('#expiration-date-group').hide();
        }
    });
      // Validate before form submission
      $('form').on('submit', function(e) {
        let isPerishable = $('#perishable-select').val() === 'perishable';
        let expirationDate = $('input[name="expiration-date"]').val();

        // Reset feedback messages
        $('.expiration-date-feedback').hide();

        if (isPerishable && !expirationDate) {
            e.preventDefault(); // Prevent form submission
            $('.expiration-date-feedback').text('Expiration date is required for perishable items.').show(); // Show validation message
        }
    });
    $('#category-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(3)').text().toLowerCase().indexOf(value) > -1);
        });
    });
    // Optional: Initialize visibility when the modal is shown
    $('#addProductModal').on('show.bs.modal', function() {
        $('#perishable-select').trigger('change'); // Trigger change to set initial visibility
    });
    
// Check item code uniqueness (existing code)
$('input[name="item-code"]').on('input', function() {
        var itemCode = $(this).val();
        // AJAX request to check item code...
    });

    // Check selling price against buying price
   
});
  


  </script>
  <style>
    .modal-content {
    width: 100%;
    max-width: 100%;
}
    .modal-body {
    max-height: calc(100vh - 200px); /* Adjust based on your header/footer heights */
    overflow-y: auto; /* Enable scrolling if content exceeds height */
}
  </style>
</head> 
<?php
function is_about_to_expire($expiration_date) {
    $current_date = new DateTime();
    $expiration_date = new DateTime($expiration_date);
    $interval = $current_date->diff($expiration_date);
    
    // Check if the expiration date is within the next year (365 days)
    return ($interval->days <= 31 && $interval->invert == 0);
}
$products = join_product_table();
$all_categories = find_all('categories');

$all_photo = find_all('media');
$min_expiration_date = date('Y-m-d', strtotime('+5 months'));

if (isset($_POST['add_product'])) {
    var_dump($_POST); 
    echo isset($_POST['expiration-date']) ? $_POST['expiration-date'] : 'Expiration date not set';

    $item_code = remove_junk($db->escape($_POST['item-code']));
    $expiration_date = $_POST['expiration-date'];
    $is_perishable = isset($_POST['is_perishable']) ? (int)$_POST['is_perishable'] : 0; // Default to non-perishable


    $req_fields = array('product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'item-code');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        $is_perishable = isset($_POST['is_perishable']) && $_POST['is_perishable'] == "1" ? 1 : 0;
        $expiration_date = $is_perishable && !empty($_POST['expiration-date']) ? $db->escape($_POST['expiration-date']) : NULL;
    

        // Initialize expiration date to NULL for non-perishable items
        $expiration_date = NULL;
        if ($is_perishable) {
            if (isset($_POST['expiration-date']) && $_POST['expiration-date'] >= $min_expiration_date) {
                $expiration_date = remove_junk($db->escape($_POST['expiration-date']));
            } else {
                $session->msg("d", "Expiration date is required and must be at least 5 months from today for perishable items.");
                redirect('product.php', false);
                exit; // Stop further execution if expiration date is invalid
            }
        }

        // Handle media ID
        $media_id = !empty($_POST['product-photo']) ? remove_junk($db->escape($_POST['product-photo'])) : '0';

        // Check for unique item code
        $query_check = "SELECT * FROM products WHERE item_code = '{$item_code}' LIMIT 1";
        $result_check = $db->query($query_check);

        if ($result_check->num_rows > 0) {
            $session->msg("d", "Item code must be unique. This code already exists.");
            redirect('product.php', false);
        } else {
            $date = make_date(); // Get the current date

            $query  = "INSERT INTO products (quantity, buy_price, sale_price, categorie_id, media_id, date, expiration_date, is_perishable, item_code) VALUES ";
            $query .= "('{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', ";
            $query .= $expiration_date ? "'{$expiration_date}', " : "NULL, ";
            $query .= "{$is_perishable}, '{$item_code}')";
            
            // Execute the query
            if ($db->query($query)) {
                $session->msg('s', "Product added ");
                redirect('product.php', false);
            } else {
                $session->msg('d', 'Sorry, failed to add!');
                redirect('product.php', false);
            }
        }
    } else {
        $session->msg("d", $errors);
        redirect('product.php', false);
    }
}


?>
<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper">
  <div class="content container-fluid">
  <div class="row">

     <div class="row">
      <div class="col"> <h3 class="page-title">Product Stock List </h3>
    </div>
    <div class="col">
      <div class="dropdown position-absolute top-0 end-0">
  <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" title="Inventory management Navigation bar" data-toggle="tooltip">
    <span class="fa fa-navicon"></span>
  </a>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
  <li><a class="dropdown-item" href="admin.php"><span class="fa fa-home"></span> Inventory Overview</a></li>
    <li><a class="dropdown-item" href="categorie.php"><span class="fa fa-th"></span> Add Product</a></li>
    <li><a class="dropdown-item" href="product.php"><span class="fa fa-th-large"></span> Product Stock List</a></li>
    <li><a class="dropdown-item" href="gym_equipment.php"><span class="fa fa-shopping-cart"></span> Store Products</a></li>
    <li><a class="dropdown-item" href="gym_equipment.php"><span class="fa fa-cubes"></span> Gym equipment</a></li>
  </ul>
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
            <span>Out of Stock/soon to expire items span 1month 31 days</span>
        </div>
        </div>
    <!-- </div>
            <div class="pull-right">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Stock in</a>
            </div> -->
        </div>
        <div class="panel-body">
    <div class="table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">#</th>
                    <th class="text-center" style="width: 10%;">Photo</th>
                    <th class="text-center" style="width: 50%;">Name</th>
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
            // Check if the product is about to expire within one year
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
            <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['item_code']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
            <td class="text-center">
                <?php 
                if (isset($product['is_perishable']) && $product['is_perishable'] == 0) {
                    echo 'Non-Perishable';
                } elseif (isset($product['expiration_date']) && !empty($product['expiration_date'])) {
                    // Display the expiration date
                    echo htmlspecialchars($product['expiration_date']);
                } else {
                    echo 'No expiration date available';
                }
                ?>
            </td>
            <td class="text-center"><?php echo read_date($product['date']); ?></td>
            <td class="text-center">
                <div class="btn-group">
                    <div class="dropdown action-label">
                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-dot-circle-o text-primary"></i> Actions
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" title="Edit" data-toggle="tooltip" data-id="<?php echo (int)$product['id']; ?>">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="dropdown-item" title="Delete" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this product batch?');">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </div>
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
</div>

<!-- Edit Product Modal -->
<div class="modal" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="margin: 1px;">
                <div class="row no-gutters">
                    <div class="col-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form method="post" action="edit_product.php" id="editProductForm" class="clearfix">
                                    <input type="hidden" name="product-id" id="edit-product-id" value="">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-th-large"></i></span>
                                            <input type="text" class="form-control" name="product-title" id="edit-product-title" placeholder="Product Title" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="product-categorie" id="edit-product-categorie" required>
                                                    <option value="">Select Product Category</option>
                                                    <?php foreach ($all_categories as $cat): ?>
                                                        <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="product-photo" id="edit-product-photo">
                                                    <option value="">Select Product Photo</option>
                                                    <?php foreach ($all_photo as $photo): ?>
                                                        <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                                                    <input type="number" class="form-control" name="product-quantity" id="edit-product-quantity" placeholder="Product Quantity" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" name="buying-price" id="edit-buying-price" placeholder="Buying Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" name="saleing-price" id="edit-selling-price" placeholder="Selling Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="edit-is-perishable" name="is-perishable"> Is this product perishable?
                                        </label>
                                    </div>

                                    <div class="form-group" id="edit-expiration-date-group" style="display: none;">
                                        <label for="edit-expiration-date">Expiration Date</label>
                                        <input type="date" class="form-control" name="expiration-date" id="edit-expiration-date" min="<?php echo $min_expiration_date; ?>" required>
                                    </div>

                                    <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
