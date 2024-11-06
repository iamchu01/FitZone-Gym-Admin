<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Renewal and Cancellation - Gym Membership</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>

    <style>
.custom-card {
    border: 1px solid #f0f0f0;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.custom-select-renewal, .custom-input-renewal, .custom-textarea-cancellation, .custom-select-cancellation {
    border-color: #e3e3e3;
    box-shadow: none;
    border-radius: 4px;
}

.custom-btn-renew {
    background-color: #48c92f; /* Match your primary button color */
    border-color: #48c92f;
}

.custom-btn-cancel {
    background-color: #f62d51; /* Match your danger button color */
    border-color: #f62d51;
}

    </style>
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
                        <h3 class="page-title">Membership Renewal and Cancellation</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Renewal and Cancellation</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Renewal Form -->
            <div class="card custom-card">
                <div class="card-header">
                    <h4>Renew Membership</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label for="searchMemberInput" class="form-label">Search Member</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="las la-search"></i> <!-- LineAwesome search icon -->
                                </span>
                                <input type="text" class="form-control custom-search-member" id="searchMemberInput" name="search_member" placeholder="Search Member" required>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <label for="renewalPlan" class="form-label">Membership Plan</label>
                                <select class="form-select custom-select-renewal" id="renewalPlan" name="plan" required>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Weekly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="renewalDate" class="form-label">Renewal Date</label>
                                <input type="date" class="form-control custom-input-renewal" id="renewalDate" name="renewal_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amountPaid" class="form-label">Amount Paid</label>
                                <input type="number" class="form-control custom-input-renewal" id="amountPaid" name="amount_paid" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success custom-btn-renew">Renew Membership</button>
                    </form>
                </div>
            </div>

            <!-- Cancellation Form -->
            <div class="card custom-card mt-4">
                <div class="card-header">
                    <h4>Cancel Membership</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-12">
                            <label for="searchMemberInput" class="form-label">Search Member</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="las la-search"></i>
                                </span>
                                <input type="text" class="form-control custom-search-member" id="searchMemberInput" name="search_member" placeholder="Search Member" required>
                            </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="cancellationReason" class="form-label">Reason for Cancellation</label>
                                <textarea class="form-control custom-textarea-cancellation" id="cancellationReason" name="reason" rows="4" placeholder="Provide the reason for cancellation" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger custom-btn-cancel">Cancel Membership</button>
                    </form>
                </div>
            </div>

        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

    <?php include 'layouts/customizer.php'; ?>
    <!-- JAVASCRIPT -->
    <?php include 'layouts/vendor-scripts.php'; ?>
</body>
</html>
