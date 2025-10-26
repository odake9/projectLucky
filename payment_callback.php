<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("❌ DB Connection failed: " . $conn->connect_error);

if (isset($_GET['billcode']) && isset($_GET['status_id'])) {
    $billCode = $_GET['billcode'];
    $statusId = $_GET['status_id'];

    $response = file_get_contents("https://toyyibpay.com/index.php/api/getBillTransactions?billCode=" . $billCode);
    $data = json_decode($response, true);

    if (!empty($data) && isset($data[0]['billExternalReferenceNo'])) {
        $order_id = $data[0]['billExternalReferenceNo'];
        $amount = $data[0]['billAmount'] / 100;
        $payer = $data[0]['billTo'];
        $email = $data[0]['billEmail'];

        if ($statusId == 1) {
            $conn->query("UPDATE orders SET status='Paid' WHERE order_id='$order_id'");
            echo "<h2>✅ Payment Successful!</h2>";
            echo "<p>Order ID: <b>$order_id</b></p>";
            echo "<p>Amount Paid: <b>RM $amount</b></p>";
            echo "<p>Thank you, <b>$payer</b>. A receipt will be sent to <b>$email</b>.</p>";
            echo "<a href='https://www.luckymilktea.xyz/index.html' style='background:#d9bca3;color:white;padding:10px 20px;border-radius:10px;text-decoration:none;'>🏠 Back to Home</a>";

            echo "<script>localStorage.removeItem('cart');</script>";
        } else {
            echo "<h2>❌ Payment Failed or Cancelled.</h2><a href='https://www.luckymilktea.xyz/payment.html'>Try Again</a>";
        }
    } else {
        echo "<h2>❌ Invalid Bill Code or No Transaction Found.</h2>";
    }
} else {
    echo "<h2>❌ Payment Failed or Invalid Order ID.</h2>";
}

$conn->close();
?>
