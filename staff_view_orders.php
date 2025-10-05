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
<title>Staff - View Orders (AJAX)</title>
<style>
  body {
    font-family: "Poppins", sans-serif;
    background-color: #fff8f0;
    color: #4b3b2f;
    margin: 0;
    padding: 20px;
  }

  .container {
    max-width: 1000px;
    margin: 0 auto;
  }

  /* Back button */
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

  /* Title */
  h1 {
    text-align: center;
    color: #6b4f4f;
    margin: 20px 0;
    font-size: 2rem;
  }

  /* Order card layout */
  .order-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(107, 79, 79, 0.1);
    padding: 20px;
    margin-bottom: 25px;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(107, 79, 79, 0.15);
  }

  /* Header alignment */
  .order-header {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    border-bottom: 2px solid #ffe3d8;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }

  .order-id {
    font-weight: 600;
    font-size: 1.1rem;
    color: #5a3930;
  }

  .order-date {
    font-size: 0.9rem;
    color: #a47148;
    text-align: right;
  }

  /* Dropdown + badge alignment */
  .status-section {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 8px;
  }

  select {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #e0b5a3;
    background: #fff9f6;
    font-size: 0.9rem;
    color: #5a3930;
    transition: border-color 0.3s;
  }
  select:focus {
    border-color: #f4845f;
    outline: none;
  }

  .status-badge {
    padding: 5px 10px;
    border-radius: 8px;
    color: #fff;
    font-weight: 500;
    font-size: 0.9em;
  }
  .Pending {
    background: #ffb347;
  }
  .Completed {
    background: #9fd356;
  }
  .Cancelled {
    background: #f91313ff;
  }

  /* Table */
  .order-items {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    table-layout: fixed;
  }
  .order-items th {
    background: #ffe3d8;
    color: #5a3930;
    text-align: left;
    padding: 10px;
    font-size: 0.95rem;
  }
  .order-items td {
    padding: 8px 10px;
    border-bottom: 1px solid #f8d9c4;
    vertical-align: top;
  }
  .order-items tr:nth-child(even) {
    background: #fff8f5;
  }

  /* Align prices and quantity columns */
  .order-items td:nth-child(2),
  .order-items td:nth-child(3) {
    text-align: right;
  }

  /* Total row */
  .total-row {
    background: #ffefe5;
    font-weight: bold;
    border-top: 2px solid #f6c6a4;
  }

  .msg {
    font-size: 0.85em;
    margin-left: 6px;
  }
</style>

</head>
<body>

<a href="staff.php" class="back-btn">← Back to Dashboard</a>
<h1>🧾 Staff View Orders</h1>

<div id="orderContainer">
<?php if (empty($orders)): ?>
  <p style="text-align:center;color:gray;">No orders found.</p>
<?php else: ?>
  <?php foreach ($orders as $id => $order): ?>
    <div class="order-card" id="order-<?= $id ?>">
      <div class="order-header">
        <div>
          <strong>Order #<?= $id ?></strong><br>
          <small>Date: <?= $order['order_date'] ?></small>
        </div>
        <div>
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
          <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
          <td colspan="2"><strong>RM <?= number_format($order['total'], 2) ?></strong></td>
        </tr>
      </table>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
</div>

<script>
// AJAX status update
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
