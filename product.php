<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <script>
function editProductModal(id, name, categorie, description, itemCode, buyPrice, salePrice, quantity) {
    // Assign values to modal fields
    document.getElementById('edit-product-id').value = id;
    document.getElementById('edit-product-title').value = name;
    document.getElementById('edit-product-category').value = categorie;
    document.getElementById('edit-item-description').value = description;
    document.getElementById('edit-item-code').value = itemCode;
    document.getElementById('edit-buying-price').value = buyPrice;
    document.getElementById('edit-saleing-price').value = salePrice;

    // Optional: Set the quantity (if it's included in the modal)
    if (document.getElementById('edit-product-quantity')) {
        document.getElementById('edit-product-quantity').value = quantity;
    }

    // Open the modal (bootstrap handles this via data-target, so this is optional)
    $('#editProductModal').modal('show');
}


document.addEventListener("DOMContentLoaded", () => {
    const buyingPriceInput = document.querySelector('input[name="buying-price"]');
    const sellingPriceInput = document.querySelector('input[name="saleing-price"]');
    const sellingPriceFeedback = document.querySelector('.selling-price-feedback');

    function validatePrices() {
        const buyingPrice = parseFloat(buyingPriceInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceInput.value) || 0;

        if (sellingPrice < buyingPrice) {
            sellingPriceInput.style.borderColor = "red";
            sellingPriceInput.style.backgroundColor = "#ffcccc";
            sellingPriceFeedback.style.display = "block";
            sellingPriceFeedback.textContent =
                "Selling price should not be less than buying price.";
        } else {
            sellingPriceInput.style.borderColor = "";
            sellingPriceInput.style.backgroundColor = "";
            sellingPriceFeedback.style.display = "none";
            sellingPriceFeedback.textContent = "";
        }
    }

    buyingPriceInput.addEventListener("input", validatePrices);
    sellingPriceInput.addEventListener("input", validatePrices);
});

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
    
//     $(document).ready(function () {
//     // Validate before form submission
//     $('form').on('submit', function (e) {
//         let isPerishable = $('#is-perishable').is(':checked'); // Check if checkbox is selected
//         if (isPerishable) {
//             $('#hidden-is-perishable').val('1'); // Set hidden field value to 1
//         } else {
//             $('#hidden-is-perishable').val('0'); // Set hidden field value to 0
//         }
//     });
// });

    // Trigger validation on input for buying and selling price fields
    $('#product-buying-price, #product-selling-price').on('input', function() {
        validatePrices();
    });




      // Validate before form submission
      $('form').on('submit', function(e) {
        // let isPerishable = $('#perishable-select').val() === 'pereshable';
        // let expirationDate = $('input[name="expiration-date"]').val();

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
            $(this).toggle($(this).find('td:nth-child(4)').text().toLowerCase().indexOf(value) > -1)
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

$(document).ready(function() {
    // Function to validate selling price against buying price
    function validatePrices() {
        var buyingPrice = parseFloat($('#edit-buying-price').val());
        var sellingPrice = parseFloat($('#edit-saleing-price').val());

        if (sellingPrice < buyingPrice) {
            $('#edit-saleing-price').addClass('is-invalid');
            $('.selling-price-feedback').text('Selling price must be greater than or equal to buying price.').show();
            $('button[type="submit"]').prop('disabled', true);
        } else {
            $('#edit-saleing-price').removeClass('is-invalid');
            $('.selling-price-feedback').text('').hide();
            $('button[type="submit"]').prop('disabled', false);
        }
    }

    $('#edit-buying-price, #edit-saleing-price').on('input', function() {
        validatePrices();
    });

    // Validate form before submission
    $('form').on('submit', function(e) {
        var isPerishable = $('#is-perishable').is(':checked');
        var expirationDate = $('input[name="expiration-date"]').val();

        // Reset feedback messages
        $('.expiration-date-feedback').hide();

        if (isPerishable && !expirationDate) {
            e.preventDefault(); // Prevent form submission
            $('.expiration-date-feedback').text('Expiration date is required for perishable items.').show(); // Show validation message
        }
    });
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

    
    // Check if the expiration date is within the next year (365 days)


$products = join_product_table();

$all_categories = find_all('categories');
$p_uom = find_all('uom');

$all_photo = find_all('media');
$min_expiration_date = date('Y-m-d', strtotime('+5 months'));
// Function to validate selling price

if(isset($_POST['add_product'])){
    
    
    $req_fields = array('product-title','product-categorie','buying-price', 'saleing-price', 'item-description', 'item-code', 'product-oum');
    validate_fields($req_fields);
  
    if(empty($errors)){
      $p_name  = remove_junk($db->escape($_POST['product-title']));
      $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
      $p_buy   = remove_junk($db->escape($_POST['buying-price']));
      $p_item_code   = remove_junk($db->escape($_POST['item-code']));
      $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
      $p_description  = remove_junk($db->escape($_POST['item-description']));
      $p_per   = remove_junk($db->escape($_POST['product-oum']));
     
    
  
    //   $expiration_date = $is_perishable ? remove_junk($db->escape($_POST['expiration-date'])) : NULL;
  
      if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
        $media_id = '0';
      } else {
        $media_id = remove_junk($db->escape($_POST['product-photo']));
      }
     

      $date = make_date(); // Assuming this generates the current date
      
      $query  = "INSERT INTO products (";
      $query .= "name, buy_price, sale_price, categorie_id, media_id, date,  description, item_code, uom_id";
      $query .= ") VALUES (";
      $query .= " '{$p_name}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}',  '{$p_description}', '{$p_item_code}', '{$p_per}'";
      $query .= ")";
  
      if($db->query($query)){
        $session->msg('s', "Product added ");
        echo "<script>
        setTimeout(function(){
            window.location.href = 'product.php';
        }, 100);
      </script>";
      } else {
        $session->msg('d', ' Sorry failed to add!');
        redirect('product.php', false);
      }
    } else{
      $session->msg("d", $errors);
      redirect('product.php.php', false);
    }
}
function validatePrices($buyPrice, $sellPrice) {
    if ($sellPrice < $buyPrice) {
        return false; // Selling price must be greater than or equal to buying price
    }
    return true;
}
?>
<?php
if (isset($_POST['edit_product'])) {
    // Sanitize inputs
    $product_id = (int)$_POST['product-id'];  // Sanitize product ID
    $product_name = $db->escape($_POST['product-title']);
    $product_description = $db->escape($_POST['item-description']);
    $product_item_code = $db->escape($_POST['item-code']);
    $product_buying_price = (float)$_POST['buying-price'];
    $product_selling_price = (float)$_POST['saleing-price'];
    $product_category = (int)$_POST['product-categorie'];  // Assuming product category ID is selected
    $product_photo = (int)$_POST['product-photo'];  // Assuming product photo ID is selected (if applicable)
    $date = date('Y-m-d H:i:s');  // Update the timestamp to the current time

    // Validate prices: selling price must be greater than or equal to buying price
    if ($product_selling_price < $product_buying_price) {
        $session->msg('d', "Selling price must be greater than or equal to the buying price.");
        redirect('inventory.php', false);  // Redirect to inventory page with error message
    }

    // Prepare the dynamic UPDATE query
    $query  = "UPDATE products SET ";
    $query .= "name = '{$product_name}', ";
    $query .= "buy_price = '{$product_buying_price}', ";
    $query .= "sale_price = '{$product_selling_price}', ";
    $query .= "categorie_id = '{$product_category}', ";
    $query .= "media_id = '{$product_photo}', ";
    $query .= "date = '{$date}', ";
    $query .= "description = '{$product_description}', ";
    $query .= "item_code = '{$product_item_code}' ";
    $query .= "WHERE id = '{$product_id}'";  // The condition to update the correct record

    // Execute the query
    if ($db->query($query)) {
        $session->msg('s', "Product updated successfully.");
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'product.php';  
                }, 100);
              </script>";
    } else {
        $session->msg('d', "Sorry, failed to update product!");
        redirect('product.php', false);  // Redirect with error message if update fails
    }
}
?>




<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper">
  <div class="content container-fluid">
  <div class="row">

     <div class="row">

                  
                        <div class="col">
                            <h3 class="page-title">Product Management</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin.php">Inventory Management</a></li>
                                <li class="breadcrumb-item active">Products</li>
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
            <div class="pull-right" style="margin-right: 2%;">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add product</a>
            </div>
    </div>           
        </div>
        <div class="panel-body">
    <div class="table-responsive">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">#</th>
                    <th class="text-center" style="width: 10%;">Photo</th>
                    <th class="text-center" style="width: 10%;">Categorie</th>
                    <th class="text-center" style="width: 50%;">Name</th>  
                    <th class="text-center" style="width: 10%;">Quantity</th>               
                    <th class="text-center" style="width: 10%;">Description</th>
                    <th class="text-center" style="width: 10%;">Item Code</th>
                    <th class="text-center" style="width: 10%;">Unit of measure</th>
                    <th class="text-center" style="width: 10%;">Buying Price</th>
                    <th class="text-center" style="width: 10%;">Selling Price</th>
                   
     
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($products as $product): ?>
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
            <td class="text-center"><?php echo remove_junk($product['name']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>           
            <td class="text-center"><?php echo remove_junk($product['description']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['item_code']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['uom_name']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
            <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
            <td class="text-center">
                <div class="btn-group">
                    <div class="dropdown action-label">
                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-dot-circle-o text-primary"></i> Actions
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" data-toggle="modal" data-target="#editProductModal" 
                                class="dropdown-item" 
                                onclick="editProductModal('<?php echo $product['id']; ?>', 
                                '<?php echo addslashes($product['name']); ?>', 
                                '<?php echo addslashes($product['categorie']); ?>', 
                                '<?php echo addslashes($product['description']); ?>', 
                                '<?php echo addslashes($product['item_code']); ?>', 
                                '<?php echo $product['buy_price']; ?>', 
                                '<?php echo $product['sale_price']; ?>', 
                                '<?php echo $product['quantity']; ?>')">
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="dropdown-item" title="Delete" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this product?');">
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
                                           
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-puzzle-piece"></i></span>
                                                    <select class="form-control" name="product-oum" id="product-oum" required>
                                                    <option value="">Unit of measure(UOM)</option>
                                                    <?php foreach ($p_uom as $cat): ?>
                                                        <option value="<?php echo (int)$cat['id']; ?>"><?php echo $cat['name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
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
                                     
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                            <input type="text" class="form-control" name="item-description" placeholder="Item description" required>
                                        </div>
                                    </div>
                                    </div>  

                                    <button type="submit" name="add_product" class="btn btn-primary pull-right " style="margin-right: 2%; margin-top: 2%;">Add product</button>
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
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
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
                            <div class="panel-heading bg-success">
                                <strong>
                                    <span class="fa fa-edit"></span>
                                    <span>Edit Product</span>
                                </strong>
                            </div>
                            <div class="panel-body">
                                <form method="post" action="product.php" class="clearfix">
                                    <!-- Hidden Field for Product ID -->
                                    <input type="hidden" id="edit-product-id" name="product-id">
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-th-large"></i></span>
                                            <input type="text" class="form-control" id="edit-product-title" name="product-title" placeholder="Product Name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="product-categorie" id="edit-product-category" required>
                                                    <option value="">Select category</option>
                                                    <?php foreach ($all_categories as $cat): ?>
                                                        <option value="<?php echo (int)$cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="product-photo" id="edit-product-photo">
                                                    <option value="">Select Product Photo</option>
                                                    <?php foreach ($all_photo as $photo): ?>
                                                        <option value="<?php echo (int)$photo['id']; ?>"><?php echo $photo['file_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" id="edit-buying-price" name="buying-price" placeholder="Buying Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    <input type="number" class="form-control" id="edit-saleing-price" name="saleing-price" placeholder="Selling Price" required>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                                <div class="invalid-feedback selling-price-feedback" style="display:none;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                            <input type="text" class="form-control" id="edit-item-code" name="item-code" placeholder="Item Code" required>
                                        </div>
                                        <div class="invalid-feedback item-code-feedback" style="display:none;"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="edit-item-description" name="item-description" placeholder="Item Description" required>
                                        </div>
                                    </div>

                                    <button type="submit" name="edit_product" class="btn btn-success pull-right" style="margin-right: 2%;">Save Changes</button>
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
