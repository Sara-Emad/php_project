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

// Store all products globally so we can filter them
// Get all products from the select element
function getAllProducts()
 {
      const productSelect = document.querySelector('select[name="product_id"]');
      const products = Array.from(productSelect.options)
        .filter(option => option.value) // Skip the "Select Product" option
        .map(option => {
          const [name, price] = option.text.split(' - EGP ');
          return {
            id: option.value,
            name: name.trim(),
            price: parseFloat(price),
            image: `/api/placeholder/400/300`
          };
        });
      return products;
  }

    // Create product card element
    function createProductCard(product) {
      return `
        <div class="product-card">
          <img src="${product.image}" alt="${product.name}" class="product-image">
          <div class="product-info">
            <div class="product-name">${product.name}</div>
            <div class="product-price">EGP ${product.price.toFixed(2)}</div>
            <button 
              class="add-to-cart"
              onclick="quickAddToOrder(${product.id}, '${product.name}', ${product.price})"
            >
              Add to Order
            </button>
          </div>
        </div>
      `;
    }

    // Filter and display products
    function filterProducts(searchTerm) {
      const products = getAllProducts();
      const filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
      
      const productGrid = document.getElementById('productGrid');
      
      if (filteredProducts.length === 0) {
        productGrid.innerHTML = '<div class="no-results">No products found</div>';
        return;
      }
      
      productGrid.innerHTML = filteredProducts.map(product => 
        createProductCard(product)
      ).join('');
    }

    // Quick add to order function
    function quickAddToOrder(productId, productName, productPrice) {
      const productSelect = document.querySelector('select[name="product_id"]');
      const quantityInput = document.querySelector('input[name="quantity"]');
      
      if (productSelect && quantityInput) {
        productSelect.value = productId;
        quantityInput.value = "1";
        // Trigger the existing addToOrder function
        addToOrder();
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      // Display all products initially
      filterProducts('');
      
      // Add search input event listener
      const searchInput = document.getElementById('productSearch');
      let debounceTimeout;
      
      searchInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
          filterProducts(e.target.value);
        }, 300); // Debounce for better performance
      });
    });
