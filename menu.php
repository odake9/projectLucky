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

$sql = "SELECT * FROM menu ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Menu - Lucky Milk Tea</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: "Poppins", sans-serif;
      background: url('homeimage.jpg') center/cover no-repeat fixed;
      color: #3c2f2f;
      line-height: 1.6;
    }
    a { text-decoration: none; color: inherit; transition: 0.3s ease; }
    img { max-width: 100%; display: block; }

    /* --- HEADER (same as homepage) --- */
    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: #ffffff;
      border-bottom: 1px solid #e5ddd2;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      z-index: 1000;
    }

    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0.5rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 90px;
      position: relative;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #b68c5a;
      font-size: 1.6rem;
      font-weight: 600;
      height: 80px;
    }

    .nav-logo img {
      height: 70px;
      width: auto;
      object-fit: contain;
      display: block;
    }

    nav {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 2rem;
    }

    nav ul li a {
      color: #5e4b3c;
      font-weight: 400;
      position: relative;
      padding-bottom: 4px;
      transition: color 0.3s ease;
    }

    nav ul li a:hover {
      color: #b68c5a;
    }

    nav ul li a::after {
      content: "";
      position: absolute;
      left: 0; bottom: 0;
      width: 0; height: 2px;
      background: #b68c5a;
      transition: width 0.3s;
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    .nav-actions {
      display: flex;
      align-items: center;
      justify-content: flex-end;
    }

    .nav-actions .nav-btn {
      display: flex;
      align-items: center;
      background: #b68c5a;
      color: white;
      border: none;
      padding: 0.5rem 1.25rem;
      border-radius: 25px;
      font-size: 0.95rem;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .nav-btn i {
      margin-right: 0.5rem;
    }

    .nav-btn:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    .cart-count {
      background-color: white;
      color: #b68c5a;
      border-radius: 50%;
      padding: 0.2rem 0.6rem;
      font-size: 0.8rem;
      font-weight: 600;
      margin-left: 6px;
    }

    /* --- PAGE CONTENT --- */
main {
  margin-top: 90px; /* aligns right below the navbar */
}

/* --- CATEGORY FILTER --- */
.category-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  padding: 0.5rem 1rem; /* snug against navbar */
  background-color: #fff;
  border-bottom: 1px solid #eee3d9;
}

    .category-buttons button {
      background: #b68c5a;
      color: #fff;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 25px;
      font-size: 0.95rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .category-buttons button:hover,
    .category-buttons button.active {
      background: #a47b48;
    }

    /* --- MENU GRID --- */
    .menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      padding: 3rem 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .menu-item {
      display: flex;
      flex-direction: column;
      background: #ffffff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .menu-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .menu-item img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 3px solid #b68c5a;
    }

    .menu-info {
      padding: 1.3rem;
      text-align: center;
      flex-grow: 1;
    }

    .menu-info h3 {
      color: #3c2f2f;
      font-size: 1.1rem;
      margin-bottom: 0.4rem;
    }

    .menu-info p {
      color: #6d5c4a;
      font-size: 0.9rem;
      margin-bottom: 0.8rem;
      min-height: 50px;
    }

    .price {
      color: #b68c5a;
      font-weight: bold;
      font-size: 1rem;
      margin-bottom: 1rem;
    }

    .order-btn {
      background: #b68c5a;
      color: #fff;
      border: none;
      padding: 0.6rem 1.4rem;
      border-radius: 25px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .order-btn:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    footer {
      background: #fff;
      border-top: 1px solid #eee3d9;
      color: #6b5b4c;
      text-align: center;
      padding: 2rem;
      font-size: 0.95rem;
      margin-top: 3rem;
    }
  </style>
</head>

<body>
  <header>
    <div class="nav-container">
      <!-- Left: Logo + Text -->
      <a href="home.html" class="nav-logo">
        <img src="logo.png" alt="Lucky Logo">
        <span>Lucky Milk Tea</span>
      </a>

      <!-- Center: Navigation Menu -->
      <nav>
        <ul>
          <li><a href="about.html">About</a></li>
          <li><a href="menu.php" style="color:#b68c5a;">Menu</a></li>
          <li><a href="contact.php">Contact & Feedback</a></li>
          <li><a href="customer_view_order.php">Order Status</a></li>
        </ul>
      </nav>

      <!-- Right: Cart Button -->
      <div class="nav-actions">
        <a href="cart.html" class="nav-btn"><i class="fa fa-shopping-cart"></i> Cart <span id="cart-count" class="cart-count">0</span></a>
      </div>
    </div>
  </header>

  <main>
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
            <button 
              class="order-btn"
              onclick="addToCart('<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', 'uploads/<?php echo $row['image']; ?>')">
              🛒 Add to Cart
            </button>
          </div>
        </div>
      <?php } ?>
    </section>
  </main>

  <footer>
    © 2025 Lucky Milk Tea — Crafted with love, served with joy.
  </footer>

  <script>
    // Category Filter
    const filterButtons = document.querySelectorAll(".category-buttons button");
    const menuItems = document.querySelectorAll(".menu-item");

    filterButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        document.querySelector(".category-buttons .active").classList.remove("active");
        btn.classList.add("active");
        const category = btn.getAttribute("data-category");
        menuItems.forEach(item => {
          if (category === "all" || item.getAttribute("data-category") === category) {
            item.style.display = "flex";
          } else {
            item.style.display = "none";
          }
        });
      });
    });

    // Cart handling
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartCount = document.getElementById("cart-count");

    function updateCartCount() {
      const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
      cartCount.textContent = totalQty;
    }

    function addToCart(name, price, image) {
      let existingItem = cart.find(item => item.name === name);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({ name, price: parseFloat(price), image, quantity: 1 });
      }
      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartCount();
      alert(name + " added to cart 🧋");
    }

    updateCartCount();
  </script>
</body>
</html>
<?php $conn->close(); ?>
