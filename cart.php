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
document.addEventListener("DOMContentLoaded", function() {
  const rows = document.querySelectorAll("#cart-table tr[data-index]");
  const totalElement = document.getElementById("total");

  function updateTotal() {
    let total = 0;
    rows.forEach(row => {
      let price = parseFloat(row.querySelector(".price").dataset.price);
      let qty = parseInt(row.querySelector(".qty-input").value);
      let subtotal = price * qty;
      row.querySelector(".subtotal").innerText = "RM " + subtotal.toFixed(2);
      total += subtotal;
    });
    totalElement.innerText = "Total: RM " + total.toFixed(2);
  }

  rows.forEach(row => {
    let minus = row.querySelector(".minus");
    let plus = row.querySelector(".plus");
    let qtyInput = row.querySelector(".qty-input");

    minus.addEventListener("click", () => {
      if (qtyInput.value > 1) qtyInput.value--;
      updateTotal();
    });

    plus.addEventListener("click", () => {
      qtyInput.value++;
      updateTotal();
    });

    qtyInput.addEventListener("input", updateTotal);
  });

  updateTotal(); // init
});
</script>
</body>
</html>
