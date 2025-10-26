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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  /* --- GLOBAL STYLE --- */
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: "Poppins", sans-serif;
    background-color: #faf8f5;
    color: #3c2f2f;
    padding: 40px 20px;
  }

  .container {
    max-width: 900px;
    margin: 0 auto;
  }

  /* --- BACK BUTTON --- */
  .back-btn {
    display: inline-block;
    background: #b68c5a;
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 25px;
    font-weight: 500;
    transition: background 0.3s, transform 0.2s;
  }
  .back-btn:hover {
    background: #a47b48;
    transform: translateY(-2px);
  }

  /* --- PAGE TITLE --- */
  h1 {
    text-align: center;
    color: #5e4b3c;
    margin: 30px 0;
    font-size: 2rem;
  }

  /* --- FORM --- */
  form {
    text-align: center;
    margin-bottom: 40px;
  }
  label {
    color: #5e4b3c;
    font-weight: 500;
  }
  input[type="number"] {
    padding: 10px;
    width: 220px;
    border: 1px solid #e5ddd2;
    border-radius: 25px;
    font-size: 1rem;
    outline: none;
    transition: border 0.3s;
  }
  input[type="number"]:focus {
    border-color: #b68c5a;
  }
  button {
    padding: 10px 20px;
    border: none;
    background: #b68c5a;
    color: white;
    font-weight: 500;
    border-radius: 25px;
    margin-left: 8px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
  }
  button:hover {
    background: #a47b48;
    transform: translateY(-2px);
  }

  /* --- ORDER CARD --- */
  .order-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 25px;
    margin-bottom: 30px;
  }

  .order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #f2e6da;
    padding-bottom: 10px;
    margin-bottom: 15px;
  }

  .order-header strong {
    color: #b68c5a;
    font-size: 1.1rem;
  }

  .status-badge {
    padding: 6px 12px;
    border-radius: 25px;
    color: #fff;
    font-weight: 500;
    font-size: 0.9em;
  }
  .Pending { background: #ffb347; }
  .Completed { background: #9fd356; }
  .Cancelled { background: #f91313ff; }

  /* --- TABLE --- */
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th {
    background: #f9f3ec;
    color: #5e4b3c;
    text-align: left;
    padding: 10px;
    border-radius: 6px 6px 0 0;
  }
  td {
    padding: 10px;
    border-bottom: 1px solid #f2e6da;
    color: #6d5c4a;
  }
  tr:nth-child(even) {
    background: #fdfaf7;
  }

  .total-row {
    background: #f9f3ec;
    font-weight: bold;
  }

  /* --- MESSAGE --- */
  .message {
    text-align: center;
    color: #7b6a58;
    margin-top: 15px;
    font-style: italic;
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
