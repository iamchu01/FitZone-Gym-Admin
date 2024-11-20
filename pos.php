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
    
    <style>
        .cart-table { width: 100%; }
        .cart-table th, .cart-table td { text-align: center; }
        .total-display { font-size: 1.5rem; font-weight: bold; }
       
@media print {
    /* Hide elements not needed in the printed receipt */
    body * {
        visibility: hidden;
    }
    #receipt-modal, #receipt-modal * {
        visibility: visible;
    }
    #receipt-modal {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        font-size: 12px;
    }

    /* Center content and remove border for a clean look */
    .modal-content {
        border: none;
        box-shadow: none;
    }
    .modal-header, .modal-footer {
        display: none; /* Hide modal header and footer for print */
    }

    /* Style adjustments for better readability */
    .modal-body {
        padding: 0;
    }
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    .table th, .table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #000;
    }
    .modal-title {
        font-size: 1.25em;
        font-weight: bold;
        text-align: center;
    }
}


    </style>
<?php
require_once('vincludes/load.php');

if (isset($_POST['confirm_checkout'])) {
    // Assuming these are POSTed values from the modal form
    $cart_items = $_POST['cart_items']; // Array of items with 'id', 'name', 'quantity', 'price'
    $total_amount = $_POST['total_amount'];
    $discount = $_POST['discount'] ?? 0;
    $amount_received = $_POST['amount_received'];
    $change_cash = $_POST['change'];
    $payment_method = $_POST['payment_method'];
    $transaction_id = $_POST['transaction_id']; // Assuming transaction ID is passed from the frontend

    // Start a transaction for better error handling
    $db->begin_transaction();
    
    try {
        // Insert the transaction details into the `pos_transaction` table
        $transaction_sql = "INSERT INTO pos_transaction 
                            (id, transaction_date, total_amount, discount, amount_received, change_cash, payment_method, created_at)
                            VALUES 
                            ('{$transaction_id}', NOW(), '{$total_amount}', '{$discount}', '{$amount_received}', '{$change_cash}', '{$payment_method}', NOW())";

        $result = $db->query($transaction_sql);

        if (!$result) {
            throw new Exception("Error inserting transaction: " . $db->con);
        }

        // Loop through cart items to update product quantities and insert each item into the `pos_transaction_items` table
        foreach ($cart_items as $item) {
            $product_id = $item['id'];
            $product_quantity = $item['quantity'];
            $product_name = $item['name'];
            $product_price = $item['price']; // Assuming price is passed for each item

            // Fetch the first available batch for the product
            $batch_sql = "SELECT b.id, b.batch_quantity 
                          FROM batches b 
                          WHERE b.product_id = '{$product_id}' AND b.batch_quantity >= {$product_quantity} 
                          ORDER BY b.created_at ASC 
                          LIMIT 1";  // Fetch the first available batch
            $batch_result = $db->query($batch_sql);
            $batch = $batch_result->fetch_assoc();

            if ($batch) {
                // Deduct the quantity from the batch
                $new_batch_quantity = $batch['batch_quantity'] - $product_quantity;
                $update_batch_sql = "UPDATE batches SET batch_quantity = '{$new_batch_quantity}' WHERE id = '{$batch['id']}'";
                $update_batch_result = $db->query($update_batch_sql);

                if (!$update_batch_result) {
                    throw new Exception("Error updating batch quantity: " . $db->con);
                }

                // Insert transaction record for each product purchased into `pos_transaction_items`
                $transaction_item_sql = "INSERT INTO pos_transaction_items 
                                        (transaction_id, product_name, product_quantity, price, created_at)
                                        VALUES 
                                        ('{$transaction_id}', '{$product_name}', {$product_quantity}, '{$product_price}', NOW())";

                $transaction_item_result = $db->query($transaction_item_sql);

                if (!$transaction_item_result) {
                    throw new Exception("Error inserting transaction item: " . $db->con);
                }
            } else {
                throw new Exception("Not enough stock available for product: {$product_name}");
            }
        }

        // Commit the transaction if everything is successful
        $db->commit();
        $session->msg('s', 'Transaction successful!');
        redirect('pos.php', false);
    } catch (Exception $e) {
        // Rollback if any error occurs
        $db->rollback();
        $session->msg('d', 'Sorry, failed to process transaction! Error: ' . $e->getMessage());
        redirect('pos.php', false);
    }
}

?>  

    <?php 
    $products = join_product_table();
    $all_categories = find_all('categories');
    $discounts = find_all('discount');
    ?>
</head>
<body>
    <div class="main-wrapper" >
        <?php include 'layouts/menu.php'; ?>
        <div class="page-wrapper"style="padding-top:0%;">
            <div class="content container-fluid ">
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
                            <div class="card-header bg-success">
                                <h5>Select Products</h5>
                                <input type="text" id="product-search" class="form-control" placeholder="Search Item code or Name...">
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item code</th>  
                                            <th>Name</th>
                                            <th>Categorie</th>
                                            <th>Unit of measure</th>
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
                                                <td><?php echo $product['name']; ?></td>
                                                <td><?php echo $product['categorie']; ?></td>
                                                <td><?php echo $product['uom_name']; ?></td>
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
                        <div class="card" style="height: 100%">
                            <div class="card-header bg-success">
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
                                        <?php foreach ($discounts as $discount): ?>
                                            <option value="<?php echo $discount['discount_percentage']; ?>">
                                            <?php echo $discount['discount_name']; ?> - <?php echo $discount['discount_percentage']; ?>%  
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="total-price">Total Amount Due</label>
                                    <div id="total-price" class="form-control" disabled>0.00</div>
                                </div>               
                                <div class="form-group">
                                    <label for="amount-received">Amount Received</label>
                                    <input type="number" id="amount-received" class="form-control" placeholder="Enter amount received">
                                </div>

                                <div class="form-group">
                                    <label for="change">Change</label>
                                    <div id="change" class="form-control" disabled>0.00</div>
                                </div>

                                <div class="form-group">    
                                    <label for="payment-method">Payment Method</label><br>
                                    <input type="radio" id="cash" name="payment-method" value="cash" class="form-check-input" checked>
                                    <label for="cash" class="form-check-label border-primary p-2">Cash</label>

                                    <input type="radio" id="gcash" name="payment-method" value="gcash" class="form-check-input ml-3">
                                    <label for="gcash" class="form-check-label border-success p-2">GCash</label>
                                </div>
                                            
                                <button class="btn btn-primary btn-block" id="checkout" data-toggle="modal">Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- recibo -->
    <form method="POST" action="pos.php" id="checkout-form">
    <div class="modal" tabindex="-1" id="receipt-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex flex-column align-items-center">
                    <h5 class="modal-title text-center">FITZONE Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="assets/img/fzlogo.png" alt="FITZONE Logo" class="mb-2" style="max-width: 100px;">
                    </div>
                    <p><strong>Transaction ID:</strong> <span id="transaction-id" class="position-absolute end-0" style=" margin-right: 2%;"></span></p>
                    <p><strong>Date:</strong> <span id="transaction-date" class="position-absolute end-0" style=" margin-right: 2%;"></span></p>

                    <h5>Products Purchased:</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="product-list-receipt">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>

                    <p><strong>Discount Applied:</strong> <span id="discount-receipt" class="position-absolute end-0" style=" margin-right: 2%;"></span></p>
                    <p><strong>Amount Received:</strong><span class="position-absolute end-0" style="margin-right: 2%;">₱<label for="amount-received-receipt"></label><span id="amount-received-receipt"></span></span></p>
                    <p><strong>Change:</strong><span class="position-absolute end-0" style="margin-right: 2%;">₱<span id="change-receipt"></span></span></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-secondary" onclick="window.print();"><div class="fa fa-print"> print</div></button>
                    <!-- Change the type to 'button' to avoid form submission -->
                    <button type="button" class="btn btn-primary" id="confirm-checkout">Confirm Checkout</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function () {
    let cart = {}; // Cart object to store items
    let totalQuantityInCart = 0; // Track total quantity in the cart
    let discount = 0; // Discount value
    let amountReceived = 0; // Amount received from user
    let totalPrice = 0; // Store total price here

    // Handle add to cart
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
        const existingQuantityInCart = cart[id] ? cart[id].quantity : 0;

// Check if the requested quantity exceeds the available stock
if (existingQuantityInCart + quantity > availableQty) {
    alert(`Cannot add more than ${availableQty} units of this product to the cart.`);
    $('#qty-' + id).val(availableQty - existingQuantityInCart); // Set to the remaining stock
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

    // Handle remove from cart
    $(document).on('click', '.remove-from-cart', function () {
        const id = $(this).data('id');
        totalQuantityInCart -= cart[id].quantity; // Update total quantity when removing from cart
        delete cart[id];
        updateCart();
    });

    // Update cart display and calculate total
    function updateCart() {
        totalPrice = 0;
        $('#cart-items').empty();
        $.each(cart, function (id, item) {
            const itemTotal = item.price * item.quantity;
            totalPrice += itemTotal;
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

        // Apply discount
        totalPrice = totalPrice - (totalPrice * discount / 100);

        // Display the total after discount
        $('#total-price').text(totalPrice.toFixed(2));

        // Update the amount received input (this will calculate the change)
        $('#amount-received').on('input', function () {
            amountReceived = parseFloat($(this).val()) || 0;
            const change = amountReceived - totalPrice;
            $('#change').text(change < 0 ? '0.00' : '' + change.toFixed(2));
        });
    }

    // Handle discount selection
    $('#discount').on('change', function () {
        discount = parseFloat($(this).val());
        updateCart();
    });

    // Handle checkout
    $('#checkout').on('click', function () {
        if (totalQuantityInCart === 0) {
            alert('No items in cart!');
            return;
        }

        amountReceived = parseFloat($('#amount-received').val());
        if (isNaN(amountReceived) || amountReceived < totalPrice) {
            alert('Insufficient amount received!');
            return;
        }

        // Generate transaction ID
        const transactionId = 'TXN-' + Math.floor(Math.random() * 1000000);
        $('#transaction-id').text(transactionId);
        $('#transaction-date').text(new Date().toLocaleString());

        // Populate the receipt with product details
        $('#product-list-receipt').empty();
        for (let id in cart) {
            const item = cart[id];
            const itemTotal = item.price * item.quantity;
            $('#product-list-receipt').append(`
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>₱${item.price.toFixed(2)}</td>
                    <td>₱${(item.price * item.quantity).toFixed(2)}</td>
                </tr>
            `);
        }

        // Display the discount, amount received, and change
        $ ('#discount-receipt').text(discount ? `${discount}%` : 'None');
        $('#amount-received-receipt').text(amountReceived.toFixed(2));
        $('#change-receipt').text ((amountReceived - totalPrice).toFixed(2));

        // Show the receipt modal using Bootstrap 5 modal method
        var myModal = new bootstrap.Modal(document.getElementById('receipt-modal'), {
            keyboard: false
        });
        myModal.show();
    });
    $('#product-search').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('tbody tr').each(function() {
        var match = $(this).find('td:nth-child(1)').text().toLowerCase().indexOf(value) > -1 ||
                    $(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1;
        $(this).toggle(match);
    });
});


    // Handle the confirm checkout button (submit the data to the server)
    $('#confirm-checkout').click(function () {
        let cartItems = [];
        for (let id in cart) {
        const item = cart[id];
        cartItems.push({
            id: id,
            name: item.name,
            quantity: item.quantity,
            price: item.price
        });
    }
        const totalAmount = $('#total-price').text();
        const discount = $('#discount').val();
        const amountReceived = $('#amount-received').val();
        const change = $('#change').text();
        const paymentMethod = $('input[name="payment-method"]:checked').val();
        const transactionId = Math.floor(Math.random() * 1000000); // Generate a unique transaction ID

        $.ajax({
    url: 'pos.php',
    type: 'POST',
    data: {
        confirm_checkout: true,
        cart_items: cartItems,
        total_amount: totalAmount,
        discount: discount,
        amount_received: amountReceived,
        change: change,
        payment_method: paymentMethod,
        transaction_id: transactionId
    },
    success: function (response) {
        // Assuming the server response redirects to a confirmation or success page
        alert('Transaction successful!');
        window.location.href = 'pos.php';
    },
    error: function (error) {
        alert('Failed to process transaction. Please try again.');
        console.error(error);
    }
});

    });
    
});
// console.log({
//     cart_items: cartItems,
//     total_amount: totalAmount,
//     discount: discount,
//     amount_received: amountReceived,
//     change: change,
//     payment_method: paymentMethod,
//     transaction_id: transactionId
// });

    
</script>
</body>
</html>
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
