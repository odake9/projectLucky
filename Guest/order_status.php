<?php
// ===== DB Connection =====
$host = "localhost";
$user = "root";
$pass = "";
$db   = "milk_tea_shop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    die("⚠️ Invalid order ID.");
}

// Fetch order
$orderRes = $conn->query("SELECT * FROM orders WHERE order_id=$order_id");
if ($orderRes->num_rows == 0) {
    die("⚠️ Order not found.");
}
$order = $orderRes->fetch_assoc();

// Fetch items
$itemsRes = $conn->query("SELECT * FROM order_items WHERE order_id=$order_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Status</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <style>
    body { font-family:"Poppins", sans-serif; background:#fdf6ec; margin:0; padding:20px; }
    .status-box { max-width:700px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); }
    h2 { text-align:center; color:#6d4c41; }
    .status { font-size:20px; text-align:center; margin:20px 0; }
    .progress { display:flex; justify-content:space-between; margin:30px 0; }
    .step { flex:1; text-align:center; position:relative; }
    .step:before { content:""; width:100%; height:4px; background:#ddd; position:absolute; top:15px; left:-50%; z-index:-1; }
    .step:first-child:before { display:none; }
    .circle { width:30px; height:30px; border-radius:50%; background:#ddd; margin:auto; line-height:30px; color:#fff; }
    .active .circle { background:#f4a261; }
    table { width:100%; margin-top:20px; border-collapse:collapse; }
    th,td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
    th { background:#f4a261; color:#fff; }
  </style>
</head>
<body>

<div class="status-box">
  <h2>📦 Order #<?= $order['order_id'] ?></h2>
  <p class="status">Current Status: <b id="status"><?= $order['status'] ?></b></p>

  <!-- Progress Tracker -->
  <div class="progress">
    <div class="step <?= ($order['status']=="Pending" || $order['status']=="Preparing" || $order['status']=="Ready" || $order['status']=="Completed") ? 'active':'' ?>">
      <div class="circle">1</div>
      <p>Pending</p>
    </div>
    <div class="step <?= ($order['status']=="Preparing" || $order['status']=="Ready" || $order['status']=="Completed") ? 'active':'' ?>">
      <div class="circle">2</div>
      <p>Preparing</p>
    </div>
    <div class="step <?= ($order['status']=="Ready" || $order['status']=="Completed") ? 'active':'' ?>">
      <div class="circle">3</div>
      <p>Ready</p>
    </div>
    <div class="step <?= ($order['status']=="Completed") ? 'active':'' ?>">
      <div class="circle">4</div>
      <p>Completed</p>
    </div>
  </div>

  <!-- Items -->
  <table>
    <tr>
      <th>Item</th>
      <th>Qty</th>
      <th>Price (RM)</th>
      <th>Subtotal</th>
    </tr>
    <?php while ($item = $itemsRes->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= $item['qty'] ?></td>
        <td><?= number_format($item['price'],2) ?></td>
        <td><?= number_format($item['qty'] * $item['price'],2) ?></td>
      </tr>
    <?php endwhile; ?>
    <tr>
      <td colspan="3"><b>Total</b></td>
      <td><b>RM <?= number_format($order['total'],2) ?></b></td>
    </tr>
  </table>
</div>

<!-- Auto-refresh status -->
<script>
function refreshStatus() {
  fetch("order_status_api.php?order_id=<?= $order_id ?>")
    .then(res => res.json())
    .then(data => {
      document.getElementById("status").textContent = data.status;
      // reload page to update progress tracker
      if (data.status !== "<?= $order['status'] ?>") {
        location.reload();
      }
    });
}
setInterval(refreshStatus, 5000); // refresh every 5s
</script>

</body>
</html>
<?php $conn->close(); ?>
