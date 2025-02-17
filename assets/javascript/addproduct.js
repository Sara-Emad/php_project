document.getElementById("addProduct").addEventListener("submit", function (e) {
    let isValid = true;

    let product_name = document.getElementById("product_name").value.trim();
    let price = document.getElementById("product_price").value.trim();
    let category = document.getElementById("category").value;
    let quantity = document.getElementById("quantity").value.trim();
    let image = document.getElementById("image").files[0];

    if (product_name.length < 3) {
        alert("Product name must be at least 3 characters long.");
        console.log('hello');
        isValid = false;
    }

    if (price === "" || isNaN(price) || parseFloat(price) <= 0) {
        alert("Please enter a valid positive price.");
        isValid = false;
    }

    if (!category) {
        alert("Please select a valid category.");
        isValid = false;
    }

    if (quantity === "" || isNaN(quantity) || parseInt(quantity) <= 0) {
        alert("Please enter a valid positive quantity.");
        isValid = false;
    }
    
    if (parseInt(quantity) > 1000) {
        alert("Quantity cannot exceed 1000 items.");
        isValid = false;
    }

    if (!Number.isInteger(parseFloat(quantity))) {
        alert("Quantity must be a whole number.");
        isValid = false;
    }

    if (!image) {
        alert("Please upload an image.");
        isValid = false;
    } else {
        let allowedExtensions = ["image/jpeg", "image/png", "image/jpg"];
        if (!allowedExtensions.includes(image.type)) {
        alert("Only JPG, JPEG, and PNG images are allowed.");
        isValid = false;
        }
    }

    if (!isValid) {
        e.preventDefault();
    }
});
