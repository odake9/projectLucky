<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch orders + items
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff - View Orders</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(to bottom right, #fffaf5, #f7efe7);
    color: #4b3b2f;
    margin: 0;
    padding: 20px;
  }

  .container {
    max-width: 1100px;
    margin: 0 auto;
  }

  /* Back Button */
  .back-btn {
    display: inline-block;
    background: #d4a373;
    color: white;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 500;
    transition: background 0.3s ease;
    margin-bottom: 15px;
  }
  .back-btn:hover {
    background: #b97a56;
  }

  /* Title */
  h1 {
    text-align: center;
    color: #5a3e2b;
    margin: 25px 0;
    font-size: 2rem;
    font-weight: 600;
  }

  /* Order Card */
  .order-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(90, 62, 43, 0.1);
    padding: 20px 25px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
  }
  .order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(90, 62, 43, 0.15);
  }

  /* Header */
  .order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #f1e0d6;
    padding-bottom: 8px;
    margin-bottom: 12px;
  }
  .order-id {
    font-weight: 600;
    font-size: 1.1rem;
    color: #6b4f4f;
  }
  .order-date {
    font-size: 0.9rem;
    color: #a47148;
  }

  /* Dropdown & Badge */
  .status-section {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  select {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #e0b5a3;
    background: #fff9f6;
    font-size: 0.9rem;
    color: #5a3930;
    transition: border-color 0.3s ease;
  }
  select:focus {
    border-color: #c58f63;
    outline: none;
  }

  .status-badge {
    padding: 6px 10px;
    border-radius: 8px;
    color: #fff;
    font-weight: 500;
    font-size: 0.85em;
  }
  .Pending {
    background: #ffb347;
  }
  .Completed {
    background: #9fd356;
  }
  .Cancelled {
    background: #f25c54;
  }

  /* Table */
  .order-items {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 0.95rem;
  }
  .order-items th {
    background: #fbe9d0;
    color: #5a3e2b;
    text-align: left;
    padding: 10px;
  }
  .order-items td {
    padding: 8px 10px;
    border-bottom: 1px solid #f2d6b3;
  }
  .order-items tr:nth-child(even) {
    background: #fff8f5;
  }

  /* Total Row */
  .order-items tr:last-child {
    background: #fcebd4;
    font-weight: 600;
  }

  .msg {
    font-size: 0.85em;
    margin-left: 6px;
  }

  /* Center "No Orders" Message */
  .empty {
    text-align: center;
    color: #9b8b7a;
    font-style: italic;
    margin-top: 40px;
  }
</style>
</head>
<body>

<div class="container">
  <a href="staff.php" class="back-btn">← Back to Dashboard</a>
  <h1>Admin - View Orders</h1>

  <div id="orderContainer">
    <?php if (empty($orders)): ?>
      <p class="empty">No orders found.</p>
    <?php else: ?>
      <?php foreach ($orders as $id => $order): ?>
        <div class="order-card" id="order-<?= $id ?>">
          <div class="order-header">
            <div>
              <div class="order-id">Order #<?= $id ?></div>
              <div class="order-date"><?= $order['order_date'] ?></div>
            </div>
            <div class="status-section">
              <select class="status-select" data-id="<?= $id ?>">
                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
              </select>
              <span class="status-badge <?= htmlspecialchars($order['status']) ?>" id="badge-<?= $id ?>">
                <?= htmlspecialchars($order['status']) ?>
              </span>
              <span class="msg" id="msg-<?= $id ?>"></span>
            </div>
          </div>

          <table class="order-items">
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
            <tr>
              <td colspan="3" style="text-align:right;">Total:</td>
              <td colspan="2">RM <?= number_format($order['total'], 2) ?></td>
            </tr>
          </table>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
document.querySelectorAll('.status-select').forEach(select => {
  select.addEventListener('change', function() {
    const orderId = this.dataset.id;
    const newStatus = this.value;

    fetch('update_status.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'order_id=' + orderId + '&status=' + encodeURIComponent(newStatus)
    })
    .then(res => res.json())
    .then(data => {
      const badge = document.getElementById('badge-' + orderId);
      const msg = document.getElementById('msg-' + orderId);
      if (data.success) {
        badge.textContent = newStatus;
        badge.className = 'status-badge ' + newStatus;
        msg.style.color = 'green';
        msg.textContent = '✓ Updated!';
        setTimeout(() => msg.textContent = '', 1500);
      } else {
        msg.style.color = 'red';
        msg.textContent = '⚠️ Failed';
      }
    })
    .catch(() => {
      const msg = document.getElementById('msg-' + orderId);
      msg.style.color = 'red';
      msg.textContent = '⚠️ Error updating';
    });
  });
});
</script>

</body>
</html>
