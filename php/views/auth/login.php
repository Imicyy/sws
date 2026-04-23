<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Log In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="/files/assets/css/login.css?v=20260418-ui3">
  <style>
    body .main-container {
      min-height: 100vh !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 1.5rem !important;
    }

    body .main-container .login-container {
      max-width: 920px !important;
      min-height: 560px !important;
      display: flex !important;
      gap: 0 !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      border: 1px solid rgba(255, 255, 255, 0.5) !important;
      box-shadow: 0 22px 48px rgba(0, 16, 54, 0.32) !important;
      margin: 0 auto !important;
    }

    body .main-container .login-image,
    body .main-container .login-form {
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    body .main-container .login-image {
      background: linear-gradient(180deg, rgba(204, 185, 136, 0.72) 0%, rgba(180, 166, 136, 0.58) 100%) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.35) !important;
      flex: 1.02 !important;
    }

    body .main-container .login-form {
      background: #fff !important;
      flex: 0.98 !important;
      padding: 2.8rem 2.6rem !important;
    }

    body .main-container .login-image .logo-circle {
      width: 92px !important;
      height: 92px !important;
      border-radius: 50% !important;
      background: rgba(255, 255, 255, 0.9) !important;
      border: 3px solid rgba(242, 191, 18, 0.95) !important;
      box-shadow: 0 10px 24px rgba(11, 25, 44, 0.18) !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0 auto 1rem !important;
      padding: 10px !important;
    }

    body .main-container .login-image .logo-circle img {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
      border-radius: 50% !important;
      display: block !important;
      margin: 0 !important;
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="login-container">
      <div class="login-image">
        <div class="logo-circle">
          <img src="/files/assets/images/logo-ebmag.png" alt="Municipality of Enrique B. Magalona seal" class="logo" onerror="this.onerror=null;this.src='/assets/images/logo-ebmag.png';">
        </div>
        <h2>Municipality of</h2>
        <h1 class="mb-4">Enrique B. Magalona Social Welfare System</h1>
        <p>Saraviahanon Digitalasyon Para sa Asenso</p>
        <div class="login-hint mt-4">
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
        <div class="mt-3 text-center login-cta">
          <a href="/register">Create account</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);
      const email = formData.get('email');
      const password = formData.get('password');
      const role = formData.get('role');

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ email, password, role })
        });

        if (response.redirected) {
          window.location.href = response.url;
          return;
        }

        const data = await response.json();

        if (data.success && data.verificationRequired) {
          const codePrompt = await Swal.fire({
            icon: 'info',
            title: 'Verification Required',
            html: '<p>A verification code has been sent to your email.</p><p>Enter the Code</p>',
            input: 'text',
            inputLabel: '6-digit verification code',
            inputPlaceholder: 'Enter the code',
            inputAttributes: {
              maxlength: 6,
              autocapitalize: 'off',
              autocorrect: 'off',
              inputmode: 'numeric'
            },
            showCancelButton: true,
            confirmButtonText: 'Enter Code',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            preConfirm: (value) => {
              if (!value || !String(value).trim()) {
                Swal.showValidationMessage('Verification code is required');
              }
              return String(value).trim();
            }
          });

          if (!codePrompt.isConfirmed) return;

          const verifyResponse = await fetch('/verify-login-code', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ code: codePrompt.value })
          });

          const verifyData = await verifyResponse.json();

          if (!verifyResponse.ok || !verifyData.success) {
            Swal.fire({
              icon: 'error',
              title: 'Verification Failed',
              text: verifyData.error || 'Invalid verification code',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Try Again'
            });
            return;
          }

          if (verifyData.redirectUrl) {
            window.location.href = verifyData.redirectUrl;
          }
          return;
        }

        if (!data.success) {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.error || 'Invalid credentials',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Try Again'
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred during login',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
        console.error('Login error:', error);
      }
    });

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
