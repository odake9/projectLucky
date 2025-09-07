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
  <title>Milk Tea Menu</title>
  <style>
    body { font-family: "Poppins", sans-serif; margin: 0; background-color: #fff8f0; color: #333; }
    header { background-color: #ffcfdf; padding: 1rem; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: relative; }
    header h1 { margin: 0; font-size: 2rem; color: #6b4f4f; }
    .back-btn, .cart-btn { position: absolute; top: 50%; transform: translateY(-50%); background-color: #ff9aa2; color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; text-decoration: none; transition: background 0.2s; display: flex; align-items: center; gap: 0.4rem; }
    .back-btn:hover, .cart-btn:hover { background-color: #ff6f91; }
    .back-btn { left: 1rem; }
    .cart-btn { right: 1rem; }
    .cart-count { background-color: white; color: #ff6f91; font-weight: bold; border-radius: 50%; padding: 0.2rem 0.5rem; font-size: 0.8rem; }
    .category-buttons { display: flex; justify-content: center; gap: 1rem; padding: 1rem; background-color: #ffe5ec; }
    .category-buttons button { background-color: #ff9aa2; color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; font-size: 0.9rem; }
    .category-buttons button:hover, .category-buttons button.active { background-color: #ff6f91; }
    .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 2rem; max-width: 1200px; margin: auto; }
    .menu-item { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.2s ease; }
    .menu-item:hover { transform: translateY(-5px); }
    .menu-item img { width: 100%; height: 300px; object-fit: cover; }
    .menu-info { padding: 1rem; }
    .menu-info h3 { margin: 0; color: #6b4f4f; font-size: 1.3rem; }
    .menu-info p { margin: 0.5rem 0; color: #555; }
    .price { font-weight: bold; color: #d46a6a; font-size: 1.1rem; }
    .order-btn { background-color: #ff9aa2; color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem; }
    .order-btn:hover { background-color: #ff6f91; }
  </style>
</head>
<body>
  <header>
    <a href="home.html" class="back-btn">← Back to Home</a>
    <h1>~ Lucky Milk Tea ~</h1>
    <a href="cart.php" class="cart-btn">
      🛒 Cart <span class="cart-count" id="cart-count">
        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
      </span>
    </a>
    <a href="cart.html" class="cart-btn">
      🛒 Cart <span class="cart-count" id="cart-count">0</span>
    </a>
  </header>

  <!-- Category Filter Buttons -->
  <div class="category-buttons">
    <button class="active" data-category="all">All</button>
    <button data-category="Signature">Signature</button>
    <button data-category="Refreshing">Refreshing</button>
    <button data-category="Ice Blended">Ice Blended</button>
  </div>

  <section class="menu" id="menu">
    <?php while($row = $result->fetch_assoc()) { ?>
      <div class="menu-item" data-category="<?php echo $row['category']; ?>">
        <?php if (!empty($row['image'])) { ?>
          <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        <?php } else { ?>
          <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
        <?php } ?>
        <div class="menu-info">
          <h3><?php echo $row['name']; ?></h3>
          <p><?php echo $row['description']; ?></p>
          <p class="price">RM <?php echo number_format($row['price'], 2); ?></p>
          <form action="menu.php" method="POST">
            <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>">
            <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
            <button type="submit" name="add_to_cart" class="order-btn">Order Now</button>
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
  header("Location: menu.php"); // refresh to update cart count
  exit();
}

$conn->close();
?>

<script>
  // Load cart from localStorage
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartCount = document.getElementById("cart-count");

  function updateCartCount() {
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalQty;
  }

  // Run immediately when page loads
  updateCartCount();

  // Update cart count whenever storage changes (like if user updates cart in another tab)
  window.addEventListener("storage", () => {
    cart = JSON.parse(localStorage.getItem("cart")) || [];
    updateCartCount();
  });
</script>
