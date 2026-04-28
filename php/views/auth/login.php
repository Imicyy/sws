<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Log In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    body {
      margin: 0 !important;
      min-height: 100vh !important;
      background-image:
        linear-gradient(180deg, rgba(238, 193, 30, 0.72) 0%, rgba(19, 35, 121, 0.74) 100%),
        url("<?= htmlspecialchars(asset_url('images/ebmagtownhall.png'), ENT_QUOTES) ?>") !important;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    body .main-container {
      min-height: 100vh !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 1.5rem !important;
    }

    body .main-container .login-container {
      max-width: 930px !important;
      width: 100% !important;
      min-height: 430px !important;
      display: flex !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      border: 1px solid rgba(255, 255, 255, 0.45) !important;
      box-shadow: 0 20px 48px rgba(7, 18, 51, 0.45) !important;
      backdrop-filter: blur(4px) !important;
      margin: 0 auto !important;
    }

    body .main-container .login-image,
    body .main-container .login-form {
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    body .main-container .login-image {
      flex: 1.04 !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      padding: 2.2rem 2rem !important;
      color: #ffffff !important;
      background:
        linear-gradient(170deg, rgba(248, 211, 76, 0.22) 0%, rgba(255, 255, 255, 0.07) 44%, rgba(13, 29, 110, 0.34) 100%),
        rgba(7, 22, 77, 0.18) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.35) !important;
    }

    body .main-container .login-image h2 {
      color: #0c2f8e !important;
      font-size: 2.35rem !important;
      font-weight: 700 !important;
      line-height: 1.2 !important;
      margin-bottom: 0.65rem !important;
      text-shadow: 0 2px 8px rgba(255, 255, 255, 0.35) !important;
    }

    body .main-container .login-image h2 .text-gold {
      color: #f0bc00 !important;
      display: inline-block !important;
    }

    body .main-container .login-image p {
      margin: 0 !important;
      font-size: 0.82rem !important;
      color: #27347d !important;
      font-style: italic !important;
      font-weight: 500 !important;
    }

    body .main-container .login-form {
      background: #ffffff !important;
      flex: 0.96 !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
      padding: 2.7rem 2.4rem !important;
    }

    body .main-container .login-form h3 {
      color: #6a6a6a !important;
      font-size: 2rem !important;
      font-weight: 500 !important;
      margin-bottom: 1.75rem !important;
    }

    body .main-container .login-image .logo-circle {
      width: 118px !important;
      height: 118px !important;
      border-radius: 50% !important;
      background: rgba(255, 255, 255, 0.94) !important;
      border: 3px solid rgba(242, 191, 18, 0.95) !important;
      box-shadow: 0 10px 24px rgba(11, 25, 44, 0.18) !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0 auto 1.25rem !important;
      padding: 9px !important;
    }

    body .main-container .login-image .logo-circle img {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
      border-radius: 50% !important;
      display: block !important;
      margin: 0 !important;
    }

    body .main-container .form-group {
      position: relative !important;
      margin-bottom: 1rem !important;
    }

    body .main-container .form-group .form-control {
      border: none !important;
      border-bottom: 1px solid #d4d4d4 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      padding: 0.65rem 2rem 0.65rem 1.75rem !important;
      font-size: 0.95rem !important;
      color: #3a3a3a !important;
      background: transparent !important;
    }

    body .main-container .form-group .fa-envelope,
    body .main-container .form-group .fa-lock {
      position: absolute !important;
      left: 0.25rem !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      color: #9f9f9f !important;
      font-size: 0.82rem !important;
    }

    body .main-container .password-toggle {
      top: 50% !important;
      right: 0.25rem !important;
      transform: translateY(-50%) !important;
      color: #9f9f9f !important;
      background: none !important;
      border: 0 !important;
    }

    body .main-container .btn-login {
      margin-top: 1.2rem !important;
      border: 0 !important;
      border-radius: 24px !important;
      padding: 0.75rem 1rem !important;
      font-weight: 700 !important;
      letter-spacing: 0.08em !important;
      text-transform: uppercase !important;
      color: #fff !important;
      background: linear-gradient(90deg, #f4c000 0%, #e2b100 100%) !important;
      box-shadow: 0 7px 16px rgba(226, 177, 0, 0.35) !important;
    }

    body .main-container .login-hint {
      display: none !important;
    }

    @media (max-width: 991.98px) {
      body .main-container .login-container {
        max-width: 640px !important;
        min-height: 0 !important;
        flex-direction: column !important;
      }

      body .main-container .login-image {
        border-right: 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.35) !important;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="login-container">
      <div class="login-image">
        <div class="logo-circle">
          <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>" alt="Municipality of Enrique B. Magalona seal" class="logo">
        </div>
        <h2>Municipality of<br><span class="text-gold">Enrique B. Magalona<br>Social Welfare System</span></h2>
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
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

    (function () {
      const params = new URLSearchParams(window.location.search);
      const verify = params.get('verify');
      if (!verify || typeof Swal === 'undefined') return;

      if (verify === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Email verified',
          text: 'Your account is verified. You can now sign in.',
          confirmButtonColor: '#2962ff'
        });
      } else if (verify === 'already') {
        Swal.fire({
          icon: 'info',
          title: 'Already verified',
          text: 'Your email is already verified. Please sign in.',
          confirmButtonColor: '#2962ff'
        });
      } else if (verify === 'expired') {
        Swal.fire({
          icon: 'warning',
          title: 'Verification expired',
          text: 'The verification link has expired. Please contact the administrator.',
          confirmButtonColor: '#2962ff'
        });
      } else if (verify === 'invalid') {
        Swal.fire({
          icon: 'error',
          title: 'Invalid verification link',
          text: 'The verification link is invalid.',
          confirmButtonColor: '#2962ff'
        });
      } else if (verify === 'error') {
        Swal.fire({
          icon: 'error',
          title: 'Verification failed',
          text: 'Unable to verify this email right now. Please try again later.',
          confirmButtonColor: '#2962ff'
        });
      }
    })();
  </script>
</body>
</html>
