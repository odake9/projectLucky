<?php
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Example ToyyibPay callback
$order_id = $_POST['order_id'] ?? 0;
$status = $_POST['status'] ?? '';

if ($status === 'paid') {
    $stmt = $conn->prepare("UPDATE orders SET status='paid' WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    echo "Order $order_id marked as paid.";
} else {
    echo "No update.";
}
