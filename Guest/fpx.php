<?php
// ===== Enable error display =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===== ToyyibPay Configuration =====
$api_key       = "kv5qqn3j-8e2w-s4u9-mn23-wg4ykvulqe0q"; // your ToyyibPay userSecretKey
$category_code = "dvqe44uj"; // your ToyyibPay categoryCode

// ===== Database Connection =====
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// ===== Get order ID from GET =====
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    die("❌ Invalid Order ID.");
}

// ===== Fetch order from database =====
$result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id");
if ($result->num_rows == 0) {
    die("❌ Order not found in database.");
}
$order = $result->fetch_assoc();
$total = floatval($order['total']);
if ($total <= 0) {
    die("❌ Invalid order amount.");
}

// ===== Set customer info with defaults =====
$customerName  = !empty($order['customer_name']) ? $order['customer_name'] : "Lucky Milk Tea Customer";
$customerEmail = !empty($order['email']) ? $order['email'] : "test@example.com";
$customerPhone = !empty($order['phone']) ? preg_replace('/\D/', '', $order['phone']) : "0123456789";

// ===== Prepare ToyyibPay bill data =====
$data = [
    'userSecretKey'        => $api_key,
    'categoryCode'         => $category_code,
    'billName'             => 'Lucky Milk Tea Order #' . $order_id,
    'billDescription'      => 'Order payment for Lucky Milk Tea',
    'billPriceSetting'     => 1,
    'billPayorInfo'        => 1,
    'billAmount'           => $total * 100, // convert RM to sen
    'billReturnUrl'        => 'https://www.luckymilktea.xyz/payment_success.php?order_id=' . $order_id,
    'billCallbackUrl'      => 'https://www.luckymilktea.xyz/payment_callback.php',
    'billExternalReferenceNo' => $order_id,
    'billTo'               => $customerName,
    'billEmail'            => $customerEmail,
    'billPhone'            => $customerPhone,
    'billSplitPayment'     => 0,
    'billPaymentChannel'   => 0,
    'billDisplayMerchant'  => 1,
    'billContentEmail'     => 'Thank you for your purchase at Lucky Milk Tea!',
    'billChargeToCustomer' => 1
];

// ===== Create bill via ToyyibPay API =====
$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    die("❌ cURL Error: " . curl_error($curl));
}
curl_close($curl);

$response = json_decode($response, true);

// ===== Log response for debugging =====
$logFile = __DIR__ . "/toyyibpay_log.txt";
file_put_contents($logFile, date("Y-m-d H:i:s") . " - " . print_r($response, true) . "\n", FILE_APPEND);

// ===== Check response =====
if (isset($response[0]['BillCode'])) {
    $billCode = $response[0]['BillCode'];
    // Redirect user to ToyyibPay payment page
    header("Location: https://toyyibpay.com/" . $billCode);
    exit;
} else {
    echo "❌ Error creating bill:<br><pre>";
    print_r($response);
    echo "</pre>";
}
?>
