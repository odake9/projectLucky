<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Lucky Milk Tea</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ffe6f0, #fff5f9);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      margin: 40px 0 20px;
      color: #d63384;
      font-weight: 600;
      text-align: center;
      font-size: 34px;
      letter-spacing: 1px;
    }

    .admin-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      gap: 25px;
      max-width: 1100px;
      padding: 20px;
    }

    .card {
      width: 240px;
      padding: 30px 20px;
      text-align: center;
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 6px 15px rgba(214, 51, 132, 0.15);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 12px 25px rgba(214, 51, 132, 0.25);
      background: #fff0f6;
    }

    .icon {
      font-size: 48px;
      margin-bottom: 15px;
      color: #d63384;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    .card:hover .icon {
      transform: scale(1.2);
      color: #ff4d94;
    }

    .card a {
      text-decoration: none;
      color: #d63384;
      font-size: 18px;
      font-weight: 600;
      display: block;
      margin-top: 8px;
      transition: color 0.3s ease;
    }

    .card:hover a {
      color: #ff4d94;
    }

    @media (max-width: 768px) {
      .card {
        width: 90%;
      }
    }
  </style>
</head>
<body>

  <h1>🌸 Welcome, Admin 🌸</h1>

  <div class="admin-container">
    <div class="card">
      <div class="icon">👨‍💼</div>
      <a href="staff_manage.php">Manage Staff</a>
    </div>
    <div class="card">
      <div class="icon">📚</div>
      <a href="admin_menu.html">Menu</a>
    </div>
    <div class="card">
      <div class="icon">💬</div>
      <a href="admin_viewfeedback.php">View Feedback</a>
    </div>
    <div class="card">
      <div class="icon">📝</div>
      <a href="profile.php">View Profile</a>
    </div>
    <div class="card">
      <div class="icon">🚪</div>
      <a href="logout.php">Logout</a>
    </div>
  </div>

</body>
</html>
