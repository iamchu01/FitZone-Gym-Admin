<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Payments - HRMS admin template</title>

    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>

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
                                <h3 class="page-title">Payments</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Payments</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">  
                                <table class="table table-striped custom-table datatable mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Client</th>
                                            <th>Payment Type</th>
                                            <th>Paid Date</th>
                                            <th>Paid Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#INV-0001</td>
                                            <td>
                                                <h2>Global Technologies</h2>
                                            </td>
                                            <td>Paypal</td>
                                            <td>8 Feb 2019</td>
                                            <td>$500</td>
                                        </tr>
                                        <tr>
                                            <td>#INV-0002</td>
                                            <td>
                                                <h2>Global Technologies</h2>
                                            </td>
                                            <td>Paypal</td>
                                            <td>8 Feb 2019</td>
                                            <td>$500</td>
                                        </tr>
                                        <tr>
                                            <td>#INV-0003</td>
                                            <td>
                                                <h2>Global Technologies</h2>
                                            </td>
                                            <td>Paypal</td>
                                            <td>8 Feb 2019</td>
                                            <td>$500</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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