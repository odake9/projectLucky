<?php
// ===== DEBUG MODE =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===== CORS HEADERS =====
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ===== DATABASE CONNECTION =====
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// ===== READ CART DATA =====
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "message" => "Invalid JSON: " . json_last_error_msg(), "raw" => $rawInput]);
    exit;
}

$cart = $data["cart"] ?? [];

if (empty($cart)) {
    echo json_encode(["success" => false, "message" => "Cart is empty", "raw" => $rawInput]);
    exit;
}

// ===== INSERT INTO ORDERS =====
if (!$conn->query("INSERT INTO orders (order_date, total) VALUES (NOW(), 0)")) {
    echo json_encode(["success" => false, "message" => "Insert order failed: " . $conn->error]);
    exit;
}
$order_id = $conn->insert_id;

// ===== INSERT ORDER ITEMS =====
$grandTotal = 0;
$stmt = $conn->prepare("INSERT INTO order_items (order_id, name, price, quantity, remark) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit;
}

foreach ($cart as $item) {
    $total = $item["price"] * $item["quantity"];
    $grandTotal += $total;

    $stmt->bind_param("isdss", 
        $order_id, 
        $item["name"], 
        $item["price"], 
        $item["quantity"], 
        $item["remark"]
    );

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Insert item failed: " . $stmt->error, "item" => $item]);
        exit;
    }
}

// ===== UPDATE ORDER TOTAL =====
if (!$conn->query("UPDATE orders SET total = $grandTotal WHERE order_id = $order_id")) {
    echo json_encode(["success" => false, "message" => "Update total failed: " . $conn->error]);
    exit;
}

// ===== RESPONSE =====
echo json_encode(["success" => true, "order_id" => $order_id, "total" => $grandTotal]);
