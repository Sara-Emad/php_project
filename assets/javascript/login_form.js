document.getElementById('loginForm').addEventListener('submit', function(event) {
  var email = document.getElementById('email').value.trim();
  var password = document.getElementById('password').value.trim();
  var isValid = true;

  var errorMessages = document.querySelectorAll('.error-message');
  errorMessages.forEach(function(error) {
      error.remove();
  });

  if (email === '') {
      showError('email', 'Email is required.');
      isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      showError('email', 'Invalid email format.');
      isValid = false;
  }

  if (password === '') {
      showError('password', 'Password is required.');
      isValid = false;
  }

  if (!isValid) {
      event.preventDefault();
  }
});

function showError(fieldId, message) {
  var field = document.getElementById(fieldId);
  var error = document.createElement('div');
  error.className = 'error-message text-danger mt-1';
  error.innerText = message;
  field.parentNode.appendChild(error);
}