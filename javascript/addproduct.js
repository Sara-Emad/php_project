document.getElementById("addProduct").addEventListener("submit", function (e) {
    let isValid = true;

    let name = document.getElementById("productName").value.trim();
    let price = document.getElementById("price").value.trim();
    let category = document.getElementById("category").value;
    let image = document.getElementById("image").files[0];

    if (name.length < 3) {
        alert("Product name must be at least 3 characters long.");
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
