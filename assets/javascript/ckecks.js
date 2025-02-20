function searchOrders() {
  const startDate = document.getElementById("start_date").value;
  const endDate = document.getElementById("end_date").value;
  const userId = document.getElementById("user_filter").value;

  if (!startDate || !endDate) {
    alert("Please insert a date range");
    return;
  }

  fetch(
    `get_orders.php?start_date=${startDate}&end_date=${endDate}&user_id=${userId}`
  )
    .then((response) => response.json())
    .then((data) => {
      const ordersList = document.getElementById("orders_list");
      ordersList.innerHTML = "";

      data.forEach((order) => {
        const row = document.createElement("tr");
        row.innerHTML = `
                  <td>${order.name}</td>
                  <td>${order.date}</td>
                  <td>${order.order_name}</td>
                  <td>${order.quantity}</td>
                  <td>$${parseFloat(order.total_price).toFixed(2)}</td>
              `;
        ordersList.appendChild(row);
      });
    })
    .catch((error) => console.error("Error:", error));
}
