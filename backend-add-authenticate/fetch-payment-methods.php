<?php
include '../layouts/db-connection.php';


$query = "SELECT method_id, method_name FROM tbl_payment_methods WHERE status = 'active' ORDER BY method_name ASC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo '<li><div class="form-check ms-3">';
    echo '<input class="form-check-input" type="checkbox" name="Payment[]" id="payment_' . htmlspecialchars($row['method_id']) . '" value="' . htmlspecialchars($row['method_id']) . '" onclick="updateSelectedOptions()">';
    echo '<label class="form-check-label" for="payment_' . htmlspecialchars($row['method_id']) . '">' . htmlspecialchars($row['method_name']) . '</label>';
    echo '</div></li>';
  }
} else {
  echo '<li class="text-muted ms-3">No active payment methods available</li>';
}
