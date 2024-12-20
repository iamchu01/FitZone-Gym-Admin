<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - HRMS admin template</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
    

    <!-- Ensure Bootstrap CSS and jQuery are included -->
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
 <?php 
 $payments = find_all('pos_transaction');
 ?>

    
</head>
<body>
 
        <?php include 'layouts/menu.php'; ?>
        <div class="main-wrapper" >
        <!-- Page Wrapper -->
        <div class="page-wrapper" style="padding-top: 2%;">

            <!-- Page Content -->
            <div class="content container-fluid" >

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Payments Reports</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active ">payments reports</li>
                            </ul>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>Cash Payments</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th> 
                                            <th>Transaction Date</th> 
                                            <th>Payments cash</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list">
                                        <?php foreach ($payments as $pay): ?>                                          
                                            <tr>
                                                <td><?php echo $pay['id']; ?></td>
                                                <td><?php echo $pay['transaction_date']; ?></td>
                                                <td><?php echo $pay['total_amount']; ?></td>
                      
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       
                    </div>
                </div>


                <?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

</body>
</html>
