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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu - Lucky Milk Tea</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    /* --- GLOBAL --- */
    body {
      font-family: "Poppins", sans-serif;
      background-color: #faf8f5;
      color: #3c2f2f;
      margin: 0;
    }

    a { text-decoration: none; color: inherit; }

    /* --- HEADER --- */
    header {
      background: #ffffff;
      border-bottom: 1px solid #e5ddd2;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      position: sticky;
      top: 0;
      z-index: 1000;
      position: relative;
    }

    .nav-logo {
      font-size: 1.8rem;
      font-weight: 600;
      color: #b68c5a;
      text-align: center;
    }

    .back-btn {
      position: absolute;
      left: 20px;
      background: #b68c5a;
      color: white;
      padding: 0.6rem 1.3rem;
      border-radius: 25px;
      font-size: 0.9rem;
      font-weight: 500;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .back-btn:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    .nav-actions {
      position: absolute;
      right: 20px;
      display: flex;
      align-items: center;
    }

    .nav-actions a {
      background: #b68c5a;
      color: white;
      padding: 0.6rem 1.3rem;
      border-radius: 25px;
      font-size: 0.9rem;
      font-weight: 500;
      transition: background 0.3s ease, transform 0.2s ease;
      margin-left: 0.5rem;
    }

    .nav-actions a:hover {
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

    /* --- CATEGORY FILTER --- */
    .category-buttons {
      display: flex;
      justify-content: center;
      gap: 1rem;
      padding: 2rem 1rem 1rem;
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
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
      padding: 3rem 2rem;
      max-width: 1300px;
      margin: 0 auto;
    }

    @media (max-width: 1200px) {
      .menu { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 900px) {
      .menu { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
      .menu { grid-template-columns: 1fr; }
    }

    .menu-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: #ffffff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 100%;
      height: 520px;
      max-width: 280px;
      margin: 0 auto;
    }

    .menu-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .menu-item img {
      width: 100%;
      height: 230px;
      object-fit: cover;
      border-bottom: 3px solid #b68c5a;
    }

    .menu-info {
      padding: 1.3rem;
      text-align: center;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
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
      flex-grow: 1;
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

    /* --- FOOTER --- */
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
  <!-- Header -->
  <header>
    <a href="home.html" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="nav-logo">Lucky Milk Tea</div>
    <div class="nav-actions">
      <a href="cart.html"><i class="fa fa-shopping-cart"></i> Cart <span id="cart-count" class="cart-count">0</span></a>
    </div>
  </header>

  <!-- Category Filter -->
  <div class="category-buttons">
    <button class="active" data-category="all">All</button>
    <button data-category="Signature">Signature</button>
    <button data-category="Refreshing">Refreshing</button>
    <button data-category="Ice Blended">Ice Blended</button>
  </div>

  <!-- Menu Grid -->
  <section class="menu" id="menu">
    <?php while($row = $result->fetch_assoc()) { ?>
      <div class="menu-item" data-category="<?php echo $row['category']; ?>">
        <?php if (!empty($row['image'])) { ?>
          <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        <?php } else { ?>
          <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
        <?php } ?>
        <div class="menu-info">
          <div>
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['description']; ?></p>
          </div>
          <div>
            <p class="price">RM <?php echo number_format($row['price'], 2); ?></p>
            <button 
              class="order-btn"
              onclick="addToCart('<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', 'uploads/<?php echo $row['image']; ?>')">
              🛒 Add to Cart
            </button>
          </div>
        </div>
      </div>
    <?php } ?>
  </section>

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

    // Cart
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
