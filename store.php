<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - HRMS Admin Template</title>

    <?php include 'layouts/head-css.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/session.php'; ?>
    <?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['order_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];

    // Validate input (optional but recommended)
    $allowed_statuses = ['Pending', 'On Hold', 'Ready for Pick-up', 'Completed', 'Cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        $session->msg("d", "Invalid status selected.");
        header("Location: store.php");
        exit();
    }

    // Update the order status
    $update_query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        $session->msg("s", "Order status updated successfully.");
    } else {
        $session->msg("d", "Failed to update order status.");
    }
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: store.php");
    exit();
}

    ?>
    <script>


        document.addEventListener('DOMContentLoaded', function() {
            var viewProductBtn = document.getElementById('viewProductBtn');
            var productTable = document.getElementById('productTable1');
            var viewPendingBtn = document.getElementById('viewPendingBtn');
            var adminOrderTable = document.getElementById('adminOrderTable');

            if (viewProductBtn) {
                viewProductBtn.addEventListener('click', function() {
                    if (productTable.style.display === 'none' || productTable.style.display === '') {
                       
                        productTable.style.display = 'table';  // Show the product table
                    } else {
                        productTable.style.display = 'none';  // Hide the product table
                    }
                });
            }
            $('#orderStatusModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var orderId = button.data('order-id'); // Extract order ID
    var currentStatus = button.data('current-status'); // Extract current order status

    var modal = $(this);
    modal.find('#order_id').val(orderId); // Set the order ID in the hidden input
    modal.find('#order_status').val(currentStatus); // Set the selected order status in the dropdown
});

            if (viewPendingBtn) {
                viewPendingBtn.addEventListener('click', function() {
                    if (adminOrderTable.style.display === 'none' || adminOrderTable.style.display === '') {
                        adminOrderTable.style.display = 'table';  // Show the admin orders table
                    } else {
                        adminOrderTable.style.display = 'none';  // Hide the admin orders table
                    }
                });
            }
        });
    </script>

    <style>
        .main-wrapper .modal-dialog {
            max-width: 80%;
            width: 100%; /* Ensures the modal takes the full width up to max-width */
        }
        .panel-icon {
            font-size: 40px;
            display: fit-content;
            align-items: center;
            justify-content: center;
        }
        /* Ensures table is hidden initially */
        #productTable1 {
            display: none;
            width: 100%; /* Ensure full width */
            table-layout: fixed; /* Prevents table from stretching */
        }

        #adminOrderTable {
            display: none;
        }

        .panel-box:hover{
            transition: transform 0.3s ease;
            transform: scale(1.05);
            background-color: #bff7d3;
        }
        .badge-pending {
    background-color: #ffc107; /* Yellow */
    color: #000; /* Black text */
}

.badge-on-hold {
    background-color: #6c757d; /* Gray */
    color: #fff; /* White text */
}

.badge-ready {
    background-color: #17a2b8; /* Blue */
    color: #fff;
}

.badge-completed {
    background-color: #28a745; /* Green */
    color: #fff;
}

.badge-cancelled {
    background-color: #dc3545; /* Red */
    color: #fff;
}

    </style>

    <?php
    // Fetch all categories and their respective product quantities
    $all_categories = find_all('categories');
    $category_stock = [];

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
</head>

<body>
    <div class="main-wrapper">
        <?php include 'layouts/menu.php'; ?>

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">E-store Management</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin.php">Inventory</a></li>
                                <li class="breadcrumb-item active">E-store</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Display messages -->
                <div class="row">
                    <div class="col-md-12">
                        <?php echo display_msg($msg); ?>
                    </div>
                </div>

                <div class="row">
                    <!-- Product Panel -->
                    <div class="col-md-3">
                        <a href="javascript:void(0);" id="viewProductBtn" style="color:black;">
                            <div class="panel panel-box clearfix" style="height: 100px;">
                                <div class="panel-icon pull-left bg-blue2" style="max-height:100px; display:center;">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="panel-value pull-right">
                                    <h2 class="margin-top"></h2>
                                    <p class="text-title">View Product</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Product Panel -->
                    <div class="col-md-3">
                        <a href="javascript:void(0);" id="viewPendingBtn" style="color:black;">
                            <div class="panel panel-box clearfix" style="height: 100px;">
                                <div class="panel-icon pull-left bg-red" style="max-height:100px; display:center;">
                                    <i class="fa fa-table"></i>
                                </div>
                                <div class="panel-value pull-right">
                                    <h2 class="margin-top"></h2>
                                    <p class="text-title">View Customer Order</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Table to show all products (initially hidden) -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table custom-table datatable" id="productTable1">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Product Name</th>
                                    <th>Item Code</th>
                                    <th>Price</th>
                                    <th>Stock Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch products to display in the online store
                                $products = join_product_table();
                                foreach ($products as $product):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['categorie']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['item_code']); ?></td>
                                    <td><?php echo '₱' . number_format($product['sale_price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Admin Order Table (Initially hidden) -->
                <div class="panel-body" id="adminOrderTable"style="width: 100%;">
                    <div class="table-responsive" >
                        <table class="table custom-table datatable">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Order Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch orders for admin to view
                                $orders = get_all_orders();
                                foreach ($orders as $order):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['member_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo number_format($order['quantity'], 2); ?></td>
                                    <td>
    <span class="badge badge-pill 
        <?php 
            switch ($order['order_status']) {
                case 'Pending':
                    echo 'badge-warning'; // Yellow for Pending
                    break;
                case 'On Hold':
                    echo 'badge-secondary'; // Gray for On Hold
                    break;
                case 'Ready for Pick-up':
                    echo 'badge-info'; // Blue for Ready for Pick-up
                    break;
                case 'Completed':
                    echo 'badge-success'; // Green for Completed
                    break;
                case 'Cancelled':
                    echo 'badge-danger'; // Red for Cancelled
                    break;
                default:
                    echo 'badge-light'; // Default color
            }
        ?>">
        <?php echo htmlspecialchars($order['order_status']); ?>
    </span>
</td>

                                    
                                    <td>
                                    <button class="btn btn-primary" 
        data-toggle="modal" 
        data-target="#orderStatusModal" 
        data-order-id="<?php echo $order['order_id']; ?>" 
        data-current-status="<?php echo $order['order_status']; ?>">
    Change Status
</button>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- Page Content -->

        </div>
        <!-- Page Wrapper -->

    </div>
    <!-- Main Wrapper -->
     <!-- Modal -->
<div class="modal " id="orderStatusModal" tabindex="-1" role="dialog" aria-labelledby="orderStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderStatusModalLabel">Update Order Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="store.php">
          <input type="hidden" name="order_id" id="order_id">
          <div class="form-group">
            <label for="order_status">Select Order Status:</label>
            <select name="order_status" id="order_status" class="form-control">
              <option value="Pending">Pending</option>
              <option value="On Hold">On Hold</option>
              <option value="Ready for Pick-up">Ready for Pick-up</option>
              <option value="Completed">Completed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>
          <button type="submit" name="status" class="btn btn-success">Update Status</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
