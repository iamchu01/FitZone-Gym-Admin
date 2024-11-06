<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Blank Page - HRMS admin template</title>

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
                        <h3 class="page-title">Sales Reports</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ul>
                    </div>
                </div>
            </div>
<!-- Sales Metrics -->
            <div class="content container-fluid">
                <div class="row">
                    <!-- Sales Growth -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-0">Sales Growth</h5>
                                        <p class="mb-0">24% Increase</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Target -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-0">Sales Target</h5>
                                        <p class="mb-0">75% Achieved</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-bullseye fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lead Conversion -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-0">Lead Conversion</h5>
                                        <p class="mb-0">12% Conversion Rate</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exchange-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Acquisition -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="mb-0">Customer Acquisition</h5>
                                        <p class="mb-0">150 New Customers</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus fa-2x"></i>
                                    </div>
                                </div>
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

                <!-- Sales Data Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Sales Data</h5>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Sales</th>
                                            <th>Revenue</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Product A</td>
                                            <td>150</td>
                                            <td>$2000</td>
                                            <td>2024-09-07</td>
                                        </tr>
                                        <tr>
                                            <td>Product B</td>
                                            <td>120</td>
                                            <td>$1800</td>
                                            <td>2024-09-06</td>
                                        </tr>
                                        <!-- More rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary" onclick="exportPDF()">Export as PDF</button>
                        <button class="btn btn-success" onclick="exportExcel()">Export as Excel</button>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- end main wrapper -->
     


<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>



</body>

</html>