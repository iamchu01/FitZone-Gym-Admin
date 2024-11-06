<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
</head> 
<script type="text/javascript">
function setEditCategory(id, name) {
    document.getElementById('edit_cat_id').value = id;
    document.getElementById('edit_cat_name').value = name;
}
function confirmEdit() {
    return confirm("changing affects all product list Are you sure you want to save these changes?");
  }

  $(document).ready(function() {
    $('#category-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
        });
    });
});


</script>

<?php
  $all_categories = find_all('categories');
// Handle category deletion via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cat'])) {
  $categorie_id = (int)$_POST['id'];
  $categorie = find_by_id('categories', $categorie_id);
  
  if (!$categorie) {
      $session->msg("d", "Missing category id.");
      redirect('categorie.php');
  }

  // Check if the category is used in any products and get total quantity
  $query = "SELECT SUM(quantity) AS total_quantity FROM products WHERE categorie_id = '{$categorie_id}'";
  $result = $db->query($query);
  
  if ($result && $row = $result->fetch_assoc()) {
      $total_quantity = $row['total_quantity'] ? $row['total_quantity'] : 0;

      if ($total_quantity > 0) {
          // If there are products linked to this category, do not delete it
          $session->msg("d", "Cannot delete Product '{$categorie['name']}' as it hasa total quantity of {$total_quantity}.");
          redirect('categorie.php');
      }
  }
  
  // Proceed with deletion if there are no products linked to this category
  $delete_id = delete_by_id('categories', $categorie_id);
  if ($delete_id) {
      $session->msg("s", "product deleted.");
      redirect('categorie.php');
  } else {
      $session->msg("d", "product deletion failed.");
      redirect('categorie.php');
  }
}

// Update Category if 'edit_cat' is posted
if (isset($_POST['edit_cat'])) {
    $cat_id = (int)$_POST['edit_cat_id'];
    $cat_name = remove_junk($db->escape($_POST['categorie-name']));

    if (!empty($cat_name)) {
        $sql = "UPDATE categories SET name='{$cat_name}' WHERE id='{$cat_id}'";
        if ($db->query($sql) && $db->affected_rows() === 1) {
            $session->msg("s", "Successfully updated product");
        } else {
            $session->msg("d", "Failed to update product.");
        }
    } else {
        $session->msg("d", "Product name cannot be empty.");
    }
    redirect('categorie.php', false);
}
$category_stock = [];

// Fetch quantities for each category
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
<?php
if (isset($_POST['add_cat'])) {
    $req_field = array('categorie-name');
    validate_fields($req_field);
    $cat_name = remove_junk($db->escape($_POST['categorie-name']));
    
    // Check for duplicate category name
    $query = "SELECT * FROM categories WHERE name = '{$cat_name}' LIMIT 1";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $session->msg("d", "Product '{$cat_name}' already exists.");
        redirect('categorie.php', false);
    }
    
    if (empty($errors)) {
        $sql  = "INSERT INTO categories (name)";
        $sql .= " VALUES ('{$cat_name}')";
        if ($db->query($sql)) {
            $session->msg("s", "Successfully Added New Product");
            redirect('categorie.php', false);
        } else {
            $session->msg("d", "Sorry Failed to insert.");
            redirect('categorie.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('categorie.php', false);
    }
}
?>


<?php include 'layouts/menu.php'; ?> 
<div class="page-wrapper" style="padding-top:2%;">
    <div class="content container-fluid">
   <div class="row">
      <div class="col"> <h3 class="page-title">Products</h3>
    </div>  
        <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <span class="fa fa-th"></span>
                            <span>Add New Product</span>
                        </strong>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="categorie.php">
                            <div class="form-group">
                                <input type="text" class="form-control" name="categorie-name" placeholder="Product Name" required>
                            </div>
                            <button type="submit" name="add_cat" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
          
            </div>
            <div class="col">
      <div class="dropdown position-absolute top-0 end-0">
  <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" title="Inventory management Navigation bar" data-toggle="tooltip">
    <span class="fa fa-navicon"></span>
  </a>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
  <li><a class="dropdown-item" href="admin.php"><span class="fa fa-home"></span> Inventory Overview</a></li>
    <li><a class="dropdown-item" href="categorie.php"><span class="fa fa-th"></span> Add Product</a></li>
    <li><a class="dropdown-item" href="product.php"><span class="fa fa-shopping-cart"></span> Product Stock List</a></li>
    <li><a class="dropdown-item" href="gym_equipment.php"><span class="fa fa-cubes"></span> Gym equpment</a></li>
    
    <!-- Add more links as needed -->
  </ul>
</div>

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
                    <!-- Actions Dropdown -->
                    <td class="text-center">
                        <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-dot-circle-o text-primary"></i> Actions
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- Edit Button -->
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#editCategoryModal" 
                                    onclick="setEditCategory(<?php echo $cat['id']; ?>, '<?php echo remove_junk(ucfirst($cat['name'])); ?>')">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <!-- Delete Button -->
                                <form action="categorie.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo (int)$cat['id']; ?>">
                                    <button type="submit" name="delete_cat" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this Product?');">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
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
            

            

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editCategoryForm" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_cat_id" id="edit_cat_id">
          <div class="form-group">
            <label for="edit_cat_name">Category Name</label>
            <input type="text" class="form-control" name="categorie-name" id="edit_cat_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="edit_cat" class="btn btn-primary" onclick="return confirmEdit()">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
