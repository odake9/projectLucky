<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "milk_tea_shop"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection failed: " . $conn->connect_error]);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["cart"]) || count($data["cart"]) === 0) {
    echo json_encode(["success" => false, "message" => "Cart is empty"]);
    exit;
}

$remark = $conn->real_escape_string($data["remark"]);
$cart = $data["cart"];
$total = 0;

// Insert order
$conn->query("INSERT INTO orders (remark, created_at) VALUES ('$remark', NOW())");
$order_id = $conn->insert_id;

// Insert items
foreach ($cart as $item) {
    $name = $conn->real_escape_string($item["name"]);
    $price = floatval($item["price"]);
    $qty = intval($item["quantity"] ?? 1);
    $itemRemark = $conn->real_escape_string($item["remark"] ?? "");

    $subtotal = $price * $qty;
    $total += $subtotal;

    $conn->query("INSERT INTO order_items (order_id, name, price, quantity, remark) 
                  VALUES ($order_id, '$name', $price, $qty, '$itemRemark')");
}

// Update order total
$conn->query("UPDATE orders SET total = $total WHERE id = $order_id");

echo json_encode(["success" => true, "message" => "Order saved", "order_id" => $order_id]);
$conn->close();
?>
