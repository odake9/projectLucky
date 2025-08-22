<?php
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "milktea_db");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

// Read JSON from fetch
$data = json_decode(file_get_contents("php://input"), true);
$cart = $data["cart"] ?? [];

if (empty($cart)) {
    echo json_encode(["success" => false, "message" => "Cart is empty"]);
    exit;
}

// Insert into orders
$conn->query("INSERT INTO orders (order_date, total) VALUES (NOW(), 0)");
$order_id = $conn->insert_id;

$grandTotal = 0;
$stmt = $conn->prepare("INSERT INTO order_items (order_id, name, price, quantity, remark) VALUES (?, ?, ?, ?, ?)");

foreach ($cart as $item) {
    $total = $item["price"] * $item["quantity"];
    $grandTotal += $total;
    $stmt->bind_param("isdiss", $order_id, $item["name"], $item["price"], $item["quantity"], $item["remark"]);
    $stmt->execute();
}

// Update order total
$conn->query("UPDATE orders SET total = $grandTotal WHERE order_id = $order_id");

echo json_encode(["success" => true, "order_id" => $order_id]);
