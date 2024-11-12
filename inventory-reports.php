<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <script>
    $(document).ready(function() {    
        $('#category-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#print-button').on('click', function() {
            window.print();
        });
    });
    </script>
    <style>
    @media print {
        /* Hide everything except the table and the header */
        body * {
            visibility: hidden;
        }
        .table, .table *, .print-title {
            visibility: visible;
        }
        body {
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }

        .table-responsive {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            overflow: visible;

        }
    }
    </style>
</head>

<?php
$products = join_product_table();
$all_categories = find_all('categories');
$outreport = find_all('stock_out');
$all_photo = find_all('media');
?>
<?php include 'layouts/menu.php'; ?>    
<body>
   
<div class="page-wrapper" style="padding-top: 2%">
    <div class="content container-fluid">
        <div class="row">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Inventory Reports</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="col">Search Product</div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input type="text" id="category-search" class="form-control" placeholder="Type Product name...">
                            </div>
                        </div>
                        <div class="col text-end">
                            <button id="print-button" class="btn btn-primary">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">   
                        <div class="table-responsive">
                            
                            <table class="table table-bordered datatable" style="width: 100%;">
                            <div class="text-center mb-3 tabletitle print-title"><h3>Stock Out Report</h3></div>
                                <thead>
                                    <tr>                                      
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th class="text-center" style="width: 10%;">Stock out Item</th>
                                        <th class="text-center" style="width: 10%;">Item Description</th>
                                        <th class="text-center" style="width: 10%;">Item Code</th>
                                        <th class="text-center" style="width: 10%;">Quantity</th>
                                        <th class="text-center" style="width: 10%;">Stock out date</th>
                                        <th class="text-center" style="width: 30%;">Reason</th>
                                        
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php if (count($outreport) > 0): ?>
                                        <?php foreach ($outreport as $report): ?>
                                            <tr>
                                                <td class="text-center"><?php echo count_id(); ?></td>                                              
                                               
                                                <td class="text-center"><?php echo remove_junk($report['product_name']); ?></td>
                                                <td class="text-center"><?php echo remove_junk($report['description']); ?></td>
                                                <td class="text-center"><?php echo remove_junk($report['item_code']); ?></td>
                                                <td class="text-center"><?php echo remove_junk($report['quantity']); ?></td>
                                                <td class="text-center"><?php echo read_date($report['date']); ?></td>
                                                <td class="text-center"><?php echo remove_junk($report['reason']); ?></td>
                                               
                                            </tr>  
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No reports found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
