<?php
// ===== DATABASE CONNECTION =====
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

function safeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error . "<br>SQL: " . $sql);
    }
    return $result;
}

// ===== FETCH DATA =====

// Total staff
$total_staff = safeQuery($conn, "SELECT COUNT(*) AS s FROM users WHERE role = 'staff'")->fetch_assoc()['s'];

// Pending orders
$pending_orders = safeQuery($conn, "SELECT COUNT(*) AS p FROM orders WHERE status = 'Pending'")->fetch_assoc()['p'];

// Completed orders
$completed_orders = safeQuery($conn, "SELECT COUNT(*) AS c FROM orders WHERE status = 'Completed'")->fetch_assoc()['c'];

// ✅ Total sales (sum of price * quantity for all orders)
$total_sales_result = safeQuery($conn, "
    SELECT SUM(price * quantity) AS total_sales
    FROM order_items
");
$total_sales = $total_sales_result->fetch_assoc()['total_sales'] ?? 0;


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard Overview</title>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #fff8f0;
    color: #4b3b2f;
    margin: 0;
    padding: 30px;
  }

  h1 {
    text-align: center;
    color: #5c3b28;
    margin-bottom: 40px;
  }

  .stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    max-width: 1000px;
    margin: 0 auto;
  }

  .stat-box {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    border: 1px solid #f0e2d0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .stat-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.1);
  }

  .stat-box h3 {
    color: #b68c5a;
    font-size: 1.2rem;
  }

  .stat-box p {
    font-size: 1.8rem;
    font-weight: bold;
    color: #4b3b2f;
    margin-top: 10px;
  }

  footer {
    text-align: center;
    margin-top: 50px;
    color: #6b4e35;
    font-size: 14px;
    opacity: 0.8;
  }
</style>
</head>
<body>

<br>

  <div class="stats-container">
    <div class="stat-box">
      <h3>Total Staff</h3>
      <p><?= $total_staff ?></p>
    </div>

    <div class="stat-box">
      <h3>Pending Orders</h3>
      <p><?= $pending_orders ?></p>
    </div>

    <div class="stat-box">
      <h3>Completed Orders</h3>
      <p><?= $completed_orders ?></p>
    </div>

    <div class="stat-box">
      <h3>Total Sales (RM)</h3>
      <p>RM <?= number_format($total_sales, 2) ?></p>
    </div>
  </div>

  <footer>© 2025 Lucky Milk Tea — Real-Time Dashboard</footer>

</body>
</html>
