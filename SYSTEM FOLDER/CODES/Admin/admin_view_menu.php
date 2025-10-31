<?php
session_start();

// DB connection
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch menu items
$sql = "SELECT * FROM menu ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin View - Milk Tea Menu</title>

  <!-- ✅ W3.CSS Framework -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #fdf6ec, #f7e3c6);
      color: #5c3b28;
    }

    header {
      background-color: #d2b48c;
      color: #4b2e19;
      text-align: center;
      padding: 20px;
      position: relative;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: 600;
    }

    .dashboard-btn {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      background: #b68c5a;
      color: white;
      border-radius: 8px;
      padding: 8px 14px;
      text-decoration: none;
      transition: 0.3s ease;
      font-weight: 500;
    }

    .dashboard-btn:hover {
      background: #a47b48;
      transform: translateY(-50%) scale(1.05);
    }

    /* Category Buttons */
    .category-buttons {
      text-align: center;
      margin: 20px 0;
    }

    .category-buttons button {
      margin: 5px;
      border: none;
      background: #d7a86e;
      color: #fff;
      border-radius: 8px;
      padding: 10px 16px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.3s ease;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .category-buttons button:hover {
      background: #b77a47;
      transform: translateY(-2px);
    }

    .category-buttons .active {
      background: #a47b48;
    }

    /* Menu Grid */
    .menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 25px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .menu-item {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 2px solid #f1e0c6;
    }

    .menu-item:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .menu-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 3px solid #d2b48c;
    }

    .menu-item h3 {
      margin: 10px 0 5px;
      font-weight: 600;
      font-size: 18px;
      color: #4b2e19;
    }

    .menu-item p {
      font-size: 14px;
      color: #6b4f3b;
      margin: 5px 0;
    }

    .menu-item .w3-text-red {
      color: #b68c5a !important;
      font-weight: bold;
    }

    /* Order Button */
    .order-btn {
      width: 100%;
      background: #c17856;
      color: white;
      font-weight: 500;
      border-radius: 8px;
      padding: 8px 0;
      border: none;
      transition: 0.3s ease;
    }

    .order-btn:hover {
      background: #a9643b;
      transform: translateY(-2px);
    }

    /* Footer */
    footer {
      text-align: center;
      margin-top: 40px;
      padding: 15px;
      background: #f7e3c6;
      color: #5c3b28;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <header class="w3-card w3-round-large">
    <a href="admin_menu.php" class="dashboard-btn">← Back</a>
    <h1>~ Lucky Milk Tea (Admin View) ~</h1>
  </header>

  <!-- Category Filter Buttons -->
  <div class="category-buttons">
    <button class="active" data-category="all">All</button>
    <button data-category="Signature">Signature</button>
    <button data-category="Refreshing">Refreshing</button>
    <button data-category="Ice Blended">Ice Blended</button>
  </div>

  <!-- Menu Section -->
  <section class="menu">
    <?php while($row = $result->fetch_assoc()) { ?>
      <div class="menu-item" data-category="<?php echo $row['category']; ?>">
        <?php if (!empty($row['image'])) { ?>
          <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        <?php } else { ?>
          <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
        <?php } ?>
        <div class="w3-container w3-padding">
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <p class="w3-text-red">RM <?php echo number_format($row['price'], 2); ?></p>
          <form action="admin_view_menu.php" method="POST">
            <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>">
            <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
            <button type="submit" name="add_to_cart" class="order-btn">Order Now</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </section>

  <footer>© 2025 Lucky Milk Tea — Crafted with Love & Served with Joy</footer>

  <script>
    // Filter functionality
    const filterButtons = document.querySelectorAll(".category-buttons button");
    const menuItems = document.querySelectorAll(".menu-item");

    filterButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        document.querySelector(".category-buttons .active").classList.remove("active");
        btn.classList.add("active");
        const category = btn.getAttribute("data-category");
        menuItems.forEach(item => {
          item.style.display = (category === "all" || item.getAttribute("data-category") === category)
            ? "block" : "none";
        });
      });
    });
  </script>

</body>
</html>

<?php
// Handle add to cart
if (isset($_POST['add_to_cart'])) {
  $item = [
    "name" => $_POST['item_name'],
    "price" => $_POST['item_price'],
    "quantity" => 1
  ];
  $_SESSION['cart'][] = $item;
  header("Location: admin_view_menu.php");
  exit();
}

$conn->close();
?>
