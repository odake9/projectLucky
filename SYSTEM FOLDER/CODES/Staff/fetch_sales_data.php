<?php
// Hide warnings
error_reporting(0);
ini_set('display_errors', 0);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "
  SELECT 
    o.order_id, 
    o.order_date, 
    o.total AS order_total,
    i.name AS product_name,
    i.price,
    i.quantity
  FROM orders o
  JOIN order_items i ON o.order_id = i.order_id
  ORDER BY o.order_date DESC, o.order_id
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $orderItems = [];
  while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orderItems[$orderId])) {
      $orderItems[$orderId] = [
        'date' => $row['order_date'],
        'total' => $row['order_total'],
        'items' => []
      ];
    }
    $orderItems[$orderId]['items'][] = [
      'name' => $row['product_name'],
      'price' => $row['price'],
      'quantity' => $row['quantity']
    ];
  }

  $grandTotal = 0;

  foreach ($orderItems as $orderId => $data) {
    echo "<div class='order-card'>";
    echo "<div class='order-header'>🧾 Order #{$orderId} — {$data['date']}</div>";
    echo "<ul>";
    foreach ($data['items'] as $item) {
      $subtotal = $item['price'] * $item['quantity'];
      echo "<li><span>{$item['name']} × {$item['quantity']}</span><span>RM " . number_format($subtotal, 2) . "</span></li>";
    }
    echo "</ul>";
    echo "<div class='order-total'>Order Total: RM " . number_format($data['total'], 2) . "</div>";
    echo "</div>";
    $grandTotal += $data['total'];
  }

  echo "<div class='grand-total'>Grand Total: RM " . number_format($grandTotal, 2) . "</div>";

} else {
  echo "<p>No sales records found.</p>";
}

$conn->close();
?>
