<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/db-connection.php'; ?>
<head>
    <title>Point of Sale - Fit Zone</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <link rel="stylesheet" href="assets/css/pos.css">
</head>
<style>
          .main-wrapper {
            width: 100%;
            height: auto;
            margin: 0%;
            flex-direction: column;
        }
        .product-image {
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
            border-radius: 5px; 
        }
        .order-image {
            height: 100%; 
            object-fit: cover; 
            border-radius: 5px; 
        }
    .category-list {
    display: flex;               /* Use flexbox for horizontal layout */
    overflow-x: auto;           /* Enable horizontal scrolling */
    white-space: nowrap;        /* Prevent wrapping of items */
    padding: 10px 0;           /* Add some padding for aesthetics */
}

.category-item {
    flex: 0 0 auto;             /* Prevent flex items from shrinking */
    margin-right: 10px;         /* Space between category buttons */
}

.category-image {
    width: 50px;                /* Set width for images */
    height: 50px;               /* Set height for images */
    object-fit: cover;          /* Ensure images cover the area without distortion */
}
.category-btn {
    width: 150px;
    overflow: hidden;
    white-space: nowrap;        /* Prevent text from wrapping */
    text-overflow: ellipsis;    /* Display ellipsis if text is too long */
    font-size: 12px;            /* Smaller font to fit longer names */
    padding: 5px 0;             /* Padding to maintain button height */
}

    </style>
<?php include 'layouts/body.php'; ?>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <?php include 'layouts/menu.php'; ?>
    
    <!-- Page Wrapper -->
    <div class="page-wrapper" >
        
        <!-- Page Content -->
        <div class="content container-fluid">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">FitZone Point of Sale</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="admin-dashboard.php">Return to Dashboard</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- POS Section -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <h4 class="card-title">Categories</h4>
                                <div class="category-list">
                                <?php
                                        // Fetch categories from the database
                                        $sql = "SELECT category_id, category_name FROM category";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Output data for each category
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<div class='col'>";
                                                echo "<button class='btn btn-outline-success category-btn' data-category-id='" . htmlspecialchars($row['category_id']) . "'>";
                                                echo "<img src='get-image.php?id=" . htmlspecialchars($row['category_id']) . "' class='category-image'><br>";
                                                echo htmlspecialchars($row['category_name']);
                                                echo "</button>";
                                                echo "</div>";
                                            }
                                        } else {
                                            echo "<div class='col'>No categories available.</div>";
                                        }
                                    ?>
                                </div>
                               
                            </div>
                            <h4>Select a Product</h4>
                            <div class="row" >
                                        <!-- Product Cards will be inserted here -->
                                         <div class="product-card" id="product-cards"></div>
                                </div>
                        </div>
                    </div>
                </div>               
                <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Current List</h4>
                        <div class="scrollmenu" id="order-list">
                            <!-- Order items will be inserted here -->
                        </div>
                        <button class="btn btn-outline-danger btn-block" onclick="clearOrder()">Clear All</button>
                        <div class="mt-4">
                            <p>Subtotal <span id="subtotal" class="float-end">₱0.00</span></p>
                            <div class="mt-4">
                            <div class="mt-4">
                    <h5>Select Discount</h5>
                    <select id="discount-select" class="form-select" onchange="applyDiscount()">
                        <option value="0">No Discount</option>
                        <?php
                            // Fetch discounts from the database
                            $sql = "SELECT discount_id, discount_name, discount_percentage FROM discount";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data for each discount
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['discount_percentage']) . "'>" . htmlspecialchars($row['discount_name']) . " (" . htmlspecialchars($row['discount_percentage']) . "%)</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="mt-4">
                <h5>Amount Received</h5>
                <input type="number" id="amount-received" class="form-control" placeholder="Enter amount" oninput="calculateChange()">
            </div>
            

            </div>

                <hr>
                <h4>Total <span id="total" class="float-end">₱0.00</span></h4>
                <div class="mt-4">
                <h5>Change <span id="change" class="float-end">₱0.00</span>    </h5>       
            </div>
            </div>
            <div class="mt-4">
                <h5>Payment Method</h5>
                <div class="btn-group btn-group-toggle d-flex" data-bs-toggle="buttons">
                    <label class="btn btn-outline-primary flex-fill">
                        <input type="radio" name="payment" id="cash" autocomplete="off"> Cash
                    </label>
                    <label class="btn btn-outline-primary flex-fill">
                        <input type="radio" name="payment" id="card" autocomplete="off">GCash
                    </label>
                </div>
            </div>
            <button class="btn btn-primary btn-block mt-4" onclick="printBill()">Print Bill</button>
        </div>
    </div>
</div>

            </div>
            
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->

<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

<script>
    // Array to keep track of the order
let order = [];

// Function to fetch and display products based on category
function fetchProducts(categoryId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'pos-product.php?category_id=' + categoryId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('product-cards').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Function to add product to the order
function addToOrder(productId, productName, productPrice) {
    // Check if the product is already in the order
    let product = order.find(p => p.id === productId);
    if (product) {
        product.quantity += 1;
    } else {
        order.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
    }
    updateOrderList();
}

// Function to update the order list
function updateOrderList(discount = 0) {
    let orderList = document.getElementById('order-list');
    let subtotal = 0;
    let total = 0;

    orderList.innerHTML = '';

    // Calculate subtotal and display the order
    order.forEach(item => {
        let itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        let div = document.createElement('div');
        div.className = 'order-item';
        div.innerHTML = `
            <img src="get-order-image.php?id=${item.id}" class="order-image">
            <div class="order-details">
                <h2>${item.name}</h2>
                <p class="text-success">₱${item.price.toFixed(2)}</p>
                <div class="order-quantity">
                    <button class="btn-danger" onclick="changeQuantity('${item.id}', -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn-primary" onclick="changeQuantity('${item.id}', 1)">+</button>
                </div>
                <p>Total: ₱${itemTotal.toFixed(2)}</p>
            </div>
        `;
        orderList.appendChild(div);
    });

    // Calculate the discount and total
    let discountAmount = subtotal * (discount / 100);
    total = subtotal - discountAmount;

    // Update the displayed totals
    document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
}


// Function to change quantity
function changeQuantity(productId, change) {
    let product = order.find(p => p.id === productId);
    if (product) {
        product.quantity += change;
        if (product.quantity <= 0) {
            order = order.filter(p => p.id !== productId);
        }
        updateOrderList();
    }
}

// Function to clear the order
function clearOrder() {
    order = [];
    updateOrderList();
}

// Function to print the bill (just a placeholder for now)
function printBill() {
    alert('Printing Bill...');
}

// Event listener for category buttons
document.querySelectorAll('.category-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        document.querySelectorAll('.category-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        
        this.classList.add('active');
        
        var categoryId = this.getAttribute('data-category-id');
        fetchProducts(categoryId);
    });
});

// Sample code to handle add to order (you should adjust this to fit your product card structure)
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('add-to-order-btn')) {
        let productId = e.target.getAttribute('data-product-id');
        let productName = e.target.getAttribute('data-product-name');
        let productPrice = parseFloat(e.target.getAttribute('data-product-price'));

        addToOrder(productId, productName, productPrice);
    }
});

     // Function to fetch and display products based on category
     function fetchProducts(categoryId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'pos-product.php?category_id=' + categoryId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('product-cards').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Fetch all products by default (pass an empty string to get all products)
    fetchProducts('');

    // Event listener for category buttons
    document.querySelectorAll('.category-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        // Remove 'active' class from all buttons
        document.querySelectorAll('.category-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        
        // Add 'active' class to the clicked button
        this.classList.add('active');
        
        var categoryId = this.getAttribute('data-category-id');
        fetchProducts(categoryId);
    });
});
function applyDiscount() {
    const discountSelect = document.getElementById('discount-select');
    const discountPercentage = parseFloat(discountSelect.value);
    updateOrderList(discountPercentage);
}
function calculateChange() {
            const amountReceived = parseFloat(document.getElementById('amount-received').value) || 0;
            const total = parseFloat(document.getElementById('total').textContent.replace('₱', '').replace(',', '')) || 0;
            const change = amountReceived - total;

            document.getElementById('change').textContent = `₱${change.toFixed(2)}`;
        }
 document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                fetchProducts(categoryId);
            });
        });
</script>
</body>
</html>
