<?php
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$billcode = isset($_GET['billcode']) ? $_GET['billcode'] : '';

if (!$order_id) {
    die("<h2>❌ Invalid Order ID</h2>");
}

// Fetch order total from DB
$servername = "localhost";
$username = "luckymil_user1";
$password = "050525010259@yijielau";
$dbname = "luckymil_milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("❌ DB error: " . $conn->connect_error);

$result = $conn->query("SELECT total FROM orders WHERE order_id=$order_id");
if ($result->num_rows==0) die("❌ Order not found");
$order = $result->fetch_assoc();
$total = $order['total'];
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Send Receipt</title>
</head>
<body>
<h2>✅ Payment Completed!</h2>
<p>Order ID: <?= $order_id ?></p>
<p>Total: RM <?= number_format($total,2) ?></p>
<p>Enter your Gmail to receive receipt:</p>

<form action="send_receipt.php" method="POST">
    <input type="hidden" name="order_id" value="<?= $order_id ?>">
    <input type="hidden" name="billcode" value="<?= htmlspecialchars($billcode) ?>">
    <input type="hidden" name="total" value="<?= $total ?>">
    <input type="email" name="email" placeholder="Enter Gmail" required>
    <button type="submit">Send Receipt</button>
</form>
</body>
</html>
