<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
</head>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Event listener for opening the modal and populating fields
    const stockInButtons = document.querySelectorAll('.stock-in');
    stockInButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Extract data attributes
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productCategorie = this.getAttribute('data-product-categorie');
            const productUom = this.getAttribute('data-product-uom');
            const productItemCode = this.getAttribute('data-product-item-code');
            const productDescription = this.getAttribute('data-product-description');

            // Populate modal fields
            document.getElementById('product_name').value = productName;
            document.getElementById('product-uom').value = productUom;
            document.getElementById('product-categorie').value = productCategorie;
            document.getElementById('item-code').value = productItemCode;
            document.querySelector('[name="item-description"]').value = productDescription;

            // Set the form action to include the product ID
            const form = document.querySelector('#stock-in-form');
            form.setAttribute('action', `product.php?id=${productId}`);

            // Show the modal
            $('#stock-in-Modal').modal('show');
        });
    });

    // Handle checkbox for perishable items
    const isPerishableCheckbox = document.getElementById('is-perishable');
    const expirationDateGroup = document.getElementById('expiration-date-group');
    if (isPerishableCheckbox) {
        isPerishableCheckbox.addEventListener('change', function () {
            if (this.checked) {
                expirationDateGroup.style.display = 'block';
            } else {
                expirationDateGroup.style.display = 'none';
            }
        });
    }

    // Validate quantity input
    document.getElementById('add-quantity').addEventListener('input', function () {
        const feedback = document.getElementById('quantity-feedback');
        if (this.value <= 0) {
            feedback.style.display = 'block';
        } else {
            feedback.style.display = 'none';
        }
    });
});
</script>

<?php
// PHP: Handle product stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quantity'])) {
    $add_quantity = (int)$_POST['add_quantity'];
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($add_quantity > 0 && $product_id > 0) {
        $query = "UPDATE products SET quantity = quantity + {$add_quantity} WHERE id = {$product_id}";
        if ($db->query($query)) {
            $session->msg('s', 'Stock updated successfully!');
            redirect('stock-in.php');
        } else {
            $session->msg('d', 'Failed to update stock.');
        }
    } else {
        $session->msg('d', 'Invalid quantity or product ID.');
    }
    redirect('stock-in.php');
}

// Fetch data for display
$products = join_product_table();
$all_categories = find_all('categories');
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
                        <input type="text" class="form-control" id="product-uom" disabled>
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
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>