<?php
// ===== DB Connection =====
$host = "localhost";
$user = "root";
$pass = "";
$db   = "milk_tea_shop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Fetch all feedback
$sql = "SELECT * FROM feedback ORDER BY date_submitted DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Feedback - Milk Tea Shop</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #fff8f0;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #8b4513;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #f4a261;
      color: #fff;
    }
    tr:hover {
      background: #f9f9f9;
    }
    .back-btn {
      display: inline-block;
      margin-bottom: 15px;
      text-decoration: none;
      color: #fff;
      background: #e76f51;
      padding: 8px 15px;
      border-radius: 5px;
    }
    .back-btn:hover {
      background: #d65a3b;
    }
  </style>
</head>
<body>
  <a href="admin.php" class="back-btn">🏠 Back to Dashboard</a>
  <h1>Customer Feedback</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Name</th>
      <th>Email</th>
      <th>Message</th>
      <th>Date Submitted</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td><?= $row['date_submitted'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="6" style="text-align:center; color:red;">No feedback found</td>
      </tr>
    <?php endif; ?>
  </table>
</body>
</html>
<?php $conn->close(); ?>
