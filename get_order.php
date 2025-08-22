<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

$order_id = intval($_GET["order_id"]);

// Order info
$order = $conn->query("SELECT * FROM orders WHERE order_id=$order_id")->fetch_assoc();

// Order items
$result = $conn->query("SELECT * FROM order_items WHERE order_id=$order_id");
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(["order" => $order, "items" => $items]);
