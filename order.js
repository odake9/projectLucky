// order.js

const menuContainer = document.getElementById("menu");
const categoryButtons = document.querySelectorAll(".category-buttons button");
const cartCount = document.getElementById("cart-count");

// Load cart from localStorage
let cart = JSON.parse(localStorage.getItem("cart")) || [];
updateCartCount();

function updateCartCount() {
  const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
  cartCount.textContent = totalQty;
}

function addToCart(item) {
  const existingItem = cart.find(cartItem => cartItem.name === item.name);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      ...item,
      quantity: 1,
      remark: "" // remark will be editable later in cart.html
    });
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

function displayMenu(category) {
  menuContainer.innerHTML = "";
  const filteredItems = category === "all"
    ? menuItems
    : menuItems.filter(item => item.category === category);

  filteredItems.forEach(item => {
    const menuCard = document.createElement("div");
    menuCard.classList.add("menu-item");
    menuCard.innerHTML = `
      <img src="${item.image}" alt="${item.name}">
      <div class="menu-info">
        <h3>${item.name}</h3>
        <p>${item.description}</p>
        <p class="price">RM ${item.price.toFixed(2)}</p>
        <button class="order-btn">Add to cart</button>
      </div>
    `;
    menuContainer.appendChild(menuCard);

    menuCard.querySelector(".order-btn").addEventListener("click", () => {
      addToCart(item);
    });
  });
}

// Initial menu display
displayMenu("all");

// Category filter buttons
categoryButtons.forEach(button => {
  button.addEventListener("click", () => {
    document.querySelector(".category-buttons .active").classList.remove("active");
    button.classList.add("active");
    displayMenu(button.dataset.category);
  });
});
