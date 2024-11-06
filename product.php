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
  
function setEditProduct(productId, productName, categoryId, itemCode, buyPrice, salePrice, quantity, expirationDate, isPerishable) {
    // Set the values in the modal
    $('#edit_product_id').val(productId);  // Set product ID in the hidden input
    $('#edit-product-category').val(categoryId);  // Set the category
    $('#edit-item-code').val(itemCode);  // Set item code
    $('#edit-buying-price').val(buyPrice);  // Set buying price
    $('#edit-saleing-price').val(salePrice);  // Set selling price
    $('#edit-product-quantity').val(quantity);  // Set quantity
    
    // Set expiration date and handle perishable check
    $('#edit-expiration-date').val(expirationDate);  // Set expiration date
    $('#edit-is-perishable').prop('checked', isPerishable);  // Set perishable status
    
    // Disable expiration date if not perishable
    if (isPerishable) {
        $('#edit-expiration-date').prop('disabled', false);
    } else {
        $('#edit-expiration-date').prop('disabled', true);
    }
}


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
                            <a href="#" data-toggle="modal" data-target="#editProductModal" aria-expanded="false"
                                 onclick="setEditProduct(<?php echo $product['id']; ?>, '<?php echo addslashes($cat['name']); ?>, '<?php echo addslashes($product['item_code']); ?>', <?php echo $product['buy_price']; ?>, <?php echo $product['sale_price']; ?>, '<?php echo $product['quantity']; ?>, '<?php echo $product['expiration_date']; ?>', <?php echo $product['is_perishable']; ?>)">
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
<div class="modal " id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal"></h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body" style="margin: 1px;">
    <div class="row no-gutters">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>
                        <span class="fa fa-th-large"></span>
                        <span>Stock in Product</span>
                    </strong>
                </div>
                <div class="panel-body">
                <form method="post" action="stock-in.php" class="clearfix">
                                    <!-- Hidden input for category ID -->
                                    <input type="hidden" id="edit_cat_id" name="edit_cat_id" value="">

                    <div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <select class="form-control" name="product-categorie" id="product-category" required>
                <option value=""></option>
                <?php foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <select class="form-control" name="product-photo">
                <option value="">Select Product Photo</option>
                <?php foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                                                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" name="buying-price" placeholder="Buying Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                                <div class="invalid-feedback selling-price-feedback" style="display:none;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                            <input type="text" class="form-control" name="item-code" placeholder="Item Code" required>
                                        </div>
                                        <div class="invalid-feedback item-code-feedback" style="display:none;"></div>
                                    </div>

                                    <!-- Checkbox for Perishable -->
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is-perishable" name="is_perishable" value="1">
                                            <label class="form-check-label" for="is-perishable">Is Perishable</label>
                                        </div>
                                    </div>

                                    <div class="form-group" id="expiration-date-group" style="display: none;">
                                        <label for="expiration-date">Expiration Date</label>
                                        <input type="date" class="form-control" name="expiration-date" id="expiration-date" min="<?php echo $min_expiration_date; ?>">
                                        <div class="expiration-date-feedback text-danger" style="display: none;"></div> <!-- Feedback message container -->
                                    </div>

                                    <button type="submit" name="" class="btn btn-primary">Update</button>
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
