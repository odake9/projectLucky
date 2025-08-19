<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "DB error"]));
}

$order_id = $_GET["order_id"] ?? 0;

$order = $conn->query("SELECT * FROM orders WHERE order_id = $order_id")->fetch_assoc();
if (!$order) {
  echo json_encode(["success" => false, "message" => "Order not found"]);
  exit;
}

$result = $conn->query("SELECT name, price, quantity, remark FROM order_items WHERE order_id = $order_id");
$items = [];
while ($row = $result->fetch_assoc()) {
  $items[] = $row;
}

$conn->close();

echo json_encode(["success" => true, "order" => $order, "items" => $items]);
?>
