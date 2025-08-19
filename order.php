<?php
// order.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Page</title>
  <style>
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #f2f2f2; }
    img { width: 50px; }
    button { padding: 10px 15px; cursor: pointer; }
    #confirm-order { background: green; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>
  <h1>Your Cart</h1>
  <table id="cart-table">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Remark</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <h2>Grand Total: RM <span id="grand-total">0.00</span></h2>
  <button id="confirm-order">Confirm Order</button>

  <script src="cart.js"></script>
  <script>
    // Confirm Order button
    document.getElementById("confirm-order").addEventListener("click", () => {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];

      if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
      }

      fetch("save_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart: cart })
      })
      .then(res => res.text())
      .then(data => {
        alert(data); // message from PHP
        localStorage.removeItem("cart"); // clear cart
        location.reload(); // refresh page
      })
      .catch(err => console.error("Error:", err));
    });
  </script>
</body>
</html>
