<?php
require_once('vincludes/load.php');

// Fetch all batches
$all_batches = find_all('batches');

if (isset($_GET['batch_id'])) {
    $batch_id = $_GET['batch_id'];

    // Query to get the products for the selected batch
    $query = "SELECT * FROM products WHERE batch_id = '{$batch_id}'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        // Start table structure
        echo "<table class='table table-bordered'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Product Name</th>";
        echo "<th>SRP</th>";
        echo "<th>Item Description</th>";
        echo "<th>Item Code</th>";
        echo "<th>Quantity in Stock</th>";
        echo "<th>Action (Select Quantity)</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($product = $result->fetch_assoc()) {
            // Fetch the category name based on the categorie_id from products
            $category_query = "SELECT name FROM categories WHERE id = '{$product['categorie_id']}'";
            $category_result = $db->query($category_query);
            $category = $category_result->fetch_assoc();

            // Table rows for each product
            echo "<tr>";
            echo "<td>" . remove_junk($category['name']) . "</td>";
            echo "<td>$" . $product['sale_price'] . "</td>";
            echo "<td>" . $product['description'] . "</td>";
            echo "<td>" . $product['item_code'] . "</td>";
            echo "<td>" . $product['quantity'] . "</td>";
            echo "<td>";
            echo "<input type='number' name='product_quantity[" . $product['id'] . "]' min='0' max='" . $product['quantity'] . "' placeholder='Enter quantity' class='form-control' oninput='validateQuantity(this, " . $product['quantity'] . ")'>";
            echo "<small class='text-danger' id='error-message-" . $product['id'] . "' style='display:none;'>Quantity cannot exceed " . $product['quantity'] . "</small>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No products found for this batch.</p>";
    }
}
?>

<script>
function validateQuantity(input, maxQuantity) {
    const errorMessage = document.getElementById("error-message-" + input.name.match(/\d+/)[0]);
    
    if (parseInt(input.value) > maxQuantity) {
        input.style.borderColor = "red";
        errorMessage.style.display = "block";
    } else {
        input.style.backgroundColor = "";  // Reset background color if within limit
        errorMessage.style.display = "none";  // Hide error message if within limit
    }
}
</script>
