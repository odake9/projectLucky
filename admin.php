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

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      min-height: 100vh;
      background: linear-gradient(135deg, #fbe8e1, #f7d9c4, #fceee4);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }

    h1 {
      margin-top: 60px;
      color: #5c3b28;
      font-weight: 600;
      font-size: 36px;
      letter-spacing: 1px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      animation: fadeInDown 1s ease-in-out;
    }

    /* Container */
    .admin-container {
      width: 90%;
      max-width: 1100px;
      margin: 40px auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 40px; /* spacing between rows */
      animation: fadeInUp 1.2s ease-in-out;
    }

    /* Row for top 4 cards */
    .row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 30px; /* space between cards */
      justify-items: center;
      width: 100%;
    }

    /* Bottom row center 3 cards */
    .row.center {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .card {
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 30px 20px;
      text-align: center;
      color: #5c3b28;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: all 0.35s ease;
      cursor: pointer;
      border: 1px solid rgba(255,255,255,0.6);
      width: 220px;
    }

    .card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 12px 25px rgba(0,0,0,0.15);
      background: rgba(255, 255, 255, 0.95);
    }

    .icon {
      font-size: 45px;
      margin-bottom: 12px;
      color: #c17856;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    .card:hover .icon {
      transform: scale(1.2);
      color: #a9643b;
    }

    .card a {
      text-decoration: none;
      color: #5c3b28;
      font-size: 18px;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .card:hover a {
      color: #a9643b;
    }

    footer {
      margin-top: auto;
      padding: 20px;
      text-align: center;
      color: #5c3b28;
      font-size: 14px;
      opacity: 0.8;
    }

    /* Animations */
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 1000px) {
      .row {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 700px) {
      .row, .row.center {
        flex-direction: column;
        display: flex;
        align-items: center;
      }
      .card {
        width: 90%;
        max-width: 300px;
      }
      h1 {
        font-size: 28px;
        margin-top: 40px;
      }
    }
  </style>
</head>
<body>

  <h1>Admin Dashboard</h1>

  <div class="admin-container">

    <!-- Top Row (4 cards) -->
    <div class="row">
      <div class="card">
        <div class="icon">👨‍💼</div>
        <a href="staff_manage.php">Manage Staff</a>
      </div>
      <div class="card">
        <div class="icon">📚</div>
        <a href="admin_menu.php">Edit Menu</a>
      </div>
      <div class="card">
        <div class="icon">💬</div>
        <a href="admin_viewfeedback.php">View Feedback</a>
      </div>
      <div class="card">
        <div class="icon">📦</div>
        <a href="admin_view_orders.php">View Orders</a>
      </div>
    </div>

    <!-- Bottom Row (centered 3 cards) -->
    <div class="row center">
      <div class="card">
        <div class="icon">📊</div>
        <a href="admin_sales_report.php">Sales Report</a>
      </div>
      <div class="card">
        <div class="icon">📝</div>
        <a href="profile.php">Profile</a>
      </div>
      <div class="card">
        <div class="icon">🚪</div>
        <a href="logout.php">Logout</a>
      </div>
    </div>

  </div>

  <footer>© 2025 Lucky Milk Tea — Admin Panel</footer>

</body>
</html>
