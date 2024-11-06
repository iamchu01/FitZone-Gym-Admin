<?php include 'layouts/session.php'; ?>
<?php
require_once('vincludes/load.php');

// Check user permission for this page
// page_require_level(2);

$product = find_by_id('products', (int)$_GET['id']);
if (!$product) {
    $session->msg("d", "Missing Product id.");
    redirect('product.php');
}

// Check if the product has a non-zero quantity
if ($product['quantity'] > 0) {
    $session->msg("d", "Cannot delete this product. It has a quantity of {$product['quantity']}.");
    redirect('product.php');
}

// Proceed to delete the product only
$delete_id = delete_by_id('products', (int)$product['id']);
if ($delete_id) {
    $session->msg("s", "Product deleted.");
    redirect('product.php');
} else {
    $session->msg("d", "Product deletion failed.");
    redirect('product.php');
}
?>
