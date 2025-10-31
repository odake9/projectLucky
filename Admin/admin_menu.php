<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','staff'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM menu ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Menu - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f9f4ef, #f4e1c6);
      font-family: 'Poppins', sans-serif;
      padding: 40px 20px;
      margin: 0;
      color: #5c3b28;
    }

    h1 {
      text-align: center;
      color: #5c3b28;
      font-weight: 600;
      margin-bottom: 35px;
      font-size: 36px;
      text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* --- Top Buttons --- */
    .top-actions {
      text-align: center;
      margin-bottom: 30px;
    }

    .top-actions a {
      text-decoration: none;
      display: inline-block;
      margin: 8px;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border: none;
    }

    .btn-dashboard {
      background: #b68c5a;
      color: white;
    }

    .btn-dashboard:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    .btn-view {
      background: #d2b48c;
      color: white;
    }

    .btn-view:hover {
      background: #c1a170;
      transform: translateY(-2px);
    }

    .btn-add {
      background: #c17856;
      color: white;
    }

    .btn-add:hover {
      background: #a9643b;
      transform: translateY(-2px);
    }

    /* --- Menu Cards Grid --- */
    .menu-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 25px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .menu-card {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 2px solid #f1e0c6;
    }

    .menu-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .menu-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 12px;
      border-bottom: 3px solid #d2b48c;
    }

    .menu-card h4 {
      margin-top: 15px;
      font-weight: 600;
      color: #4e342e;
      font-size: 18px;
    }

    .menu-card p {
      font-size: 14px;
      color: #6b4f3b;
      margin: 8px 0;
      min-height: 40px;
    }

    .menu-card .price {
      font-size: 17px;
      font-weight: bold;
      color: #b68c5a;
      margin: 10px 0;
    }

    .menu-card small {
      color: #8d6e63;
    }

    /* --- Buttons inside cards --- */
    .menu-card .btn {
      border-radius: 8px;
      padding: 6px 12px;
      margin: 4px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-warning {
      background: #d7a86e;
      border: none;
    }

    .btn-warning:hover {
      background: #b77a47;
    }

    .btn-danger {
      background: #c17856;
      border: none;
    }

    .btn-danger:hover {
      background: #a9643b;
    }

    /* Footer */
    footer {
      text-align: center;
      margin-top: 50px;
      color: #6b4f3b;
      opacity: 0.8;
      font-size: 14px;
    }
  </style>
</head>
<body>

<h1>☕ Manage Menu</h1>

<div class="top-actions">
  <a href="admin_view_menu.php" class="btn-view">👀 View Menu</a>
  <a href="add_menu.php" class="btn-add">➕ Add New Menu Item</a>
</div>

<div class="menu-container">
  <?php while($row = $result->fetch_assoc()) { ?>
    <div class="menu-card">
      <?php if (!empty($row['image'])) { ?>
        <img src="uploads/<?php echo $row['image']; ?>" alt="Menu Image">
      <?php } else { ?>
        <img src="https://via.placeholder.com/250x180?text=No+Image" alt="No Image">
      <?php } ?>
      <h4><?php echo htmlspecialchars($row['name']); ?></h4>
      <p><?php echo htmlspecialchars($row['description']); ?></p>
      <div class="price">RM <?php echo number_format($row['price'], 2); ?></div>
      <small><em><?php echo htmlspecialchars($row['category']); ?></em></small>
      <br><br>
      <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
      <a href="delete_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">🗑 Delete</a>
    </div>
  <?php } ?>
</div>

<footer>© 2025 Lucky Milk Tea — Crafted with Love & Served with Joy</footer>

</body>
</html>
<?php $conn->close(); ?>
