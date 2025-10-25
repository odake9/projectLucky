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
  <title>Staff Dashboard - Lucky Milk Tea</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      display: flex;
      height: 100vh;
      background: linear-gradient(135deg, #fbe8e1, #f7d9c4, #fceee4);
      color: #5c3b28;
      overflow: hidden;
    }

    /* ===== Sidebar ===== */
    .sidebar {
      width: 240px;
      background-color: #b68c5a;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 20px 0;
      box-shadow: 4px 0 10px rgba(0,0,0,0.1);
    }

    .sidebar h2 {
      text-align: center;
      font-size: 1.6rem;
      color: #fffbe6;
      margin-bottom: 30px;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .nav a {
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .nav a:hover, .nav a.active {
      background-color: #a47b48;
      transform: translateX(5px);
    }

    .nav i {
      width: 20px;
    }

    .bottom-links {
      margin-top: auto;
      display: flex;
      flex-direction: column;
      gap: 5px;
      padding: 0 10px;
    }

    .bottom-links a {
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 8px;
      transition: background 0.3s ease;
    }

    .bottom-links a:hover {
      background-color: #a47b48;
    }

    /* ===== Main Area ===== */
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    .header {
      background-color: #fff8f3;
      color: #5c3b28;
      text-align: center;
      padding: 20px;
      font-size: 28px;
      font-weight: 600;
      border-bottom: 1px solid #e2c9b1;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    iframe {
      flex: 1;
      width: 100%;
      border: none;
      background-color: #fff;
    }

    footer {
      background: #fff8f3;
      color: #5c3b28;
      text-align: center;
      padding: 10px;
      font-size: 14px;
      border-top: 1px solid #e2c9b1;
    }

    /* Responsive */
    @media (max-width: 800px) {
      .sidebar {
        width: 180px;
      }
      .nav a {
        font-size: 14px;
        padding: 10px;
      }
      .header {
        font-size: 22px;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Lucky Milk Tea</h2>
    <div class="nav">
      <a href="#" class="active" onclick="loadPage('staff_dashboard_home.php', this)"><i class="fa fa-chart-pie"></i> Dashboard</a>
      <a href="#" onclick="loadPage('staff_menu.php', this)"><i class="fa fa-mug-hot"></i> Edit Menu</a>
      <a href="#" onclick="loadPage('staff_view_feedback.php', this)"><i class="fa fa-comments"></i> View Feedback</a>
      <a href="#" onclick="loadPage('staff_view_orders.php', this)"><i class="fa fa-box"></i> View Orders</a>
      <a href="#" onclick="loadPage('staff_sales_report.php', this)"><i class="fa fa-chart-line"></i> Sales Report</a>
      <a href="#" onclick="loadPage('staff_profile.php', this)"><i class="fa fa-user"></i> Profile</a>
    </div>

    <div class="bottom-links">
      <a href="home.html"><i class="fa fa-home"></i> Back to Home</a>
      <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>

  <!-- Main Area -->
  <div class="main-content">
    <div class="header">Staff Dashboard</div>
    <iframe id="contentFrame" src="staff_dashboard_home.php"></iframe>
    <footer>© 2025 Lucky Milk Tea — Staff Panel</footer>
  </div>

  <script>
    // Load pages inside iframe
    function loadPage(page, element) {
      document.getElementById("contentFrame").src = page;

      // Highlight active link
      document.querySelectorAll(".nav a").forEach(a => a.classList.remove("active"));
      element.classList.add("active");
    }
  </script>

</body>
</html>
