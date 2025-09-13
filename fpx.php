<?php
$curl = curl_init();
$data = array(
    'userSecretKey'=>'kv5qqn3j-8e2w-s4u9-mn23-wg4ykvulqe0q',
    'categoryCode'=>'dvqe44uj',
    'billName'=>'LuckyMilkTea',
    'billDescription'=>'Milk Tea Order',
    'billPriceSetting'=>1,
    'billPayorInfo'=>1,
    'billAmount'=>200, // RM2.00
    'billReturnUrl'=>'https://yourwebsite.com/payment_success.php',
    'billCallbackUrl'=>'https://yourwebsite.com/callback.php',
    'billExternalReferenceNo'=>'123456',
    'billTo'=>'ADMIN',
    'billEmail'=>'lauyijie0259@email.com',
    'billPhone'=>'0123456789'
);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($curl);
curl_close($curl);

$obj = json_decode($result, true);
header("Location: https://dev.toyyibpay.com/".$obj[0]['BillCode']);
?>
