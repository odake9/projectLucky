<?php
$curl = curl_init();

// Get total from payment.html (default 0 if not passed)
$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
$billAmount = $total * 100; // ToyyibPay expects cents

$data = array(
    'userSecretKey'=>'kv5qqn3j-8e2w-s4u9-mn23-wg4ykvulqe0q',
    'categoryCode'=>'dvqe44uj',
    'billName'=>'LuckyMilkTea',
    'billDescription'=>'Milk Tea Order',
    'billPriceSetting'=>1,
    'billPayorInfo'=>1,
    'billAmount'=>$billAmount,
    'billReturnUrl'=>'https://overexuberant-solomon-overthinly.ngrok-free.dev/projectLucky/payment_success.php',
    'billCallbackUrl'=>'https://overexuberant-solomon-overthinly.ngrok-free.dev/projectLucky/payment_callback.php',
    'billExternalReferenceNo'=>uniqid(), // unique reference
    'billTo'=>'LAU YI JIE',
    'billEmail'=>'lauyijie0259@email.com',
    'billPhone'=>'0178790259'
);

curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill'); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($curl);
curl_close($curl);

$obj = json_decode($result, true);
if (isset($obj[0]['BillCode'])) {
    header("Location: https://toyyibpay.com/".$obj[0]['BillCode']);
    exit();
} else {
    echo "Error creating bill: " . $result;
}
?>
