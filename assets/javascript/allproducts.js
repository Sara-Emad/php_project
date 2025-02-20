function confirmDelete(productId) {
  if (confirm('Are you sure you want to delete this product?')) {
      window.location.href = 'allproducts.php?action=delete&product_id=' + productId;
      alert("The product deleted successfully");
  }
}