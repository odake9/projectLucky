<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "milk_tea_shop";
$conn = new mysqli($host, $user, $pass, $db);

$order_id = intval($_GET['order_id']);
$res = $conn->query("SELECT status FROM orders WHERE order_id=$order_id");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode(["status" => $row['status']]);
} else {
    echo json_encode(["status" => "Not Found"]);
}
$conn->close();
?>
