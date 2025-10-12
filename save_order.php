<?php
session_start();

$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;
$user_id = $_SESSION['user_id'] ?? 1; // demo user

if (!empty($cart)) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_name, price, qty, subtotal) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $name = $item['name'];
        $price = floatval($item['price']);
        $qty = intval($item['quantity']);
        $subtotal = $price * $qty;
        $stmt_item->bind_param("isdid", $order_id, $name, $price, $qty, $subtotal);
        $stmt_item->execute();
    }

    echo "Order saved with ID: " . $order_id;
} else {
    echo "Cart is empty.";
}
