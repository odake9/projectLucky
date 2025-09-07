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
  <title>Staff Menu - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      background: #fff0f5;
      font-family: 'Poppins', sans-serif;
      padding: 30px;
    }
    h1 {
      text-align: center;
      color: #d63384;
      margin-bottom: 30px;
    }
    .menu-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1100px;
      margin: auto;
    }
    .menu-card {
      background: white;
      border-radius: 15px;
      padding: 15px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.3s ease;
    }
    .menu-card:hover {
      transform: translateY(-6px);
    }
    .menu-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 12px;
    }
    .menu-card h4 {
      margin-top: 10px;
      font-weight: bold;
      color: #343a40;
    }
    .menu-card p {
      font-size: 14px;
      color: #555;
      min-height: 40px;
    }
    .menu-card .price {
      font-size: 16px;
      font-weight: bold;
      color: #d63384;
      margin: 10px 0;
    }
    .btn-pink {
      background: #d63384;
      color: white;
      border-radius: 8px;
      padding: 6px 12px;
    }
    .btn-pink:hover {
      background: #b62d6f;
      color: #fff;
    }
    .top-actions {
      text-align: center;
      margin-bottom: 20px;
    }
    .top-actions a {
      margin: 5px;
    }
  </style>
</head>
<body>

<h1>🍹 Manage Menu</h1>

<div class="top-actions">
  <a href="staff.php" class="btn btn-default">⬅️ Back to Dashboard</a>
  <a href="staff_view_menu.php" class="btn btn-info">👀 View Menu</a>
  <a href="add_menu.php" class="btn btn-pink">➕ Add New Menu Item</a>
</div>

<div class="menu-container">
  <?php while($row = $result->fetch_assoc()) { ?>
    <div class="menu-card">
      <?php if (!empty($row['image'])) { ?>
        <img src="uploads/<?php echo $row['image']; ?>" alt="Menu Image">
      <?php } else { ?>
        <img src="https://via.placeholder.com/250x160?text=No+Image" alt="No Image">
      <?php } ?>
      <h4><?php echo $row['name']; ?></h4>
      <p><?php echo $row['description']; ?></p>
      <div class="price">RM <?php echo number_format($row['price'], 2); ?></div>
      <small><em><?php echo $row['category']; ?></em></small>
      <br><br>
      <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
      <a href="delete_menu.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">🗑 Delete</a>
    </div>
  <?php } ?>
</div>

</body>
</html>
<?php $conn->close(); ?>
