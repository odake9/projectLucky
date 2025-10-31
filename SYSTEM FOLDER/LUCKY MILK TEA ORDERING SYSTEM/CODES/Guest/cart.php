<?php
session_start();

// Handle remove item
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Lucky Milk Tea</title>
  <style>
    body { font-family: "Poppins", sans-serif; background-color: #fff8f0; padding: 20px; }
    h1 { text-align: center; color: #6b4f4f; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; }
    th { background: #ffcfdf; color: #6b4f4f; }
    tr:hover { background: #ffe5ec; }
    .btn { background: #ff9aa2; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; }
    .btn:hover { background: #ff6f91; }
    .remove-btn { background: #ff6a6a; }
    .remove-btn:hover { background: #e63946; }
    .total { font-weight: bold; font-size: 1.2rem; color: #d46a6a; text-align: right; padding: 10px; }
    .checkout-btn { display: inline-block; margin-top: 15px; padding: 12px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 1rem; font-weight: bold; background: #ff9aa2; color: white; text-decoration: none; }
    .checkout-btn:hover { background: #ff6f91; }
    .qty-box { display: flex; justify-content: center; align-items: center; gap: 5px; }
    .qty-input { width: 50px; text-align: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; }
    .back-btn { display: inline-block; margin-bottom: 15px; padding: 10px 18px; border-radius: 8px; background: #ffcfdf; color: #6b4f4f; font-weight: bold; text-decoration: none; transition: 0.2s; }
    .back-btn:hover { background: #ff9aa2; color: white; }
  </style>
</head>
<body>
  <h1>🛒 Your Cart</h1>
  <div style="text-align:left;">
    <a href="menu.php" class="back-btn">⬅ Back to Menu</a>
  </div>

  <?php if (!empty($_SESSION['cart'])) { ?>
    <table id="cart-table">
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
      <?php
      $total = 0;
      foreach ($_SESSION['cart'] as $index => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr data-index='$index'>
                <td>{$item['name']}</td>
                <td class='price' data-price='{$item['price']}'>RM ".number_format($item['price'], 2)."</td>
                <td>
                  <div class='qty-box'>
                    <button type='button' class='btn minus'>➖</button>
                    <input type='number' class='qty-input' value='{$item['quantity']}' min='1'>
                    <button type='button' class='btn plus'>➕</button>
                  </div>
                </td>
                <td class='subtotal'>RM ".number_format($subtotal, 2)."</td>
                <td><a href='cart.php?remove=$index' class='btn remove-btn'>❌ Remove</a></td>
              </tr>";
      }
      ?>
    </table>
    <div class="total" id="total">Total: RM <?php echo number_format($total, 2); ?></div>

    <div style="text-align:right; margin-top:10px;">
      <a href="payment.html" class="checkout-btn">Proceed to Checkout</a>
    </div>
  <?php } else { ?>
    <p style="text-align:center; color:#555;">Your cart is empty.</p>
  <?php } ?>

<script>
  function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // ✅ confirmation popup
    if (confirm(`Are you sure you want to remove "${cart[index].name}" from the cart?`)) {
      cart.splice(index, 1);
      localStorage.setItem("cart", JSON.stringify(cart));
      loadCart();
      alert("Item removed successfully!"); // optional success message
    }
  }

    function loadCart() {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      const cartContainer = document.getElementById("cart-container");

      if (cart.length === 0) {
        cartContainer.innerHTML = `<p class="empty-message">🛒 Your cart is empty.</p>`;
        return;
      }

      let total = 0;
      let tableHTML = `<table class="w3-table-all">
        <tr><th>Image</th><th>Item</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>`;

      cart.forEach((item, index) => {
        total += item.price * item.quantity;
        tableHTML += `<tr>
          <td><img src="${item.image}" alt="${item.name}" width="60" style="border-radius:8px;"></td>
          <td>${item.name}</td>
          <td>RM ${item.price.toFixed(2)}</td>
          <td>
            <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
            ${item.quantity}
            <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
          </td>
          <td><button class="remove-btn" onclick="removeItem(${index})">REMOVE</button></td>
        </tr>`;
      });

      tableHTML += `</table>
        <label><strong>Remark:</strong></label>
        <textarea id="remark" class="remark-box" placeholder="E.g., Less sugar, add pearls..."></textarea>
        <p class="total">Total: RM ${total.toFixed(2)}</p>
        <button class="checkout-btn" onclick="goToCheckout()">🛒 Checkout</button>`;
      cartContainer.innerHTML = tableHTML;
    }

    function changeQty(index, change) {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      cart[index].quantity += change;
      if (cart[index].quantity < 1) cart.splice(index, 1);
      localStorage.setItem("cart", JSON.stringify(cart));
      loadCart();
    }

    function goToCheckout() {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
      }

      let remark = document.getElementById("remark").value.trim();
      localStorage.setItem("remark", remark);

      // ✅ go to payment page
      window.location.href = "payment.html";
    }

    loadCart();
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const confirmBtn = document.getElementById("confirm-order");
      if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
          const cart = JSON.parse(localStorage.getItem("cart")) || [];
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

          fetch("save_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ cart, total })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert("Order placed successfully!");
              localStorage.removeItem("cart");
              window.location.href = "order.html?order_id=" + data.order_id;
            } else {
              alert("Error placing order: " + data.message);
            }
          })
          .catch(err => alert("Error: " + err));
        });
      }
    });
  </script>
</body>
</html>
