let orderItems = [];
let total = 0;
let allProducts = [];

function addToOrder() {
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');

    if (!productSelect.value) {
        showError('Please select a product');
        return;
    }

    const productOption = productSelect.selectedOptions[0];
    const productId = productSelect.value;
    const productName = productOption.text.split(' - ')[0];
    const productPrice = parseFloat(productOption.text.split('EGP ')[1]);
    const quantity = parseInt(quantityInput.value);

    if (quantity <= 0 || isNaN(quantity)) {
        showError('Please enter a valid quantity');
        return;
    }

    orderItems.push({
        product_id: productId,
        name: productName,
        quantity: quantity,
        price: productPrice,
        total: productPrice * quantity
    });

    updateOrderSummary();
    quantityInput.value = "1";
}

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger alert-dismissible fade show';
    errorDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.getElementById('orderForm').prepend(errorDiv);

    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Improved updateOrderSummary function (only this version)
function updateOrderSummary() {
    const orderItemsDiv = document.getElementById('orderItems');
    orderItemsDiv.innerHTML = '';
    total = 0;
    
    if (orderItems.length === 0) {
        orderItemsDiv.innerHTML = '<p class="text-muted">No items in order</p>';
        // document.getElementById('orderTotal').textContent = 'EGP 0.00';
        document.getElementById('orderTotal').textContent = `EGP ${total.toFixed(2)}`;
        return;
    }
    
    orderItems.forEach((item, index) => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'd-flex justify-content-between align-items-center mb-2';
        itemDiv.innerHTML = `
            <div>
                <span class="fw-bold">${escapeHtml(item.name)}</span>
                <br>
                <small>Qty: ${item.quantity} × EGP ${item.price.toFixed(2)}</small>
            </div>
            <div class="text-end">
                <div>EGP ${item.total.toFixed(2)}</div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">×</button>
            </div>
        `;
        orderItemsDiv.appendChild(itemDiv);
        total += item.total;
    });
    
    document.getElementById('orderTotal').textContent = `EGP ${total.toFixed(2)}`;
}

function removeItem(index) {
    orderItems.splice(index, 1);
    updateOrderSummary();
}

document.addEventListener("DOMContentLoaded", function () {
    const orderForm = document.getElementById("orderForm");

    if (!orderForm) {
        console.error("Error: orderForm not found in the DOM.");
        return;
    }

    orderForm.onsubmit = async function (e) {
        e.preventDefault();

        if (orderItems.length === 0) {
            showError("Please add at least one product to the order");
            return false;
        }

        const orderData = {
            products: orderItems.map((item) => ({
                product_id: item.product_id,
                quantity: item.quantity,
            })),
            notes: document.querySelector('textarea[name="notes"]').value.trim(), //?edit add trim()
            room: document.querySelector('select[name="room"]').value.trim(), //?edit add trim()
        };

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = "Processing...";

        try {
            // console.log("Attempting to fetch from:", "../../PHP/process_order.php");
            const response = await fetch("process_order.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(orderData),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.status === "success") {
                const successDiv = document.createElement("div");
                successDiv.className = "alert alert-success";
                successDiv.textContent = "Order created successfully!";
                orderForm.prepend(successDiv);

                orderItems = [];
                updateOrderSummary();
                orderForm.reset();

                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                throw new Error(data.message || "Failed to create order");
            }
        } catch (error) {
            console.error("Error:", error);
            showError(error.message || "An error occurred while processing the order");
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }

        return false;
    };
    
    // Initialize search functionality
    // Get products from the select element and store them
    const productSelect = document.querySelector('select[name="product_id"]');
    allProducts = Array.from(productSelect.options)
        .filter(option => option.value) // Skip the "Select Product" option
        .map(option => {
            const [name, priceText] = option.text.split(' - EGP ');
            return {
                id: option.value,
                name: name.trim(),
                price: parseFloat(priceText),
                category: option.dataset.category || '',
                image: option.dataset.image || ''
            };
        });

    // Set up search input handler with debouncing
    const searchInput = document.getElementById('productSearch');
    let debounceTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase().trim();
                filterAndDisplayProducts(searchTerm);
            }, 300);
        });

        // Initial display of all products
        filterAndDisplayProducts('');
    }
});

// Enhanced filter function with multiple search criteria
function filterAndDisplayProducts(searchTerm) {
    const filteredProducts = allProducts.filter(product => {
        if (!searchTerm) return true;
        
        // Search in product name
        if (product.name.toLowerCase().includes(searchTerm)) return true;
        
        // Search in price (if searchTerm is a number)
        const searchNumber = parseFloat(searchTerm);
        if (!isNaN(searchNumber) && product.price === searchNumber) return true;
        
        // Search in category
        if (product.category.toLowerCase().includes(searchTerm)) return true;
        
        return false;
    });

    displayProducts(filteredProducts);
}

// Enhanced display function with proper image handling
function displayProducts(products) {
    const productGrid = document.getElementById('productGrid');
    
    if (!productGrid) {
        console.error("Product grid element not found");
        return;
    }
    
    if (products.length === 0) {
        productGrid.innerHTML = `
            <div class="no-results">
                <div class="text-center text-gray-500 my-8">
                    <i class="fas fa-search mb-3"></i>
                    <p>No products found</p>
                </div>
            </div>`;
        return;
    }
    
    productGrid.innerHTML = products.map(product => {
        // Construct proper image path with proper template literal syntax
        const imagePath = product.image 
            ? `./uploads/products/${product.image}`
            : './assets/images/default-product.jpg';
            
        return `
            <div class="product-card" data-product-id="${product.id}">
                <img src="${imagePath}" 
                    alt="${escapeHtml(product.name)}"
                    onerror="this.src='./assets/images/default-product.jpg'"
                    class="product-image">
                <div class="product-info">
                    <div class="product-name">${escapeHtml(product.name)}</div>
                    <div class="product-price">EGP ${product.price.toFixed(2)}</div>
                    <button class="add-to-cart" onclick="quickAddToOrder(${product.id}, '${escapeHtml(product.name).replace(/'/g, "\\'").replace(/"/g, '\\"')}', ${product.price})">
                        Add to Order
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

// Utility function to escape HTML and prevent XSS
function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Quick add to order with animation feedback
function quickAddToOrder(productId, productName, productPrice) {
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    
    if (productSelect && quantityInput) {
        productSelect.value = productId;
        quantityInput.value = "1";
        
        // Add visual feedback
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        if (productCard) {
            productCard.classList.add('adding-to-cart');
            setTimeout(() => {
                productCard.classList.remove('adding-to-cart');
            }, 500);
        }
        
        // Trigger the existing addToOrder function
        addToOrder();
    }
}