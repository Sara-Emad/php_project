let order = [];
let totalPrice = 0;

function addToOrder() {
    let productSelect = document.getElementById("productSelect");
    let quantity = parseInt(document.getElementById("quantity").value);
    let productName = productSelect.options[productSelect.selectedIndex].text;
    let productPrice = parseInt(productSelect.options[productSelect.selectedIndex].getAttribute("data-price"));

    let item = { name: productName, price: productPrice, quantity: quantity };
    order.push(item);
    totalPrice += productPrice * quantity;

    updateOrderList();
}

function updateOrderList() {
    let orderList = document.getElementById("orderList");
    orderList.innerHTML = "";
    order.forEach(item => {
        let listItem = document.createElement("li");
        listItem.className = "list-group-item";
        listItem.textContent = `${item.name} x ${item.quantity} - ${item.price * item.quantity} LE`;
        orderList.appendChild(listItem);
    });

    document.getElementById("totalPrice").textContent = totalPrice;
}

document.getElementById("orderForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let userId = document.getElementById("userSelect").value;

    fetch("process_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ userId: userId, order: order, totalPrice: totalPrice })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        order = [];
        totalPrice = 0;
        updateOrderList();
    })
    .catch(error => console.error("Error:", error));
});
