<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'DB connection failed']);
  exit();
}

if (isset($_POST['order_id'], $_POST['status'])) {
  $order_id = intval($_POST['order_id']);
  $status = $conn->real_escape_string($_POST['status']);

  $sql = "UPDATE orders SET status='$status' WHERE order_id=$order_id";
  if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

$conn->close();
?>
