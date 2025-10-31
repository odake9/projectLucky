<?php
// ===== DEBUG MODE =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===== CORS HEADERS =====
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

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

// ===== CHECK ORDER ID =====
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid order_id"]);
    exit;
}

// ===== GET ORDER INFO =====
$orderSql = "SELECT order_id, order_date, total FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    echo json_encode(["success" => false, "message" => "Order not found"]);
    exit;
}

// ===== GET ORDER ITEMS =====
$itemSql = "SELECT name, price, quantity, remark 
            FROM order_items 
            WHERE order_id = ?";
$stmt = $conn->prepare($itemSql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$itemResult = $stmt->get_result();

$items = [];
while ($row = $itemResult->fetch_assoc()) {
    $items[] = $row;
}

// ===== RESPONSE =====
echo json_encode([
    "success" => true,
    "order" => $order,
    "items" => $items
]);
?>
