<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
    <script>
    </script>
    <style>
        .modal-content {
            width: 100%;
            max-width: 100%;
        }
        .modal-body {
            max-height: calc(100vh - 200px); /* Adjust based on your header/footer heights */
            overflow-y: auto; /* Enable scrolling if content exceeds height */
        }
    </style>
</head> 

<?php
function is_about_to_expire($expiration_date) {
    $current_date = new DateTime();
    $expiration_date = new DateTime($expiration_date);
    $interval = $current_date->diff($expiration_date);
    
    // Check if the expiration date is within the next month (31 days)
    return ($interval->days <= 31 && $interval->invert == 0);
}

$products = join_product_table1();
$all_categories = find_all('categories');
$all_photo = find_all('media');
$min_expiration_date = date('Y-m-d', strtotime('+5 months'));
?>

<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Product Inventory</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php">Inventory Management</a></li>
                    <li class="breadcrumb-item active">Inventory Product</li>
                </ul>
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
</div>

<!-- Color Legend Row below the search input -->
<div class="color-legend" style="margin-top: 15px; margin-left: 1%; display: flex; align-items: center;">
    <div style="width: 20px; height: 20px; background-color: #ffc107; margin-right: 10px; border-radius: 3px;"></div>
    <span style="margin-right: 15px;">In Stock: Low (below 10)</span>

    <div style="width: 20px; height: 20px; background-color: #dc3545; margin-right: 10px; border-radius: 3px;"></div>
    <span style="margin-right: 15px;">Out of Stock</span>

    <div class="bg-secondary" style="width: 20px; height: 20px; margin-right: 10px; border-radius: 3px;"></div>
    <span>Soon to expire items (within 1 month)</span>
</div>



                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 30px;">#</th>
                                        <th class="text-center" style="width: 30px;">Product-ID</th>
                                        <th class="text-center" style="width: 10%;">Photo</th>
                                        <th class="text-center" style="width: 10%;">Category</th>
                                        <th class="text-center" style="width: 50%;">Product Name</th>
                                        <th class="text-center" style="width: 10%;">Description</th>
                                        <th class="text-center" style="width: 10%;">Item Code</th>
                                        <th class="text-center" style="width: 10%;">Quantity</th>
                                        <th class="text-center" style="width: 10%;">Buying Price</th>
                                        <th class="text-center" style="width: 10%;">Selling Price</th>
                                        <th class="text-center" style="width: 10%;">Expire Date</th>
                                        <th class="text-center" style="width: 10%;">Product Batch</th> <!-- Added Batch column -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <?php 
                                            // Determine the row class based on quantity and expiration
                                            $rowClass = '';
                                            if ($product['quantity'] == 0) {
                                                $rowClass = 'bg-danger';
                                            } elseif ($product['quantity'] < 10) {
                                                $rowClass = 'bg-warning';
                                            }
                                            // Check if the product is about to expire within one month
                                            if (isset($product['expiration_date']) && is_about_to_expire($product['expiration_date'])) {
                                                $rowClass = 'bg-secondary'; // Override with secondary color if about to expire
                                            }
                                        ?>
                                        <tr class="<?php echo $rowClass; ?>">
                                            <td class="text-center"><?php echo count_id(); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['id']); ?></td>
                                            <td>
                                                <?php if ($product['media_id'] === '0'): ?>
                                                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                                                <?php else: ?>
                                                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="Product Image">
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['name']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['description']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['item_code']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                            <td class="text-center">
                                                    <?php 
                                                    if (!empty($product['expiration_date']) && $product['expiration_date'] !== '0000-00-00') {
                                                        echo remove_junk($product['expiration_date']);
                                                    } else {
                                                        echo 'Non-Perishable';
                                                    }
                                                    ?>
                                                </td>
                                            <td class="text-center">
                                                <?php 
                                                // Check if there are batches for this product
                                                if (isset($product['batch_quantity'])) {
                                                    echo 'Batch Quantity: ' . $product['batch_quantity'] . '<br>';
                                                    echo 'Batch Expiration: ' . (isset($product['batch_expiration']) ? $product['batch_expiration'] : 'N/A') . '<br>';
                                                    echo 'Batch Status: ' . (isset($product['batch_status']) ? $product['batch_status'] : 'N/A');
                                                } else {
                                                    echo 'No batches available';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
