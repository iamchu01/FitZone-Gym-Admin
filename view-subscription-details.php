<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Subscription Details - HRMS admin template</title>

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
                        <h3 class="page-title">Subscription Details </h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Subscription Details </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
       
            <!-- //*Subscription Info Tab -->
                        <div class="tab-content">
                        <!-- Subscription Info Tab -->
                        <div id="subscription_details" class="pro-overview tab-pane fade show active">
                            <div class="row">
                                <!-- Subscription Overview -->
                                <div class="col-md-6 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h3 class="card-title">Subscription Overview <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_subscription_modal"><i class="fa fa-pencil"></i></a></h3>
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Subscription Name</div>
                                                    <div class="text text-dark"> Basic Plan</div>
                                                </li>
                                                <li>
                                                    <div class="title">Subscription Type</div>
                                                    <div class="text text-dark">Monthly</div>
                                                </li>
                                                <li>
                                                    <div class="title">Duration</div>
                                                    <div class="text text-dark">6 Months</div>
                                                </li>
                                                <li>
                                                    <div class="title">Status</div>
                                                    <div class="text fs-6">
                                                        <span class="badge bg-success">Active</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                        <!-- Pricing and Payment Method -->
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Pricing & Payment Method <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_pricing_modal"><i class="fa fa-pencil"></i></a></h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Pricing</div>
                                            <div class="text text-dark">$750.00</div>
                                        </li>
                                        <li>
                                            <div class="title">Payment Method</div>
                                            <div class="text text-dark">Credit Card</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Members and Features -->
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Members and Features<a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_access_features_modal"><i class="fa fa-pencil"></i></a></h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Members Availed</div>
                                            <div class="text text-dark">120 Members</div>
                                        </li>
                                        <li>
                                            <div class="title">Access Features</div>
                                            <div class="text text-dark">Gym Access, Group Classes, Pool Access</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Description <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_description_modal"><i class="fa fa-pencil"></i></a></h3>
                                    <p>
                                        This membership plan provides unlimited access to all gym facilities, including access to group fitness classes twice a week. Perfect for individuals looking to stay active and committed over the long term.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Terms & Conditions -->
                        <div class="col-md-12 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Terms & Conditions<a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_Term&Conditions_modal"><i class="fa fa-pencil"></i></a></h3>
                                    <p>
                                        By subscribing to this plan, you agree to adhere to all gym policies, including access and usage guidelines. The subscription is non-transferable and non-refundable once activated. Any violations may result in suspension or termination of the subscription.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     <!-- //*Subscription Info Tab -->

        </div>
        <!-- /Page Content -->
        
        <!-- //* Put Modal Here -->
         <!-- //* Put Modal Here -->
        
    </div>
    <!-- /Page Wrapper -->



</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>



</body>

</html>