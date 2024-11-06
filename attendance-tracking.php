<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Attendance Tracking - Fit Zone</title>

    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>

     <style>
        /* Styling the attendance summary section */


/* Filter section */
.filter-row {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filter-row .form-control {
    height: 40px;
    border-radius: 6px;
    padding-left: 15px;
}

.search-box {
    display: flex;
    align-items: center;
    position: relative;
}

.search-box .form-control {
    width: 100%;
    padding-left: 20px;
    padding-right: 45px;
    border-radius: 6px;
}

.search-box .search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: none;
    color: #333;
    cursor: pointer;
}

.search-box .search-btn:hover {
    color: #007bff;
}

/* Wrapper for the dropdown with the icon */
.dropdown-wrapper {
    position: relative;
}

.dropdown-wrapper select {
    width: 100%;
    padding-right: 35px; /* To make space for the icon */
}

.dropdown-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none; /* So the icon doesn't interfere with dropdown interaction */
    color: #333;
}


.filter-row .form-label {
    margin-bottom: 8px;
    font-weight: bold;
}

.filter-row .btn {
    height: 40px;
    border-radius: 6px;
}

/* Filter Section */


        .attendance-summary {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-item {
            flex: 1;
            padding: 10px;
            text-align: center;
        }

        .summary-item h4 {
            font-size: 22px;
            color: #4a4a4a;
            margin-bottom: 5px;
        }

        .summary-item p {
            font-size: 18px;
            color: #6c757d;
            font-weight: 500;
        }

        .custom-checkin-btn {
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            margin-left: 10px;
        }

        /* Video scanner for QR */
        #scanner-container video {
            border: 2px solid #ccc;
            border-radius: 10px;
            width: 300px;
            height: 200px;
        }

        #scanner-container {
            display: none;
            text-align: center;
        }

        /* Styling the table */
        .table-attendance {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #f1f1f1;
            color: #333;
            font-weight: 600;
            text-align: left;
        }

        .table-hover tbody tr:hover {
            background-color: #f9f9f9;
        }

        .table tbody tr td {
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }

        .status-present {
            background-color: #28a745;
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .status-absent {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
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
                        <h3 class="page-title">Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Attendance Summary -->
            <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="card dash-widget">
                                <div class="card-body">
                                    <span class="dash-widget-icon dash-widget-icon-user"> <i class="fas fa-users"></i></span>
                                    <div class="dash-widget-info"> 
                                        <h3>112</h3>
                                        <span>Total Active Members</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="card dash-widget">
                                <div class="card-body">
                                    <span class="dash-widget-icon"> <i class="fa fa-check"></i> </span>
                                    <div class="dash-widget-info">
                                        <h3>44</h3>
                                        <span>Present Today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="card dash-widget">
                                <div class="card-body">
                                    <span class="dash-widget-icon"><i class="fa fa-times"></i></span>
                                    <div class="dash-widget-info">
                                        <h3>37</h3>
                                        <span>Absent Today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="card dash-widget">
                                <div class="card-body">
                                    <span class="dash-widget-icon"><i class="fa fa-qrcode"></i></span>
                                     <button id="checkin-btn" class="btn btn-primary custom-checkin-btn">Check In with QR</button>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- /Attendance Summary -->

            <!-- QR Scanner -->
            <div id="scanner-container">
                <video id="camera-stream"></video>
            </div>
            <!-- /QR Scanner -->
            

            <!-- Search Filter -->
                    <div class="row filter-row">
                <h3>Member Search</h3>
                
                <!-- Search by ID -->
                <div class="col-md-3">
                    <label for="member-id" class="form-label">Search by ID:</label>
                    <div class="search-box">
                        <input type="text" id="member-id" class="form-control" placeholder="Enter ID">
                        <button class="btn search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Search by Name -->
                <div class="col-md-3">
                    <label for="full-name" class="form-label">Search by Name:</label>
                    <div class="search-box">
                        <input type="text" id="full-name" class="form-control" placeholder="Enter Full Name">
                        <button class="btn search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="col-sm-2">
                    <label for="start-date" class="form-label">Start Date:</label>
                    <input type="date" id="start-date" class="form-control">
                </div>

                <!-- End Date -->
                <div class="col-sm-2">
                    <label for="end-date" class="form-label">End Date:</label>
                    <input type="date" id="end-date" class="form-control">
                </div>

                <!-- Status Dropdown -->
            <div class="col-md-2">
                <label for="status-select" class="form-label">Status:</label>
                <div class="dropdown-wrapper">
                    <select id="status-select" class="form-control">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                    </select>
                    <!-- Dropdown Icon -->
                    <i class="bi bi-caret-down-fill dropdown-icon"></i>
                </div>
            </div>
            </div>
            <!-- /Search Filter -->

            <!-- Attendance Table -->
            <div class="table-responsive table-attendance">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>1 Sept 2024</td>
                            <td><span class="status-present">Present</span></td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>1 Sept 2024</td>
                            <td><span class="status-absent">Absent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /Attendance Table -->

        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

</div>
<!-- end main wrapper-->

<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>

<!-- JAVASCRIPT -->
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
    // Toggle the camera
    document.getElementById('checkin-btn').addEventListener('click', function () {
        document.getElementById('scanner-container').style.display = 'block';
        let videoElement = document.getElementById('camera-stream');
        let scanner = new Instascan.Scanner({ video: videoElement });
        
        scanner.addListener('scan', function (content) {
            alert('QR code scanned: ' + content); 
            // Process attendance based on scanned QR content
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    });
</script>

</body>

</html>
