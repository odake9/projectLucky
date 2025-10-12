<?php
error_reporting(0);
ini_set('display_errors', 0);

// ===== DATABASE CONNECTION =====
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ===== VARIABLES =====
$order_id = "";
$orders = [];
$message = "";

// ===== SEARCH BY ORDER ID =====
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
  $order_id = intval($_GET['order_id']);

  $sql = "SELECT o.order_id, o.order_date, o.total, o.status,
                 i.name AS item_name, i.quantity, i.price, i.remark
          FROM orders o
          LEFT JOIN order_items i ON o.order_id = i.order_id
          WHERE o.order_id = ?
          ORDER BY o.order_date DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $result = $stmt->get_result();

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
  } else {
    $message = "⚠️ Order not found. Please check your Order ID.";
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Check My Order - Lucky Milk Tea</title>
<style>
  body {
    font-family: "Poppins", sans-serif;
    background-color: #fff8f0;
    color: #4b3b2f;
    margin: 0;
    padding: 20px;
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
  }

  .back-btn {
    display: inline-block;
    background: #f7b267;
    color: #fff;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 500;
    transition: 0.3s ease;
  }
  .back-btn:hover {
    background: #f4845f;
  }

  h1 {
    text-align: center;
    color: #6b4f4f;
    margin: 20px 0;
  }

  form {
    text-align: center;
    margin-bottom: 30px;
  }
  input[type="number"] {
    padding: 10px;
    width: 200px;
    border: 1px solid #d5b59c;
    border-radius: 8px;
    font-size: 1rem;
  }
  button {
    padding: 10px 18px;
    border: none;
    background: #f7b267;
    color: white;
    font-weight: 500;
    border-radius: 8px;
    margin-left: 5px;
    cursor: pointer;
    transition: 0.3s;
  }
  button:hover {
    background: #f4845f;
  }

  .order-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(107, 79, 79, 0.1);
    padding: 20px;
    margin-bottom: 25px;
  }

  .order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #ffe3d8;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }

  .status-badge {
    padding: 5px 10px;
    border-radius: 8px;
    color: #fff;
    font-weight: 500;
    font-size: 0.9em;
  }
  .Pending { background: #ffb347; }
  .Completed { background: #9fd356; }
  .Cancelled { background: #f91313ff; }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  th {
    background: #ffe3d8;
    color: #5a3930;
    text-align: left;
    padding: 10px;
  }
  td {
    padding: 8px 10px;
    border-bottom: 1px solid #f8d9c4;
  }
  tr:nth-child(even) {
    background: #fff8f5;
  }

  .total-row {
    background: #ffefe5;
    font-weight: bold;
  }

  .message {
    text-align: center;
    color: gray;
    margin-top: 10px;
  }
</style>
</head>
<body>

<a href="home.html" class="back-btn">← Back to Home</a>
<h1>🧾 Check My Order</h1>

<div class="container">
  <form method="GET">
    <label for="order_id">Enter your Order ID:</label><br><br>
    <input type="number" name="order_id" id="order_id" value="<?= htmlspecialchars($order_id) ?>" required>
    <button type="submit">Check</button>
  </form>

  <?php if ($message): ?>
    <p class="message"><?= $message ?></p>
  <?php endif; ?>

  <?php if (!empty($orders)): ?>
    <?php foreach ($orders as $id => $order): ?>
      <div class="order-card">
        <div class="order-header">
          <div>
            <strong>Order #<?= $id ?></strong><br>
            <small>Date: <?= $order['order_date'] ?></small>
          </div>
          <span class="status-badge <?= htmlspecialchars($order['status']) ?>">
            <?= htmlspecialchars($order['status']) ?>
          </span>
        </div>

        <table>
          <tr>
            <th>Item Name</th><th>Qty</th><th>Price (RM)</th><th>Subtotal (RM)</th><th>Remark</th>
          </tr>
          <?php foreach ($order['items'] as $it): 
            $subtotal = $it['price'] * $it['quantity']; ?>
            <tr>
              <td><?= htmlspecialchars($it['name']) ?></td>
              <td><?= $it['quantity'] ?></td>
              <td><?= number_format($it['price'], 2) ?></td>
              <td><?= number_format($subtotal, 2) ?></td>
              <td><?= htmlspecialchars($it['remark']) ?></td>
            </tr>
          <?php endforeach; ?>
          <tr class="total-row">
            <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
            <td colspan="2"><strong>RM <?= number_format($order['total'], 2) ?></strong></td>
          </tr>
        </table>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
