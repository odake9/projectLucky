<?php
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

$sql = "SELECT o.order_id, o.order_date, o.total,
               i.name, i.price, i.quantity, i.remark
        FROM orders o
        JOIN order_items i ON o.order_id = i.order_id
        ORDER BY o.order_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Orders</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total</th>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Remark</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= $row['order_date'] ?></td>
                <td><?= $row['total'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['remark'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
