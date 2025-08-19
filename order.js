document.addEventListener("DOMContentLoaded", () => {
  const orderId = localStorage.getItem("lastOrderId");
  if (!orderId) {
    document.querySelector(".receipt").innerHTML = "<h2>No order found</h2>";
    return;
  }

  fetch(`get_order.php?order_id=${orderId}`)
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        document.querySelector(".receipt").innerHTML = "<h2>Error loading order</h2>";
        return;
      }

      document.getElementById("order-date").textContent = "Date: " + data.order.order_date;
      const tbody = document.querySelector("#order-table tbody");
      tbody.innerHTML = "";

      data.items.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${item.name}</td>
          <td>${item.quantity}</td>
          <td>${parseFloat(item.price).toFixed(2)}</td>
          <td>${(item.price * item.quantity).toFixed(2)}</td>
          <td>${item.remark || "-"}</td>
        `;
        tbody.appendChild(row);
      });

      document.getElementById("grand-total").textContent = data.order.total.toFixed(2);
    })
    .catch(() => {
      document.querySelector(".receipt").innerHTML = "<h2>Error loading order</h2>";
    });
});
