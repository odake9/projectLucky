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
    body { font-family: "Poppins", sans-serif; margin: 0; background-color: #fff8f0; }
    header { position: relative; padding: 1rem; background-color: #ffcfdf; text-align: center; }
    header h1 { margin: 0; color: #6b4f4f; }
    .dashboard-btn { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); }
    .category-buttons { margin: 1rem 0; text-align: center; }
    .category-buttons button { margin: 0 0.3rem; }
    .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 2rem; max-width: 1200px; margin: auto; }
    .menu-item img { height: 250px; object-fit: cover; }
    .order-btn { width: 100%; }
  </style>
</head>
<body>

  <header class="w3-card w3-round-large w3-padding">
    <a href="staff_menu.php" class="w3-button w3-red dashboard-btn">📊 Dashboard</a>
    <h1 class="w3-center">~ Lucky Milk Tea (Staff) ~</h1>
  </header>

  <!-- Category Filter Buttons -->
  <div class="category-buttons">
    <button class="w3-button w3-pink w3-round w3-small active" data-category="all">All</button>
    <button class="w3-button w3-pink w3-round w3-small" data-category="Signature">Signature</button>
    <button class="w3-button w3-pink w3-round w3-small" data-category="Refreshing">Refreshing</button>
    <button class="w3-button w3-pink w3-round w3-small" data-category="Ice Blended">Ice Blended</button>
  </div>

  <section class="menu">
    <?php while($row = $result->fetch_assoc()) { ?>
      <div class="w3-card-4 w3-round menu-item w3-white" data-category="<?php echo $row['category']; ?>">
        <?php if (!empty($row['image'])) { ?>
          <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="w3-image">
        <?php } else { ?>
          <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image" class="w3-image">
        <?php } ?>
        <div class="w3-container">
          <h3><?php echo $row['name']; ?></h3>
          <p><?php echo $row['description']; ?></p>
          <p class="w3-text-red"><b>RM <?php echo number_format($row['price'], 2); ?></b></p>
          <form action="admin_view_menu.php" method="POST">
            <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>">
            <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
            <button type="submit" name="add_to_cart" class="w3-button w3-pink w3-round order-btn">Order Now</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </section>

  <script>
    // Filter buttons
    const filterButtons = document.querySelectorAll(".category-buttons button");
    const menuItems = document.querySelectorAll(".menu-item");

    filterButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        document.querySelector(".category-buttons .active").classList.remove("active");
        btn.classList.add("active");
        const category = btn.getAttribute("data-category");
        menuItems.forEach(item => {
          if (category === "all" || item.getAttribute("data-category") === category) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
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
  header("Location: admin_view_menu.php"); // refresh to update cart
  exit();
}

$conn->close();
?>
