<?php
// (Optional) You can also check ToyyibPay return parameters:
$status_id = $_GET['status_id'] ?? '';
$billcode = $_GET['billcode'] ?? '';
$order_id = $_GET['order_id'] ?? '';

// Example: Only show success message if payment is successful
if ($status_id == 1) {
    $message = "✅ Payment Successful!";
} else {
    $message = "❌ Payment Failed or Cancelled.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="5;url=home.html"> <!-- redirect after 5 seconds -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Result</title>
<style>
    body {
        font-family: "Poppins", sans-serif;
        text-align: center;
        padding: 80px;
        background: #f9fafb;
    }
    .container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: inline-block;
    }
    h1 { color: #28a745; }
    p { font-size: 18px; }
    a {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
    }
    a:hover { background: #0056b3; }
</style>
</head>
<body>
<div class="container">
    <h1><?php echo $message; ?></h1>
    <p>You will be redirected to the home page in 5 seconds.</p>
    <a href="home.html">Go to Home Now</a>
</div>
</body>
</html>
