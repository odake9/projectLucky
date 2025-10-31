<?php
// ===== START SESSION =====
session_start();

// ===== SET JSON HEADER =====
header('Content-Type: application/json');

// ===== DATABASE CONNECTION =====
$servername = "localhost";
$username   = "root"; 
$password   = "";   
$dbname     = "milk_tea_shop";    

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// ===== VALIDATE CART DATA =====
if (!isset($_POST['cart']) || !isset($_POST['total'])) {
    echo json_encode(["error" => "Missing cart or total data"]);
    exit;
}

$cart = json_decode($_POST['cart'], true);
$total = floatval($_POST['total']);

if (empty($cart) || $total <= 0) {
    echo json_encode(["error" => "Invalid cart data"]);
    exit;
}

// ===== INSERT ORDER =====
$order_sql = "INSERT INTO orders (order_date, total, status) VALUES (NOW(), '$total', 'Pending')";
if ($conn->query($order_sql) === TRUE) {
    $order_id = $conn->insert_id;

    // ===== INSERT ITEMS INTO order_items TABLE =====
    foreach ($cart as $item) {
        $name = $conn->real_escape_string($item['name']);
        $price = floatval($item['price']);
        $qty = intval($item['quantity']);
        $subtotal = $price * $qty;

        $item_sql = "INSERT INTO order_items (order_id, name, price, quantity, remark)
                     VALUES ('$order_id', '$name', '$price', '$qty', '')";
        $conn->query($item_sql);
    }

    // ===== RETURN JSON WITH ORDER ID =====
    echo json_encode(["order_id" => $order_id]);
} else {
    echo json_encode(["error" => "Failed to create order: " . $conn->error]);
}

$conn->close();
?>
