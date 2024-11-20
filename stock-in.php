<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS Admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
</head>

<?php
// Handle product stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $add_quantity = isset($_POST['add_quantity']) ? (int)$_POST['add_quantity'] : 0;
    $is_perishable = isset($_POST['is_perishable']) ? 1 : 0;
    $expiration_date = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

    if ($product_id > 0 && $add_quantity > 0) {
        try {
            // Begin transaction
            // $db->beginTransaction();

            // Insert into batches table
            $insert_batch_query = "INSERT INTO batches (product_id, batch_quantity, expiration_date) 
                                   VALUES ({$product_id}, {$add_quantity}, '{$expiration_date}')";

            if ($db->query($insert_batch_query)) {
                // Commit the transaction
                $db->commit();
                $session->msg('s', 'Stock added and batch recorded successfully!');
            } else {
                // Rollback if batch insertion fails
                $db->rollBack();
                $session->msg('d', 'Failed to insert batch data.');
            }
        } catch (Exception $e) {
            // Rollback if any exception occurs
            $db->rollBack();
            $session->msg('d', 'An error occurred: ' . $e->getMessage());
        }
    } else {
        $session->msg('d', 'Invalid product ID or quantity.');
    }

    // Redirect to avoid form resubmission
    redirect('stock-in.php');
}

?>

<?php
// Fetch data for display
$products = join_product_table();
?>

<?php include 'layouts/menu.php'; ?>
<div class="page-wrapper" style="padding-top:2%;">
    <div class="content container-fluid">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Stock In</h3>
            </div>
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
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Photo</th>
                                    <th class="text-center">Categorie</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Unit of Measure</th>
                                    <th class="text-center">Buying Price</th>
                                    <th class="text-center">Selling Price</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
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
                                        <td class="text-center"><?php echo remove_junk($product['item_code']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['uom_name']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                        <td class="text-center"><?php echo remove_junk($product['description']); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-success stock-in fa fa-list"
                                                data-product-id="<?php echo (int)$product['id']; ?>"
                                                data-product-name="<?php echo remove_junk($product['name']); ?>"
                                                data-product-categorie="<?php echo remove_junk($product['categorie']); ?>"
                                                data-product-description="<?php echo remove_junk($product['description']); ?>"
                                                data-product-uom="<?php echo remove_junk($product['uom_name']); ?>"
                                                data-product-item-code="<?php echo remove_junk($product['item_code']); ?>"
                                                data-toggle="modal"
                                                data-target="#stock-in-Modal">
                                                Stock In
                                            </button>
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

<!-- Stock-in Modal -->
<div class="modal" id="stock-in-Modal" tabindex="-1" role="dialog" aria-labelledby="stock-in-Modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="stock-in-ModalLabel">Stock In</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="stock-in-form" method="POST">
                    <input type="hidden" id="product-id" name="product_id">
                    <div class="form-group">
                        <label for="product-name">Product Name:</label>
                        <input type="text" class="form-control" id="product_name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="product-categorie">Categorie:</label>
                        <input type="text" class="form-control" id="product-categorie" disabled>
                    </div>
                    <div class="form-group">
                        <label for="item_code">Item Code:</label>
                        <input type="text" class="form-control" id="item-code" disabled>
                    </div>
                    <div class="form-group">
                        <label for="product-uom">Unit of Measure (UOM):</label>
                        <input type="text" class="form-control" id="product-uom" >
                    </div>
                    <div class="form-group">
                        <label for="add-quantity">Add Quantity:</label>
                        <input type="number" class="form-control" id="add-quantity" name="add_quantity" required>
                        <div class="invalid-feedback" id="quantity-feedback">Quantity must be greater than 0</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is-perishable" name="is_perishable">
                        <label class="form-check-label" for="is-perishable">Perishable</label>
                    </div>
                    <div class="form-group" id="expiration-date-group" style="display: none;">
                        <label for="expiration-date">Expiration Date:</label>
                        <input type="date" class="form-control" name="expiration_date">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Pass product data to the modal
    $('.stock-in').on('click', function() {
        const button = $(this);
        $('#product-id').val(button.data('product-id'));
        $('#product_name').val(button.data('product-name'));
        $('#product-categorie').val(button.data('product-categorie'));
        $('#item-code').val(button.data('product-item-code'));
        $('#product-uom').val(button.data('product-uom'));
    });

    // Toggle expiration date input
    $('#is-perishable').on('change', function() {
        if ($(this).is(':checked')) {
            $('#expiration-date-group').show();
        } else {
            $('#expiration-date-group').hide();
        }
    });

    // Validation for stock quantity
    $('#stock-in-form').on('submit', function(e) {
        const quantity = $('#add-quantity').val();
        if (quantity <= 0) {
            e.preventDefault();
            $('#quantity-feedback').show();
        } else {
            $('#quantity-feedback').hide();
        }
    });
</script>
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

</body>
</html>
