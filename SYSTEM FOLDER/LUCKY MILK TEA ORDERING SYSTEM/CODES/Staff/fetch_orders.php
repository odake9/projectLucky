<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT o.order_id, o.order_date, o.total, o.status, 
               i.name AS item_name, i.quantity, i.price, i.remark
        FROM orders o
        LEFT JOIN order_items i ON o.order_id = i.order_id
        ORDER BY o.order_date DESC, o.order_id DESC";
$result = $conn->query($sql);

$orders = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $id = $row['order_id'];
    if (!isset($orders[$id])) {
      $orders[$id] = [
        'order_date' => $row['order_date'],
        'total' => $row['total'],
        'status' => $row['status'],
        'items' => []
      ];
    }
    $orders[$id]['items'][] = [
      'name' => $row['item_name'],
      'quantity' => $row['quantity'],
      'price' => $row['price'],
      'remark' => $row['remark']
    ];
  }
}
$conn->close();

if (empty($orders)) {
  echo "<p style='text-align:center;color:gray;'>No orders found.</p>";
} else {
  foreach ($orders as $id => $order) {
    echo "<div class='order-card' id='order-$id'>
            <div class='order-header'>
              <div>
                <strong>Order #$id</strong><br>
                <small>Date: {$order['order_date']}</small>
              </div>
              <div>
                <select class='status-select' data-id='$id'>
                  <option value='Pending'" . ($order['status']=='Pending'?' selected':'') . ">Pending</option>
                  <option value='Completed'" . ($order['status']=='Completed'?' selected':'') . ">Completed</option>
                  <option value='Cancelled'" . ($order['status']=='Cancelled'?' selected':'') . ">Cancelled</option>
                </select>
                <span class='status-badge {$order['status']}' id='badge-$id'>{$order['status']}</span>
                <span class='msg' id='msg-$id'></span>
              </div>
            </div>
            <table class='order-items'>
              <tr><th>Item Name</th><th>Qty</th><th>Price (RM)</th><th>Subtotal (RM)</th><th>Remark</th></tr>";
    foreach ($order['items'] as $it) {
      $subtotal = $it['price'] * $it['quantity'];
      echo "<tr>
              <td>{$it['name']}</td>
              <td>{$it['quantity']}</td>
              <td>" . number_format($it['price'], 2) . "</td>
              <td>" . number_format($subtotal, 2) . "</td>
              <td>{$it['remark']}</td>
            </tr>";
    }
    echo "<tr>
            <td colspan='3' style='text-align:right;'><strong>Total:</strong></td>
            <td colspan='2'><strong>RM " . number_format($order['total'], 2) . "</strong></td>
          </tr>
          </table>
        </div>";
  }
}
?>
