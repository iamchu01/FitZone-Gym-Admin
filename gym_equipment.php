<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>
    <title>Dashboard - GYYMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; 
  ?>
  <script>
  function openEditModal(equipment) {
    document.getElementById('edit_equipment_id').value = equipment.id;
    document.getElementById('edit_name').value = equipment.name;
    document.getElementById('edit_description').value = equipment.description;
    document.getElementById('edit_brand').value = equipment.brand;
    document.getElementById('edit_quantity').value = equipment.quantity;
    document.getElementById('edit_price').value = equipment.price;
    document.getElementById('edit_price_type').value = equipment.price_type;
    document.getElementById('edit_purchase_date').value = equipment.purchase_date;
    document.getElementById('edit_condition').value = equipment.condition;
    document.getElementById('edit_location').value = equipment.location;
    document.getElementById('edit_maintenance_date').value = equipment.maintenance_date;
    document.getElementById('edit_is_in_use').value = equipment.is_in_use ? "1" : "0";
    
    $('#editGymEquipmentModal').modal('show');
  }
</script>

  </head> 
  <?php
  $all_equipment = find_all('gym_equipment');
  ?>
  
<?php include 'layouts/menu.php';
$path = "fitzone-gym" . DS . "layouts" . DS . "menu.php";
?> 
  <div class="page-wrapper" style="padding-top:2%;">
  <div class="content container-fluid">
  <div class="row">
      <div class="col"> <h3 class="page-title">Gym Equipment List </h3>
    </div>
    <div class="col">
      <div class="dropdown position-absolute top-0 end-0">
  <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" title="Inventory management Navigation bar" data-toggle="tooltip">
    <span class="fa fa-navicon"></span>
  </a>

  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
  <li><a class="dropdown-item" href="admin.php"><span class="fa fa-home"></span> Inventory Overview</a></li>
    <li><a class="dropdown-item" href="categorie.php"><span class="fa fa-th"></span> Add Product</a></li>
    <li><a class="dropdown-item" href="product.php"><span class="fa fa-th-large"></span> Product Stock List</a></li>
    <li><a class="dropdown-item" href="gym_equipment.php"><span class="fa fa-shopping-cart"></span> Store Products</a></li>
    <li><a class="dropdown-item" href="gym_equipment.php"><span class="fa fa-cubes"></span> Gym equipment</a></li>
    
    <!-- Add more links as needed -->
  </ul>
</div>

    </div>
  <div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="fa fa-cubes"></span>
                    <span>Gym Equipment</span>
                </strong>
                <div class="pull-right">
                    <a href="add_gym_equipment.php" class="btn btn-primary">Add Gym Equipment</a>
            </div>
                
            </div>
            
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th> Name </th>
              <th> Description </th>
              <th> Brand </th>
              <th> Quantity </th>
              <th> Price </th>
              <th> Price Type </th>
              <th> Purchase Date </th>
              <th> Condition </th>
              <th> Location </th>
              <th> Maintenance Date </th>
              <th class="text-center"> In Use </th>
              <th class="text-center"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_equipment as $equipment): ?>
              
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td> <?php echo remove_junk($equipment['name']); ?> </td>
              <td> <?php echo remove_junk($equipment['description']); ?> </td>
              <td> <?php echo remove_junk($equipment['brand']); ?> </td>
              <td> <?php echo remove_junk($equipment['quantity']); ?> </td>
              <td> <?php echo remove_junk($equipment['price']); ?> </td>
              <td> <?php echo remove_junk($equipment['price_type']); ?> </td>
              <td> <?php echo read_date($equipment['purchase_date']); ?> </td>
              <td> <?php echo remove_junk($equipment['condition']); ?> </td>
              <td> <?php echo remove_junk($equipment['location']); ?> </td>
              <td> <?php echo read_date($equipment['maintenance_date']); ?> </td>
              <td class="text-center"> <?php echo $equipment['is_in_use'] ? "Yes" : "No"; ?> </td>
              <td class="text-center">
              <div class="btn-group">
              <div class="dropdown action-label">
                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-dot-circle-o text-primary"></i> Actions
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- Edit Button -->
                                <a href="#" class="dropdown-item" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($equipment)); ?>)">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <!-- Delete Button -->
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#" 
                                    id="<?php echo (int)$equipment['id'];?>" class="btn btn-xs btn-danger" title="delete" data-toggle="tooltip">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
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
<!-- Edit Gym Equipment Modal -->
<div class="modal fade" id="editGymEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="editGymEquipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editGymEquipmentModalLabel">Edit Gym Equipment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="update_gym_equipment.php">
        <div class="modal-body">
          <input type="hidden" name="equipment_id" id="edit_equipment_id">

          <div class="form-group">
            <label for="edit_name">Name</label>
            <input type="text" class="form-control" name="name" id="edit_name" required>
          </div>

          <div class="form-group">
            <label for="edit_description">Description</label>
            <input type="text" class="form-control" name="description" id="edit_description" required>
          </div>

          <div class="form-group">
            <label for="edit_brand">Brand</label>
            <input type="text" class="form-control" name="brand" id="edit_brand" required>
          </div>

          <div class="form-group">
            <label for="edit_quantity">Quantity</label>
            <input type="number" class="form-control" name="quantity" id="edit_quantity" required>
          </div>

          <div class="form-group">
            <label for="edit_price">Price</label>
            <input type="number" step="0.01" class="form-control" name="price" id="edit_price" required>
          </div>

          <div class="form-group">
            <label for="edit_price_type">Price Type</label>
            <select class="form-control" name="price_type" id="edit_price_type" required>
              <option value="per_unit">Per Unit</option>
              <option value="all_quantity">For All Quantity</option>
            </select>
          </div>

          <div class="form-group">
            <label for="edit_purchase_date">Purchase Date</label>
            <input type="date" class="form-control" name="purchase_date" id="edit_purchase_date">
          </div>

          <div class="form-group">
            <label for="edit_condition">Condition</label>
            <input type="text" class="form-control" name="condition" id="edit_condition">
          </div>

          <div class="form-group">
            <label for="edit_location">Location</label>
            <input type="text" class="form-control" name="location" id="edit_location">
          </div>

          <div class="form-group">
            <label for="edit_maintenance_date">Maintenance Date</label>
            <input type="date" class="form-control" name="maintenance_date" id="edit_maintenance_date">
          </div>

          <div class="form-group">
            <label for="edit_is_in_use">In Use</label>
            <select class="form-control" name="is_in_use" id="edit_is_in_use">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>



<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
