<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      margin: 0;
      padding: 0;
    }
    h1 {
      text-align: center;
      margin: 30px 0;
      color: #343a40;
      font-weight: bold;
    }

    .admin-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      max-width: 900px;
      margin: auto;
      padding: 20px;
    }

    .card {
      background: white;
      width: 220px;
      padding: 25px 20px;
      text-align: center;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      background: #f1f9ff;
    }

    .card a {
      text-decoration: none;
      color: #007bff;
      font-size: 18px;
      font-weight: 600;
      display: block;
      margin-top: 10px;
    }

    .card a:hover {
      color: #0056b3;
    }

    .icon {
      font-size: 40px;
      margin-bottom: 10px;
      color: #007bff;
      transition: color 0.3s;
    }

    .card:hover .icon {
      color: #0056b3;
    }
  </style>
</head>
<body>

  <h1>🌟 Welcome Staff 🌟</h1>

  <div class="admin-container">
    <div class="card">
      <div class="icon">👨‍💼</div>
      <a href="#">Manage Staff</a>
    </div>
    <div class="card">
      <div class="icon">📚</div>
      <a href="staff_menu.html">Menu</a>
    </div>
    <div class="card">
      <div class="icon">💬</div>
      <a href="staff_viewfeedback.php">View Feedback</a>
    </div>
    <div class="card">
      <div class="icon">📝</div>
      <a href="staff_profile.php">View Profile</a>
    </div>
    <div class="card">
      <div class="icon">🚪</div>
      <a href="logout.php">Logout</a>
    </div>
  </div>

</body>
</html>
