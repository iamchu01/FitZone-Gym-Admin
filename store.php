<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - HRMS admin template</title>
    <?php include 'layouts/head-main.php'; ?>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/session.php'; ?>
    <script>
        $(document).ready(function() {
    $('#category-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
        });
    });
});
    </script>
 <style>
    
  .modal-dialog {
    max-width: 80%; 
    width: 100%;
}


#selectItemsModal .table-responsive {
    width: 100%;
}
.modal .dropdown-menu {
    overflow: visible;
}

#selectItemsModal table {
    width: 100%;
}  
 </style>
<?php
$all_photo = find_all('media');
$products = join_product_table();
$all_categories = find_all('categories');
$category_stock = [];
foreach ($all_categories as $cat) {
    $query = "SELECT SUM(quantity) AS total_quantity FROM products WHERE categorie_id = '{$cat['id']}'";
    $result = $db->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        $category_stock[$cat['id']] = $row['total_quantity'] ? $row['total_quantity'] : 0;
    } else {
        $category_stock[$cat['id']] = 0; // Default to 0 if no products found
    }
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
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">E-store management</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin.php">Inventory</a></li>
                                <li class="breadcrumb-item active ">E-store</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectItemsModal">
                Select Items
            </button>
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
            <div class="panel-body">
            <div class="table-responsive">
            <table class="table custom-table datatable">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Products List</th>
                <th class="text-center" style="width: 100px;">In-Stock</th>
                <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_categories as $cat): ?>
                <tr>
                    <!-- Product ID -->
                    <td class="text-center"><?php echo count_id(); ?></td>
                    
                    <!-- Product Name -->
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center"><?php echo $category_stock[$cat['id']]; ?></td> <!-- Display In-Stock quantity -->
                 
                    <td class="text-center">
                    <a href="?id=<?php echo (int)$product['id'];?>" class="dropdown-item bg-success" title="" data-toggle="tooltip" onclick="return confirm('');">
                                                    <i class="fa fa-add"></i> select
                                                </a>

                   
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
<!-- Modal Structure -->
<div class="modal fade" id="selectItemsModal" tabindex="-1" aria-labelledby="selectItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <div class="col">Search Product</div>     
            <div class="col-md-4">
                <div class="input-group">                                             
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="category-search" class="form-control" placeholder="Type Product name...">
                </div>
            </div>
                <h5 class="modal-title" id="selectItemsModalLabel">Select Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <!-- Table Content -->
                <div class="table-responsive" id="s_items">
                    <table class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px;">#</th>
                                <th class="text-center" style="width: 10%;">Photo</th>
                                <th class="text-center" style="width: 50%;">Name</th>
                                <th class="text-center" style="width: 10%;">Item Code</th>
                                <th class="text-center" style="width: 10%;">In-Stock</th>
                                
                                <th class="text-center" style="width: 10%;">Selling Price</th>
                                <th class="text-center" style="width: 10%;">Expire Date</th>
                                <th class="text-center" style="width: 10%;">Product Batch</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <!-- Table Row Logic -->
                                <tr class="<?php echo $rowClass; ?>">
                                    <td class="text-center"><?php echo count_id(); ?></td>
                                    <td>
                                        <?php if ($product['media_id'] === '0'): ?>
                                            <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                                        <?php else: ?>
                                            <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['item_code']); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                                    <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                    <td class="text-center">
                                        <?php 
                                        if (isset($product['is_perishable']) && $product['is_perishable'] == 0) {
                                            echo 'Non-Perishable';
                                        } elseif (isset($product['expiration_date']) && !empty($product['expiration_date'])) {
                                            echo htmlspecialchars($product['expiration_date']);
                                        } else {
                                            echo 'No expiration date available';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo read_date($product['date']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                        <a href="?id=<?php echo (int)$product['id'];?>" class="dropdown-item bg-success" title="" data-toggle="tooltip" onclick="return confirm('');">
                                                    <i class="fa fa-add"></i> select
                                                </a>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
</body>
</html>
