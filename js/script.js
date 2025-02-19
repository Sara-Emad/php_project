document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".cancel-order-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            let cancelOrderId = this.getAttribute("data-order-id");

            if (confirm("Are you sure you want to cancel this order?")) {
                fetch("cancel_order.php?cancel_order_id=" + cancelOrderId, {
                    method: "GET"
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "Success") {
                        alert("Order canceled successfully!");
                        location.reload(); 
                    } else {
                        alert("Failed to cancel order. Please try again.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});