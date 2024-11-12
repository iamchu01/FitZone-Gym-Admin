<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
</head> 
<script type="text/javascript">
function setEditCategory(name) {
   
    document.getElementById('product-category').value = name;
}
function confirmEdit() {
    return confirm("changing affects all product list Are you sure you want to save these changes?");
  }

  $(document).ready(function() {
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
    $('#addProductModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var categoryId = button.data('category-id'); // Extract category ID
        var categoryName = button.data('category-name'); // Extract category name

        var modal = $(this);
        modal.find('#addProductModalLabel').text('Stock in Product: ' + categoryName); // Set modal header

        // Reset dropdown
        var $dropdown = modal.find('#product-category');
        $dropdown.val(categoryId); // Set selected category in dropdown

        // Check if the first option is already set, if not update it
        if ($dropdown.find('option[value=""]').length > 0) {
            $dropdown.find('option[value=""]').text('Selected: ' + categoryName); // Change the default option text
        }
    });
    $('#submitProduct').on('click', function() {
        // Handle form submission here
        $('#addProductForm').submit(); // Or your AJAX logic to submit the form
    });
    
    $('#category-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
        });
    });
});


</script>

<?php
  $all_categories = find_all('categories');
  $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
// Handle category deletion via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cat'])) {
  $categorie_id = (int)$_POST['id'];
  $categorie = find_by_id('categories', $categorie_id);
  
  if (!$categorie) {
      $session->msg("d", "Missing category id.");
      redirect('categorie.php');
  }

  // Check if the category is used in any products and get total quantity
  $query = "SELECT SUM(quantity) AS total_quantity FROM products WHERE categorie_id = '{$categorie_id}'";
  $result = $db->query($query);
  
  if ($result && $row = $result->fetch_assoc()) {
      $total_quantity = $row['total_quantity'] ? $row['total_quantity'] : 0;

      if ($total_quantity > 0) {
          // If there are products linked to this category, do not delete it
          $session->msg("d", "Cannot delete Product '{$categorie['name']}' as it hasa total quantity of {$total_quantity}.");
          redirect('categorie.php');
      }
  }
  
}

?>
<?php
$all_photo = find_all('media');
$min_expiration_date = date('Y-m-d', strtotime('+5 months'));

if (isset($_POST['add_product'])) {
    $item_code = remove_junk($db->escape($_POST['item-code']));
    $is_perishable = isset($_POST['is_perishable']) ? (int)$_POST['is_perishable'] : 0; // Default to non-perishable
    $expiration_date = NULL; // Initialize to NULL for non-perishable items
    $item_discription = remove_junk($db->escape($_POST['item-description']));
    $req_fields = ['product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'item-code','item-description'];
    validate_fields($req_fields);
    
    if (empty($errors)) {
       
        $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
        $p_qty   = (int)remove_junk($db->escape($_POST['product-quantity'])); // Ensure quantity is an integer
        $p_buy   = (float)remove_junk($db->escape($_POST['buying-price'])); // Ensure buying price is a float
        $p_sale  = (float)remove_junk($db->escape($_POST['saleing-price'])); // Ensure selling price is a float
        
        if ($is_perishable) {
            if (isset($_POST['expiration-date']) && $_POST['expiration-date'] >= $min_expiration_date) {
                $expiration_date = remove_junk($db->escape($_POST['expiration-date']));
            } else {
                $errors[] = "Expiration date is required and must be at least 5 months from today for perishable items.";
            }
        }

        // Handle media ID
        $media_id = !empty($_POST['product-photo']) ? remove_junk($db->escape($_POST['product-photo'])) : '0';

        // Check for unique item code
        $query_check = "SELECT * FROM products WHERE item_code = '{$item_code}' LIMIT 1";
        $result_check = $db->query($query_check);

        if ($result_check->num_rows > 0) {
            $errors[] = "Item code must be unique. This code already exists.";
        } else {
            $date = make_date(); // Get the current date

            $query  = "INSERT INTO products (quantity, buy_price, sale_price, categorie_id, media_id, date, expiration_date, is_perishable, item_code, description) VALUES ";
            $query .= "('{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', ";
            $query .= $expiration_date ? "'{$expiration_date}', " : "NULL, ";
            $query .= "{$is_perishable}, '{$item_code}', '{$item_discription}')";
            // Execute the query
            if ($db->query($query)) {
                $session->msg('s', "Product added successfully!");
                echo "<script>
                setTimeout(function(){
                    window.location.href = 'stock-in.php';
                }, 100);
              </script>";
               // redirect('stock-in.php', false);
            } else {
               
                 $session->msg('d', 'Sorry, failed to add product!');
                redirect('stock-in.php', false);
            }
        }
    } else {
        $session->msg("d", implode("<br>", $errors)); // Show all errors
        redirect('stock-in.php', false);
    }
}
$category_stock = [];

// Fetch quantities for each category
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


<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper" style="padding-top:2%;">
    <div class="content container-fluid">
   <div class="row">
      <div class="col"> <h3 class="page-title">Stock In</h3>
    </div>  
        <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
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
                    <!-- <div class="pull-right">
                 <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Stock in</a> -->
            <!-- </div>
                    <div class="pull-right">
                 -->
           
                    </div>
                   
                  
            <div class="panel-body">
            <div class="table-responsive">
            <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Products List</th>
                <th class="text-center" style="width: 10%;">In-Stock</th>
                <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($all_categories as $cat): ?>
        <tr>
    <td class="text-center"><?php echo count_id(); ?></td>
    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
    <td class="text-center"><?php echo $category_stock[$cat['id']]; ?></td> <!-- Display In-Stock quantity -->
    <td class="text-center">
            <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#addProductModal"  aria-expanded="false"  onclick="setEditCategory(<?php echo $cat['id']; ?>, '<?php echo addslashes($cat['name']); ?>');">
                <i class=" fa fa-gift text-primary"></i> Stock-in
            </a>

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
                                    <!-- stock-in product modal -->
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
                                    <div class="form-group">
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

                                    <button type="submit" name="add_product" class="btn btn-primary">Add product</button>
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
