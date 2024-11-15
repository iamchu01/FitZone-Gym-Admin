<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <script>
     function editProductModal(id, categoryName, description, itemCode, buyPrice, salePrice, quantity, expirationDate, isPerishable) {
    document.getElementById('edit_cat_id').value = id;  // Set the hidden product ID field
    
    // Display category name as product name
    document.getElementById('product-name').value = categoryName;
    document.getElementById('product-description').value = description;
    document.getElementById('product-item-code').value = itemCode;
    document.getElementById('product-buying-price').value = buyPrice;
    document.getElementById('product-selling-price').value = salePrice;
    document.getElementById('product-quantity').value = quantity;
    document.getElementById('product-expiration-date').value = expirationDate;

    // Handle perishable checkbox
    document.getElementById('perishable-select').checked = isPerishable == 1;
    $('#perishable-select').trigger('change');
}


$(document).ready(function() {
    // Function to validate selling price against buying price
    function validatePrices() {
        var buyingPrice = parseFloat($('#product-buying-price').val());
        var sellingPrice = parseFloat($('#product-selling-price').val());

        if (!isNaN(sellingPrice) && !isNaN(buyingPrice) && sellingPrice < buyingPrice) {
            $('#product-selling-price').addClass('is-invalid');
            $('.selling-price-feedback').text('Selling price must be greater than or equal to buying price.').show();
            $('button[type="submit"]').prop('disabled', true);
        } else {
            $('#product-selling-price').removeClass('is-invalid');
            $('.selling-price-feedback').text('').hide();
            $('button[type="submit"]').prop('disabled', false);
        }
    }

    // Trigger validation on input for buying and selling price fields
    $('#product-buying-price, #product-selling-price').on('input', function() {
        validatePrices();
    });

    // Show or hide expiration date based on perishable checkbox status
    $('#is-perishable').change(function() {
        if ($(this).is(':checked')) {
            $('#expiration-date-group').show();
        } else {
            $('#expiration-date-group').hide();
        }
    });

    // Validate before form submission for expiration date if perishable
    $('form').on('submit', function(e) {
        if ($('#is-perishable').is(':checked') && !$('input[name="expiration-date"]').val()) {
            e.preventDefault();
            $('.expiration-date-feedback').text('Expiration date is required for perishable items.').show();
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
    
// Check item code uniqueness (existing code)
$('input[name="item-code"]').on('input', function() {
        var itemCode = $(this).val();
        // AJAX request to check item code...
    });

    // Check selling price against buying price
   
});
// Example function to open the modal with pre-filled data

  

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
// Function to validate selling price

if(isset($_POST['add_product'])){
    $req_fields = array('product-title','product-categorie','buying-price', 'saleing-price', 'item-description', 'item-code' );
    validate_fields($req_fields);
  
    if(empty($errors)){
      $p_name  = remove_junk($db->escape($_POST['product-title']));
      $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
      $p_buy   = remove_junk($db->escape($_POST['buying-price']));
      $p_item_code   = remove_junk($db->escape($_POST['item-code']));
      $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
      $p_description  = remove_junk($db->escape($_POST['item-description']));
      $is_perishable = isset($_POST['is-perishable']) ? 1 : 0;
  
      $expiration_date = $is_perishable ? remove_junk($db->escape($_POST['expiration-date'])) : NULL;
  
      if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
        $media_id = '0';
      } else {
        $media_id = remove_junk($db->escape($_POST['product-photo']));
      }
  
      $date = make_date(); // Assuming this generates the current date
  
      // Insert query without ON DUPLICATE KEY UPDATE
      $query  = "INSERT INTO products (";
      $query .= "name, buy_price, sale_price, categorie_id, media_id, date, is_perishable, expiration_date, description, item_code";
      $query .= ") VALUES (";
      $query .= " '{$p_name}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', '{$is_perishable}', '{$expiration_date}', '{$p_description}', '{$p_item_code}'";
      $query .= ")";
  
      if($db->query($query)){
        $session->msg('s', "Product added ");
        redirect('add_product.php', false);
      } else {
        $session->msg('d', ' Sorry failed to add!');
        redirect('product.php', false);
      }
    } else{
      $session->msg("d", $errors);
      redirect('add_product.php', false);
    }
}
function validatePrices($buyPrice, $sellPrice) {
    if ($sellPrice < $buyPrice) {
        return false; // Selling price must be greater than or equal to buying price
    }
    return true;
}

// Update Product
if (isset($_POST['update_product'])) {
    $edit_cat_id = (int)$_POST['edit_cat_id'];
    $product_description = $_POST['product-description'];
    $product_item_code = $_POST['product-item-code'];
    $product_buying_price = (float)$_POST['product-buying-price'];
    $product_selling_price = (float)$_POST['product-selling-price'];
    $product_expiration_date = isset($_POST['product-expiration-date']) ? $_POST['product-expiration-date'] : NULL;
    $product_is_perishable = isset($_POST['perishable-select']) ? 1 : 0;
    $product_photo = $_POST['product-photo']; // Assuming this is the photo URL or path

    // Validate prices
    if (!validatePrices($product_buying_price, $product_selling_price)) {
        $session->msg('d', "Selling price must be greater than or equal to buying price.");
        redirect('inventory.php', false); // Redirect to the inventory page without changing the URL
    }

    // Update product in the database, excluding the name field
    $query = "UPDATE products SET 
                description = '{$product_description}',
                item_code = '{$product_item_code}',
                buy_price = '{$product_buying_price}',
                sale_price = '{$product_selling_price}',
                expiration_date = '{$product_expiration_date}',
                is_perishable = '{$product_is_perishable}',
                media_id = '{$product_photo}'
                WHERE id = '{$edit_cat_id}'";

    if ($db->query($query)) {
        $session->msg('s', "Product updated successfully.");
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'product.php';
                }, 100);
              </script>";
       // redirect('product.php', false); // Redirect to the product page without changing the URL
    } else {
        $session->msg('d', "Sorry, failed to update product!");
        redirect('product.php', false); // Redirect to the product page without changing the URL
    }
}

?>

<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper">
  <div class="content container-fluid">
  <div class="row">

     <div class="row">

                  
                        <div class="col">
                            <h3 class="page-title">Product Inventory</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin.php">Inventory Management</a></li>
                                <li class="breadcrumb-item active">Inventory Product</li>
                            </ul>
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
        <div class="col color-legend" style="margin-bottom: 15px;">
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #ffc107; margin-right: 5px; border-radius: 3px;"></div>
            <span>In Stock: Low (below 10)</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div style="width: 20px; height: 20px; background-color: #dc3545; margin-right: 5px; border-radius: 3px;"></div>
            <span>Out of Stock</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div class="bg-secondary" style="width: 20px; height: 20px; margin-right: 5px; border-radius: 3px;"></div>
            <span>soon to expire items span 1month 31 days</span>
        </div>
        </div>
    </div>
        </div>
        <div class="panel-body">
    <div class="table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">#</th>
                    <th class="text-center" style="width: 30px;">Product-ID</th>
                    <th class="text-center" style="width: 10%;">Photo</th>
                    <th class="text-center" style="width: 10%;">Categorie</th>
                    <th class="text-center" style="width: 50%;">Product Name</th>
                    
                    <th class="text-center" style="width: 10%;">Description</th>
                    <th class="text-center" style="width: 10%;">Item Code</th>
                    <th class="text-center" style="width: 10%;">Quantity</th>
                    <th class="text-center" style="width: 10%;">Buying Price</th>
                    <th class="text-center" style="width: 10%;">Selling Price</th>
                    <th class="text-center" style="width: 10%;">Expire Date</th>
                    <th class="text-center" style="width: 10%;">Product Batch</th>
                 
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
                $rowClass = 'bg-secondary'; // Override with secondary color if about to expire
            }
        ?>
        <tr class="<?php echo $rowClass; ?>">
            <td class="text-center"><?php echo count_id(); ?></td>
            <td class="text-center"><?php echo remove_junk($product['id']); ?></td>
            <td>
                <?php if ($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
            </td>
            <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['name']); ?></td>           
            <td class="text-center"><?php echo remove_junk($product['description']); ?></td>
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
</div>
<!-- add product modal -->
<div class="modal " id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addProductModalLabel"></h5>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <div class="modal-body" style="margin: 1px;">
                                        <div class="row no-gutters">
                                            <div class="col-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading bg-success">
                                                        <strong>
                                                            <span class="fa fa-th-large"></span>
                                                            <span>Add Product</span>
                                                        </strong>
                                                    </div>
                                                    <div class="panel-body">
                                                    <form method="post" action="product.php" class="clearfix">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-th-large"></i></span>
                                                            <input type="text" class="form-control" name="product-title" placeholder="Product Name" required>
                                                        </div>
                                                    </div>
                                        <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="product-categorie" id="product-category" required>
                                                    <option value="">Select categorie</option>
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
                                    <div class="form-group">
                                        <div class="row">
                                            <!-- <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                                                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" disabled>
                                                </div>
                                            </div> -->
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
                                     
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="item-description" placeholder="Item description" required>
                                        </div>
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

                                    <button type="submit" name="add_product" class="btn btn-primary pull-right " style="margin-right: 2%;">Add product</button>
                                </form>
                            </div>
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
                            <div class="panel-heading">
                                <strong>
                                    <span class="fa fa-th-large"></span>
                                    <span>Edit Product</span>
                                </strong>
                            </div>
                            <div class="panel-body">
                            <form method="post" action="product.php" class="clearfix">
    <input type="hidden" id="edit_cat_id" name="edit_cat_id" value="">

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
            
            <input type="text" class="form-control" id="product-name" name="product-name" readonly>
                    
            </div>
            <div class="col-md-6">
                <select class="form-control" name="product-photo">
                    <option value="">Select Product Photo</option>
                    <?php foreach ($all_photo as $photo): ?>
                        <option value="<?php echo $photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Other fields -->
    <div class="form-group">
        <div class="row">
        
            <div class="col-md-4">
            <label for="product-quantity">Quantity</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                    <input type="number" class="form-control" name="product-quantity" id="product-quantity" disabled>
                </div>
            </div>
           
            <div class="col-md-4">
            <label for="product-buying-price">Buying price</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="number" class="form-control" name="product-buying-price" id="product-buying-price" required>
                    <span class="input-group-addon">.00</span>
                </div>
            </div>
            <div class="col-md-4">
            <label for="product-selling-price">Selling price</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="number" class="form-control" name="product-selling-price" id="product-selling-price" required>
                    <span class="input-group-addon">.00</span>
                   
                </div>
                <div class="invalid-feedback selling-price-feedback" style="display:none;"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
    <label for="product-item-code">Item code</label>
                                        <div class="input-group">
                                           
                                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                            <input type="text" class="form-control" name="product-item-code" id="product-item-code"  >
                                        </div>
                                        
                                    </di>
                                    <div class="form-group">
                                    <label for="product-description">Item description</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="product-description" id="product-description" required>
                                        </div>
                                    </div>
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

   

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary" name="update_product">Save Changes</button>
    </div>
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
