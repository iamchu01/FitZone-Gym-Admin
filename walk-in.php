<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Walk-in Checkout - HRMS admin template</title>

    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>

</head>
<?php 
$payment_method = find_all('tbl_payment_methods')
?>
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
                        <h3 class="page-title">Walk-in Checkout</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Walk-in Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Content Starts -->
            <div class="row">
                <div class="col-md-6">
                    <form action="process_walkin.php" method="POST">
                        <!-- Name Input -->
                        <div class="form-group">
                            <label for="customer_name">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name" required>
                        </div>
                        
                        <!-- Payment Type Dropdown -->
                        <div class="form-group">
                                       
                                           
                                            <label for="payment_method">Payment Method</label>
                                                <select class="form-control" name="payment_method" id="payment_method" required>
                                                    <option value="">Select payment method</option>
                                                    <?php foreach ($payment_method as $cat): ?>
                                                        <option value="<?php echo (int)$cat['method_id']; ?>"><?php echo $cat['method_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                           
                        </div>

                        <!-- Customer Type Dropdown -->
                        <div class="form-group">
                            <label for="customer_type">Customer Type</label>
                            <select class="form-control" id="customer_type" name="customer_type" required>
                                <option value="">Select Customer Type</option>
                                <option value="student">Student (60% discount)</option>
                                <option value="senior">Senior (80% discount)</option>
                                <option value="none">None (No discount)</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Confirm Checkout</button>
                    </form>
                </div>
            </div>
            <!-- /Content Ends -->
            
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

</div>
<!-- end main wrapper-->


<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

</body>

</html>
