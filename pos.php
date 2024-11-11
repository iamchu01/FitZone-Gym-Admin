<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - GYMMS</title>
    <?php include 'layouts/head-main.php'; ?>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/session.php'; ?>

    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>

    <style>
        .cart-table { width: 100%; }
        .cart-table th, .cart-table td { text-align: center; }
        .total-display { font-size: 1.5rem; font-weight: bold; }
    </style>
    <?php 
     $products = join_product_table();
    $all_categories = find_all('categories');
    ?>
</head>
<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Point of Sale (POS)</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">POS</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- POS Section -->
                <div class="row">
                    <div class="col-md-8">
                        <!-- Product Selection -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Select Products</h5>
                                <input type="text" id="product-search" class="form-control" placeholder="Search Item code or Name...">
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item code</th>  
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Available qty</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Add</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list">
                                        <?php foreach ($products as $product): ?>                                          
                                            <tr>
                                                <td><?php echo $product['item_code']; ?></td>
                                                <td><?php echo $product['categorie']; ?></td>
                                                <td><?php echo $product['description']; ?></td>
                                                <td><?php echo $product['quantity'] > 0 ? $product['quantity'] : 'Out of Stock'; ?></td>
                                                <td><?php echo number_format($product['sale_price'], 2); ?></td>
                                                
                                                <td>
                                                    <input type="number" 
                                                        id="qty-<?php echo $product['id']; ?>" 
                                                        value="1" 
                                                        min="1" 
                                                        max="<?php echo $product['quantity']; ?>" 
                                                        class="form-control" 
                                                        style="width: 70px;" 
                                                        <?php echo $product['quantity'] == 0 ? 'disabled' : ''; ?>>
                                                </td>
                                                
                                                <td>
                                                    <button class="btn btn-success add-to-cart" 
                                                            data-id="<?php echo $product['id']; ?>" 
                                                            data-name="<?php echo $product['name']; ?>" 
                                                            data-price="<?php echo $product['sale_price']; ?>" 
                                                            <?php echo $product['quantity'] == 0 ? 'disabled' : ''; ?>>
                                                        Add
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Cart Section -->
                        <div class="card">
    <div class="card-header">
        <h5>Cart</h5>
    </div>
    <div class="card-body">
        <table class="table cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Cart items go here -->
            </tbody>
        </table>
       
        <div class="form-group">
    <label for="discount">Discount</label>
    <select id="discount" class="form-control">
        <option value="0">No Discount</option>
        <option value="5">5% Off</option>
        <option value="10">10% Off</option>
        <option value="15">15% Off</option>
        <option value="20">20% Off</option>
    </select>
</div>
<div class="total-display">Total: ₱<span id="total-price">0.00</span></div>
        <!-- Payment Method Selection -->
        <div class="form-group">    
            <label for="payment-method">Payment Method</label><br>
            
            <!-- Cash Option -->
            <input type="radio" id="cash" name="payment-method" value="cash" class="form-check-input" checked>
            <label for="cash" class="form-check-label border-primary p-2">Cash</label>

            <!-- GCash Option -->
            <input type="radio" id="gcash" name="payment-method" value="gcash" class="form-check-input ml-3">
            <label for="gcash" class="form-check-label border-success p-2">GCash</label>
        </div>

        <button class="btn btn-primary btn-block" id="checkout">Checkout</button>
    </div>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
  $(document).ready(function () {
    let cart = {};
    let totalQuantityInCart = 0; // Track total quantity in the cart

    $('.add-to-cart').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = parseFloat($(this).data('price'));
        const quantity = parseInt($('#qty-' + id).val());
        const availableQty = parseInt($('#qty-' + id).attr('max')); // Get the max available quantity

        // Check if the requested quantity is greater than the available stock
        if (quantity > availableQty) {
            alert('Quantity exceeds available stock!');
            $('#qty-' + id).val(availableQty); // Reset to max available quantity
            return; // Prevent adding to cart
        }

        // Check if adding this quantity will exceed the total available stock in the cart
        if (totalQuantityInCart + quantity > availableQty) {
            alert('You cannot add more than the available stock in the cart.');
            return; // Prevent adding to cart
        }

        // Add to cart or update quantity if it exists
        if (cart[id]) {
            cart[id].quantity += quantity;
        } else {
            cart[id] = { name, price, quantity };
        }

        totalQuantityInCart += quantity; // Update the total quantity in cart
        updateCart();
    });

    $(document).on('click', '.remove-from-cart', function () {
        const id = $(this).data('id');
        totalQuantityInCart -= cart[id].quantity; // Update total quantity when removing from cart
        delete cart[id];
        updateCart();
    });

    function updateCart() {
        let total = 0;
        $('#cart-items').empty();
        $.each(cart, function (id, item) {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            $('#cart-items').append(`
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>₱${item.price.toFixed(2)}</td>
                    <td>₱${itemTotal.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm remove-from-cart" data-id="${id}">X</button></td>
                </tr>
            `);
        });
        $('#total-price').text(total.toFixed(2));
    }
});

$('#product-search').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('tbody tr').filter(function() {
        $(this).toggle(
            $(this).find('td:nth-child(1)').text().toLowerCase().indexOf(value) > -1 ||
            $(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1
        );
    });
});
    </script>

<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

</body>
</html>
