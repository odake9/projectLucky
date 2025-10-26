<?php
// ===== Get order_id and billcode from URL =====
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$billcode = isset($_GET['billcode']) ? $_GET['billcode'] : '';

if (!$order_id) {
    die("<h2>❌ Invalid Order ID</h2>");
}

// ===== Connect to database =====
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("❌ Database connection failed: ".$conn->connect_error);

// ===== Fetch order total =====
$result = $conn->query("SELECT total FROM orders WHERE order_id=$order_id");
if ($result->num_rows == 0) die("<h2>❌ Order not found</h2>");

$order = $result->fetch_assoc();
$total = $order['total'];
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>
<style>
body { font-family: Poppins, sans-serif; text-align:center; background:#f5f5f5; margin-top:100px; }
.container { background:white; width:400px; margin:auto; padding:30px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
input { width:80%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
button { background:#4CAF50; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }
button:hover { background:#45a049; }
</style>
</head>
<body>
<div class="container">
<h2>✅ Payment Successful</h2>
<p>Order ID: <b><?= htmlspecialchars($order_id) ?></b></p>
<p>Bill Code: <b><?= htmlspecialchars($billcode) ?></b></p>
<p>Total: <b>RM <?= number_format($total,2) ?></b></p>
<p>Please enter your email to receive the receipt:</p>

<form action="send_receipt.php" method="POST">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
    <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
    <input type="hidden" name="billcode" value="<?= htmlspecialchars($billcode) ?>">
    <input type="email" name="email" placeholder="Enter your Gmail" required><br>
    <button type="submit">Send Receipt</button>
</form>
</div>
</body>
</html>
