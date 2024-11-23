<?php require_once('vincludes/load.php'); ?>
<?php
// Fetch the order details
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Get the current status of the order
    $order_query = "SELECT order_status FROM orders WHERE order_id = ?";
    $stmt = $db->prepare($order_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_status = $_POST['order_status'];

        // Update the order status
        $update_query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
        $stmt = $db->prepare($update_query);
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();

        // Redirect back to orders page
        header("Location: store.php");
        exit();
    }
}
?>


