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
  <title>Customer Feedback - Lucky Milk Tea</title>

  <!-- W3.CSS & Fonts -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fdf6ec, #f7e3c6);
      margin: 0;
      padding: 40px 20px;
      color: #4b2e19;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #6b4f3b;
      font-weight: 600;
    }

    .top-container {
      text-align: center;
      margin-bottom: 30px;
    }

    .back-btn {
      background: #c17856;
      color: #fff;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 10px;
      font-weight: 500;
      transition: 0.3s;
      display: inline-block;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .back-btn:hover {
      background: #a9643b;
      transform: translateY(-2px);
      text-decoration: none;
    }

    .feedback-table {
      width: 95%;
      margin: auto;
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      border-collapse: collapse;
    }

    .feedback-table th {
      background: #cfa17e;
      color: #fff;
      padding: 14px;
      text-align: center;
      font-weight: 600;
      font-size: 15px;
    }

    .feedback-table td {
      padding: 12px 14px;
      border-bottom: 1px solid #f1e0c6;
      text-align: left;
      vertical-align: top;
    }

    .feedback-table tr:hover {
      background-color: #fff6e9;
      transition: 0.3s;
    }

    .no-data {
      text-align: center;
      padding: 20px;
      color: #a04e2d;
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .feedback-table {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>


  <h1>💬 Customer Feedback</h1>

  <div class="w3-responsive">
    <table class="feedback-table">
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
          <td colspan="6" class="no-data">No feedback found 😔</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

</body>
</html>

<?php $conn->close(); ?>
