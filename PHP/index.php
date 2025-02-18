
<?php
require_once '../php/database/config.php';
require_once '../php/products.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Product object
$product = new Product($db);

// Get all products
$stmt = $product->read();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 1.5rem;
      padding: 1rem;
    }
    
    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.2s;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .product-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    
    .product-info {
      padding: 1rem;
    }
    
    .product-name {
      font-size: 1.1rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }
    
    .product-price {
      color: #2563eb;
      font-weight: bold;
      margin-bottom: 1rem;
    }
    
    .add-to-cart {
      background-color: #2563eb;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.2s;
    }
    
    .add-to-cart:hover {
      background-color: #1d4ed8;
    }
    
    .search-container {
      margin: 1rem auto;
      max-width: 600px;
      padding: 0 1rem;
    }
    
    .search-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.2s;
    }
    
    .search-input:focus {
      border-color: #2563eb;
      outline: none;
    }
    
    .no-results {
      text-align: center;
      padding: 2rem;
      color: #666;
      grid-column: 1 / -1;
    }
  </style>
  </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Cafe System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adduser.php">add user</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product_mangement.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>New Order</h5>
                </div>
                <div class="card-body">
                    <form id="orderForm" method="POST" action="process_order.php">
                        <div class="row">
                            <!-- Left side - Order Input -->
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Product</label>
                                        <select class="form-select" name="product_id" required>
    <option value="">Select Product</option>
    <?php foreach($products as $product): ?>
        <option value="<?php echo $product['product_id']; ?>"
                data-category="<?php echo htmlspecialchars($product['category_name']); ?>"
                data-image="<?php echo htmlspecialchars($product['image']); ?>">
            <?php echo $product['product_name']; ?> - 
            EGP <?php echo $product['product_price']; ?>
        </option>
    <?php endforeach; ?>
</select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="quantity" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary d-block w-100" onclick="addToOrder()">
                                            Add to Order
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="2"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Table</label>
                                    <select class="form-select" name="room">
                                        <option value="">Select Table</option>
                                        <option value="Room 1">table 1</option>
                                        <option value="Room 2">table 2</option>
                                        <option value="Room 3">table 3</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Right side - Order Summary -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Order Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="orderItems" class="mb-3">
                                            <!-- Order items will be displayed here -->
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h6>Total:</h6>
                                            <h6 id="orderTotal">EGP 0.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success w-100">Confirm Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <br>
    <br>


<div class="search-container">
    <input 
      type="text" 
      id="productSearch" 
      class="search-input" 
      placeholder="Search for products..."
    >
  </div>
  
  <div id="productGrid" class="product-grid">
    <!-- Products will be displayed here -->
  </div>
</div>
<div id="orderItemsInputs" style="display: none;">
    <!-- Hidden inputs will be added here -->
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
let orderItems = [];
let total = 0;

function addToOrder() {
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    
    if (!productSelect.value) {
        alert('Please select a product');
        return;
    }

    const productOption = productSelect.selectedOptions[0];
    const productId = productSelect.value;
    const productName = productOption.text.split(' - ')[0];
    const productPrice = parseFloat(productOption.text.split('EGP ')[1]);
    const quantity = parseInt(quantityInput.value);
    
    if (quantity <= 0 || isNaN(quantity)) {
        alert('Please enter a valid quantity');
        return;
    }

    // Add item to order
    orderItems.push({
        product_id: productId,
        name: productName,
        quantity: quantity,
        price: productPrice,
        total: productPrice * quantity
    });
    
    updateOrderSummary();
    
    // Keep the selected product and only reset quantity
    quantityInput.value = "1";
}

function updateOrderSummary() {
    const orderItemsDiv = document.getElementById('orderItems');
    orderItemsDiv.innerHTML = '';
    total = 0;
    
    orderItems.forEach((item, index) => {
        orderItemsDiv.innerHTML += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="fw-bold">${item.name}</span>
                    <br>
                    <small>Qty: ${item.quantity} × EGP ${item.price}</small>
                </div>
                <div class="text-end">
                    <div>EGP ${item.total.toFixed(2)}</div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">×</button>
                </div>
            </div>
        `;
        total += item.total;
    });
    
    document.getElementById('orderTotal').textContent = `EGP ${total.toFixed(2)}`;
}

function removeItem(index) {
    orderItems.splice(index, 1);
    updateOrderSummary();
}

document.getElementById('orderForm').onsubmit = function(e) {
    e.preventDefault();
    
    if (orderItems.length === 0) {
        alert('Please add at least one product to the order');
        return false;
    }

    const formData = new FormData();
    
    // Add each product to formData
    orderItems.forEach((item, index) => {
        formData.append(`products[${index}][product_id]`, item.product_id);
        formData.append(`products[${index}][quantity]`, item.quantity);
    });

    // Add other form fields
    formData.append('notes', document.querySelector('textarea[name="notes"]').value);
    formData.append('room', document.querySelector('select[name="room"]').value);

    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = 'Processing...';

    fetch('process_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Order created successfully!');
            // Clear form and order items
            orderItems = [];
            updateOrderSummary();
            this.reset();
            location.reload(); // Optional: reload the page after successful order
        } else {
            throw new Error(data.message || 'Failed to create order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while processing the order');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });

    return false;
};

// Prevent form submission on enter key
document.getElementById('orderForm').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
    }
});

// =====================================================
// Get all products from PHP and store them for search
let allProducts = [];

// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
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

    searchInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const searchTerm = e.target.value.toLowerCase().trim();
            filterAndDisplayProducts(searchTerm);
        }, 300);
    });

    // Initial display of all products
    filterAndDisplayProducts('');
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

// Enhanced display function with better UI feedback
function displayProducts(products) {
    const productGrid = document.getElementById('productGrid');
    
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

    productGrid.innerHTML = products.map(product => `
        <div class="product-card" data-product-id="${product.id}">
            <img src="uploads/products/${product.image || 'default.jpg'}" 
                 onerror="this.src='/api/placeholder/400/300'"
                 alt="${escapeHtml(product.name)}" 
                 class="product-image">
            <div class="product-info">
                <div class="product-name">${escapeHtml(product.name)}</div>
                <div class="product-price">EGP ${product.price.toFixed(2)}</div>
                <button class="add-to-cart" onclick="quickAddToOrder(${product.id}, '${escapeHtml(product.name)}', ${product.price})">
                    Add to Order
                </button>
            </div>
        </div>
    `).join('');
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

// Add these styles to your existing CSS
const styles = `
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.adding-to-cart {
    animation: addToCartPulse 0.5s ease;
}

@keyframes addToCartPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.no-results {
    grid-column: 1 / -1;
    padding: 2rem;
    text-align: center;
}

.search-input:focus {
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.product-grid {
    opacity: 1;
    transition: opacity 0.3s ease;
}

.product-grid.loading {
    opacity: 0.5;
}
`;


</script>
</body>
</html>