<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social Welfare System - Log In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="/files/assets/css/login.css">
</head>
<body>
  <div class="main-container">
    <div class="login-container">
      <div class="login-image">
        <img src="/files/assets/images/SilayLogo.jpg" alt="Logo" class="logo">
        <h2>Municipality of</h2>
        <h1 class="mb-4">Enrique B. Magalona Social Welfare System</h1>
        <p>Saraviahanon Digitalasyon Para sa Asenso</p>
        <div class="mt-4" style="font-size: 13px; opacity: 0.9; line-height: 1.6;">
          <div><strong>Test logins:</strong></div>
          <div>Super Admin: qa.migration@example.com</div>
          <div>Password: QaPass123!</div>
        </div>
      </div>
      <div class="login-form">
        <h3 class="text-center mb-4">Login to Your Account</h3>
        <form method="POST" action="/login" id="loginForm">
          <input type="hidden" name="role" id="selected-role" value="user">
          <div class="form-group">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
            <i class="fas fa-envelope"></i>
          </div>
          <div class="form-group password-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            <i class="fas fa-lock"></i>
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
              <i class="fas fa-eye" id="togglePasswordIcon"></i>
            </button>
          </div>
          <button type="submit" class="btn btn-login btn-block">Sign In</button>
        </form>
        <div class="mt-3 text-center">
          <a href="/register">Create account</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="/files/assets/js/auth.js"></script>
  <script>
    (function () {
      const passwordInput = document.getElementById('password');
      const toggleBtn = document.getElementById('togglePassword');
      const toggleIcon = document.getElementById('togglePasswordIcon');

      if (!passwordInput || !toggleBtn || !toggleIcon) return;

      toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        toggleIcon.classList.toggle('fa-eye', !isHidden);
        toggleIcon.classList.toggle('fa-eye-slash', isHidden);
        toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
      });
    })();
  </script>
</body>
</html>
