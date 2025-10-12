<?php
// Example callback handler
$data = $_POST;
if ($data['status'] == 1) { // 1 = successful payment
    $orderId = $data['order_id'];
    // Update database: mark order as "Paid"
}
?>
