<?php
session_start();

// If not logged in, block access
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard - Lucky Milk Tea</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h1>
    <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

    <hr>

    <h2>Staff Dashboard</h2>
    <ul>
      <li><a href="#">👩‍💻 View Staff List</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </div>
</body>
</html>
