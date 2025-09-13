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

  <!-- W3.CSS Framework -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

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
    .back-btn {
      margin-bottom: 15px;
    }
    .w3-table td, .w3-table th {
      text-align: left;
    }
    .w3-table tr:hover {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <a href="staff.php" class="w3-button w3-red back-btn">🏠 Back to Dashboard</a>

  <h1>Customer Feedback</h1>

  <div class="w3-responsive">
    <table class="w3-table-all w3-card-4 w3-hoverable">
      <tr class="w3-orange">
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
          <td colspan="6" class="w3-center w3-text-red">No feedback found</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

</body>
</html>
<?php $conn->close(); ?>
