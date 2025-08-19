const cartTableBody = document.querySelector("#cart-table tbody");
const grandTotalElem = document.getElementById("grand-total");
const confirmOrderBtn = document.getElementById("confirm-order");

let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Render cart items
function renderCart() {
  cartTableBody.innerHTML = "";
  let grandTotal = 0;

  cart.forEach((item, index) => {
    const row = document.createElement("tr");

    const itemTotal = item.price * item.quantity;
    grandTotal += itemTotal;

    row.innerHTML = `
      <td><img src="${item.image}" alt="${item.name}" width="50"></td>
      <td>${item.name}</td>
      <td>RM ${item.price.toFixed(2)}</td>
      <td><input type="number" min="1" value="${item.quantity}" data-index="${index}" class="qty-input"></td>
      <td><input type="text" placeholder="Enter remark" value="${item.remark || ""}" data-index="${index}" class="remark-input"></td>
      <td>RM ${itemTotal.toFixed(2)}</td>
      <td><button class="remove-btn" data-index="${index}">Remove</button></td>
    `;

    cartTableBody.appendChild(row);
  });

  grandTotalElem.textContent = grandTotal.toFixed(2);
  attachEvents();
}

// Attach events
function attachEvents() {
  document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("change", e => {
      const index = e.target.dataset.index;
      cart[index].quantity = parseInt(e.target.value);
      saveCart();
    });
  });

  document.querySelectorAll(".remark-input").forEach(input => {
    input.addEventListener("input", e => {
      const index = e.target.dataset.index;
      cart[index].remark = e.target.value;
      saveCart(false);
    });
  });

  document.querySelectorAll(".remove-btn").forEach(button => {
    button.addEventListener("click", e => {
      const index = e.target.dataset.index;
      cart.splice(index, 1);
      saveCart();
    });
  });
}

// Save cart to localStorage
function saveCart(reRender = true) {
  localStorage.setItem("cart", JSON.stringify(cart));
  if (reRender) renderCart();
}

// Confirm order and send to server
if (confirmOrderBtn) {
  confirmOrderBtn.addEventListener("click", () => {
    if (cart.length === 0) {
      alert("Your cart is empty!");
      return;
    }

    fetch("save_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ cart: cart })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Order saved successfully!");
        localStorage.removeItem("cart");
        cart = [];
        renderCart();
      } else {
        alert("Failed to save order: " + data.message);
      }
    })
    .catch(err => {
      console.error("Error:", err);
      alert("Error saving order!");
    });
  });
}

// Init
renderCart();
