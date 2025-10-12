<?php
$data = $_POST;
if ($data['status'] == 1) {
    $orderId = $data['order_id'];
    $amount = $data['amount'];
    $billcode = $data['billcode'];
    $date = date("Y-m-d H:i:s");

    $conn = new mysqli("localhost", "root", "", "milk_tea_shop");
    $conn->query("INSERT INTO payments (order_id, billcode, amount, status, date)
                  VALUES ('$orderId', '$billcode', '$amount', 'SUCCESS', '$date')");
}
?>
