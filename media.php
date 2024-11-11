<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard - GYMMS admin</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php require_once('vincludes/load.php'); ?>
    <?php include 'layouts/head-css.php'; ?>
</head> 
<?php $media_files = find_all('media'); ?>
<?php
  if (isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    if ($photo->process_media()) {
        $session->msg('s', 'Photo has been uploaded.');
        redirect('media.php');
    } else {
        $session->msg('d', join($photo->errors));
        redirect('media.php');
    }
  }
?>
<?php include 'layouts/menu.php'; ?> 
    
<div class="page-wrapper" style="padding-top:2%;">
    <div class="content container-fluid">
        <div class="col-md-6">
            <?php echo display_msg($msg); ?>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <span class="fa fa-camera"></span>
                    <span>All Photos</span>
                    <div class="pull-right">
                        <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <input type="file" name="file_upload"  id="file_upload" multiple="multiple" class="btn btn-success"style="display: none;" onchange="previewImage(event)" />
                                        <label for="file_upload" class="btn btn-info" style="margin-left: 10px;">Choose a file</label>
                                      </span>
                                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="text-center mb-3">
                        <img id="preview" src="#" alt="Image Preview" class="img-thumbnail" style="display: none; max-height: 200px;">
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th class="text-center">Photo</th>
                                <th class="text-center">Photo Name</th>
                                <th class="text-center" style="width: 20%;">Photo Type</th>
                                <th class="text-center" style="width: 50px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($media_files as $media_file): ?>
                            <tr class="list-inline">
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td class="text-center">
                                    <img src="uploads/products/<?php echo $media_file['file_name']; ?>" class="img-thumbnail" />
                                </td>
                                <td class="text-center"><?php echo $media_file['file_name']; ?></td>
                                <td class="text-center"><?php echo $media_file['file_type']; ?></td>
                                <td class="text-center">
                                <a href="delete_media.php?id=<?php echo (int)$media_file['id']; ?>" 
   class="btn btn-danger btn-xs" 
   title="Delete"
   onclick="return confirm('Are you sure you want to delete this photo?');">
    <span class="fa fa-trash"></span> 
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

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.style.display = 'none';
        }
    }
</script>

<?php include_once('vlayouts/footer.php'); ?>
<?php include 'layouts/customizer.php'; ?>
<?php include 'layouts/vendor-scripts.php'; ?>
