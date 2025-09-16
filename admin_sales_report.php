<?php
session_start();

// DB connection
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch sales report (orders summary)
$sql = "SELECT order_id, order_date, total FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report</title>
  <style>
    body { font-family: "Poppins", sans-serif; background-color: #fff8f0; padding: 20px; }
    h1 { text-align: center; color: #6b4f4f; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; }
    th { background: #ffcfdf; color: #6b4f4f; }
    tr:hover { background: #ffe5ec; }
    .total { font-weight: bold; font-size: 1.2rem; color: #d46a6a; text-align: right; padding: 10px; }
    .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #ff9aa2; color: #fff; border-radius: 8px; text-decoration: none; }
    .back-btn:hover { background: #ff6f91; }
  </style>
</head>
<body>
  <a href="admin.php" class="back-btn">← Back to Dashboard</a>
  <h1>📊 Sales Report</h1>

  <table>
    <tr>
      <th>Order ID</th>
      <th>Date</th>
      <th>Total (RM)</th>
    </tr>
    <?php
    $grandTotal = 0;
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['order_date']}</td>
                <td>RM " . number_format($row['total'], 2) . "</td>
              </tr>";
        $grandTotal += $row['total'];
      }
    } else {
      echo "<tr><td colspan='3'>No sales records found.</td></tr>";
    }
    ?>
  </table>

  <div class="total">Grand Total: RM <?php echo number_format($grandTotal, 2); ?></div>

</body>
</html>
<?php
$conn->close();
?>
