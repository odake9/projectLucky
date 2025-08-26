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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    ul.admin-menu {
      list-style-type: none;
      padding: 0;
      max-width: 500px;
      margin: auto;
    }
    ul.admin-menu li {
      background: #007bff;
      color: white;
      margin: 10px 0;
      padding: 15px;
      text-align: center;
      border-radius: 8px;
      transition: background 0.3s;
    }
    ul.admin-menu li a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      display: block;
    }
    ul.admin-menu li:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <h1>Welcome Admin</h1>

  <ul class="admin-menu">
    <li><a href="staff_manage.php">👨‍💼 Manage Staff</a></li>
    <li><a href="admin_menu.html">📚 Menu</a></li>
    <li><a href="attendance.php">📝 Record Staff Training Attendance</a></li>
    <li><a href="feedback_list.php">💬 View Feedback</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

</body>
</html>
