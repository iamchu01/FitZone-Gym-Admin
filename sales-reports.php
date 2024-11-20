<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php require_once('vincludes/load.php'); ?>
<head>

    <title>Sales Reports - HRMS admin template</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php 
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    $transaction = find_by_id('pos_transaction', 'id');
    if (!$transaction) {
        echo json_encode(["error" => "Transaction not found"]);
        exit;
    }
    
    $transaction_items = find_by_sql("SELECT * FROM pos_transaction_items WHERE transaction_id = '$transaction'");
    if (!$transaction_items) {
        echo json_encode(["error" => "Transaction items not found"]);
        exit;
    }
    

    // Check if data is valid
    if ($transaction && $transaction_items) {
        // Send data as JSON
        $response = [
            "transaction" => $transaction[0],  // Assuming `find_by_id` returns an array with the first element
            "items" => $transaction_items
        ];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => "Transaction not found"]);
    }echo json_encode($response);
    exit;
    
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
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Sales Reports</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Sales Reports</li>
                            </ul>
                        </div> 
                    </div>
                </div>

                <!-- Sales Metrics -->
                <div class="row">
                    <!-- Sales Growth -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">Sales Growth</h5>
                                    <p class="mb-0">24% Increase</p>
                                </div>
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Target -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">Sales Target</h5>
                                    <p class="mb-0">75% Achieved</p>
                                </div>
                                <i class="fas fa-bullseye fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Lead Conversion -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">Lead Conversion</h5>
                                    <p class="mb-0">12% Conversion Rate</p>
                                </div>
                                <i class="fas fa-exchange-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Acquisition -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">Customer Acquisition</h5>
                                    <p class="mb-0">150 New Customers</p>
                                </div>
                                <i class="fas fa-user-plus fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Performance Chart -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Sales Performance</h5>
                                <canvas id="salesPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="panel-title">POS Transactions</h5>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dateFilterModal">
                Filter by Date
            </button>
        </div>
       
    </div>

    <!-- Display the selected date range -->
    <div class="panel-body">
    <?php
// Get date filter from URL if set
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$date_preset = isset($_GET['date_preset']) ? $_GET['date_preset'] : '';

// Date presets logic
if ($date_preset) {
    switch ($date_preset) {
        case 'today':
            // Set both 'from' and 'to' dates to today
            $date_from = date('Y-m-d', strtotime('today midnight'));

            $date_to = date('Y-m-d', strtotime('tomorrow midnight'));
            break;
        case 'this_week':
            // Get the start and end of this week (Monday to Sunday)
            $date_from = date('Y-m-d', strtotime('monday this week'));
            $date_to = date('Y-m-d', strtotime('sunday this week'));
            break;
        case 'this_month':
            // Get the first and last day of the current month
            $date_from = date('Y-m-01');
            $date_to = date('Y-m-t');
            break;
    }
}

// Get transactions from the database
$get_transact = find_all('pos_transaction');


?>

        <?php
        // Display the date range if both 'from' and 'to' dates are provided
        if (!empty($date_from) && !empty($date_to)) {
            echo "<p class='text-center'>Showing POS transactions from <strong>" . htmlspecialchars($date_from) . "</strong> to <strong>" . htmlspecialchars($date_to) . "</strong>.</p>";
        }
        ?>
        <div class="table-responsive">
    <table class="table custom-table datatable">
        <thead>
            <tr>
                <th class="text-center" style="width: 20%;">Transaction ID</th>
                <th class="text-center" style="width: 10%;">Date</th>
                <th class="text-center" style="width: 10%;">Discount</th>
                <th class="text-center" style="width: 10%;">Total Sale</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>

        <?php
            foreach ($get_transact as $tr) {
                $transaction_date = $tr['transaction_date'];
                $discount = $tr['discount'];

                if ((!$date_from || $transaction_date >= $date_from) && (!$date_to || $transaction_date <= $date_to)) {
                    $discount_display = ($discount == '00.00') ? 'None' : remove_junk(ucfirst($discount));
                    
                    echo '<tr class="text-center">';
                    echo '<td>' . remove_junk(ucfirst($tr['id'])) . '</td>';
                    echo '<td>' . date('F j, Y h:i A', strtotime($transaction_date)) . '</td>';
                    echo '<td>' . $discount_display . '</td>';
                    echo '<td>₱' . remove_junk(ucfirst($tr['total_amount'])) . '</td>';
                    echo '<td class="text-center">
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewTransactionModal" 
                        onclick="viewTransaction(\'' . $tr['id'] . '\')">
                        View
                        </a>
                    </td>';             
                    echo '</tr>';
                }
            } // Closing foreach loop
        ?>

        </tbody>
    </table>
</div>

    </div>
</div>
                <!-- Export Buttons -->
                <div class="text-right mt-3">
                
                    <button class="btn btn-primary" onclick="exportPDF()">Export as PDF</button>
                    <button class="btn btn-success" onclick="exportExcel()">Export as Excel</button>
                </div>
            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper -->
    <!-- Date Filter Modal -->
<div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateFilterModalLabel">Filter Transactions by Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date_from">From</label>
                        <input type="date" id="date_from" name="date_from" class="form-control" placeholder="From">
                    </div>
                    <div class="form-group">
                        <label for="date_to">To</label>
                        <input type="date" id="date_to" name="date_to" class="form-control" placeholder="To">
                    </div>

                    <!-- Date Presets -->
                    <div class="form-group">
                        <label for="date_preset">Quick Filter</label>
                        <select class="form-control" id="date_preset" name="date_preset">
                            <option value="">Select Preset</option>
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- View Transaction Modal -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-labelledby="viewTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTransactionModalLabel">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Transaction ID:</strong> <span id="transactionId"></span></p>
                <p><strong>Date:</strong> <span id="transactionDate"></span></p>
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
                    <tbody>
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
                <p><strong>Discount:</strong> <span id="transactionDiscount"></span></p>
                <p><strong>Total Amount Due:</strong> <span id="transactionTotalAmount"></span></p>
                
                <p><strong>Change:</strong> <span id="transactionDetails"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewTransaction(transaction_id) {
    // Show loading message in the modal while fetching data
    document.getElementById('transactionId').textContent = 'Loading...';
    document.getElementById('transactionDate').textContent = 'Loading...';
    document.getElementById('transactionDiscount').textContent = 'Loading...';
    document.getElementById('transactionTotalAmount').textContent = 'Loading...';

    const tbody = document.querySelector('#viewTransactionModal table tbody');
    tbody.innerHTML = ''; // Clear any existing rows
    const loadingRow = document.createElement('tr');
    loadingRow.innerHTML = `<td colspan="4" class="text-center">Loading...</td>`;
    tbody.appendChild(loadingRow);

    // Use fetch to get transaction details
    fetch(`sales-reports.php?id=${transaction_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error fetching data:", data.error);
                document.getElementById('transactionId').textContent = 'Error';
                document.getElementById('transactionDate').textContent = 'Error';
                document.getElementById('transactionDiscount').textContent = 'Error';
                document.getElementById('transactionTotalAmount').textContent = 'Error';
                return;
            }

            // Populate the modal with data
            document.getElementById('transactionId').textContent = data.transaction.id;
            document.getElementById('transactionDate').textContent = data.transaction.transaction_date;
            document.getElementById('transactionDiscount').textContent = data.transaction.discount;
            document.getElementById('transactionTotalAmount').textContent = '₱' + data.transaction.total_amount;

            // Populate the products purchased table
            tbody.innerHTML = ''; // Clear any existing rows
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.product_quantity}</td>
                        <td>₱${item.price}</td>
                        <td>₱${(item.price * item.product_quantity).toFixed(2)}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="4" class="text-center">No items found for this transaction.</td>`;
                tbody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error fetching transaction details:', error);
            document.getElementById('transactionId').textContent = 'Error';
            document.getElementById('transactionDate').textContent = 'Error';
            document.getElementById('transactionDiscount').textContent = 'Error';
            document.getElementById('transactionTotalAmount').textContent = 'Error';
        });
}

</script>

    <?php include_once('vlayouts/footer.php'); ?>
    <?php include 'layouts/customizer.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>

</body>
</html>
