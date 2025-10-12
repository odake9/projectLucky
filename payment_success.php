<?php
$status = isset($_GET['status']) ? $_GET['status'] : null;
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : uniqid('ORDER_');
$total = isset($_GET['total']) ? $_GET['total'] : 0;
$billcode = isset($_GET['billcode']) ? $_GET['billcode'] : null;

if ($status != 1) {
    echo "<h2>❌ Payment Failed</h2>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Successful</title>
  <style>
    body {
      font-family: Poppins, sans-serif;
      text-align: center;
      background-color: #f5f5f5;
      margin-top: 100px;
    }
    .container {
      background: white;
      width: 400px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    input {
      width: 80%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>✅ Payment Successful</h2>
    <p>Order ID: <b><?= htmlspecialchars($order_id) ?></b></p>
    <p>Bill Code: <b><?= htmlspecialchars($billcode) ?></b></p>
    <p>Total: <b>RM <?= number_format($total, 2) ?></b></p>
    <p>Please enter your email to receive the receipt:</p>

    <form action="send_receipt.php" method="POST">
      <input type="hidden" name="order_id" value="<?= $order_id ?>">
      <input type="hidden" name="total" value="<?= $total ?>">
      <input type="hidden" name="billcode" value="<?= $billcode ?>">
      <input type="email" name="email" placeholder="Enter your Gmail" required><br>
      <button type="submit">Send Receipt</button>
    </form>
  </div>
</body>
</html>
