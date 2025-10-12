<?php
// Example: payment_success.php

$status = isset($_GET['status']) ? $_GET['status'] : null;
$billcode = isset($_GET['billcode']) ? $_GET['billcode'] : null;
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($status == 1) {
    $paymentStatus = "✅ Payment Successful";
} else {
    $paymentStatus = "❌ Payment Failed";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Receipt - Lucky Milk Tea</title>
  <style>
    body {
      font-family: Poppins, sans-serif;
      background-color: #f7f7f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .receipt {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 400px;
      text-align: center;
    }
    h2 { color: #333; }
    .status { font-size: 18px; margin: 10px 0; }
    .home-btn {
      display: inline-block;
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="receipt">
    <h2>Lucky Milk Tea Receipt</h2>
    <p class="status"><?= $paymentStatus ?></p>
    <p><b>Order ID:</b> <?= htmlspecialchars($order_id) ?></p>
    <p><b>Bill Code:</b> <?= htmlspecialchars($billcode) ?></p>
    <p><b>Date:</b> <?= date("Y-m-d H:i:s") ?></p>
    <hr>
    <p>Thank you for your purchase!</p>
    <a href="home.html" class="home-btn">Back to Home</a>
  </div>
</body>
</html>
