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
      position: relative !important;
      z-index: 2 !important;
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
      /* left padding for the left icon, right padding for the toggle button */
      padding: 0.65rem 2.6rem 0.65rem 2.2rem !important;
      font-size: 0.95rem !important;
      color: #3a3a3a !important;
      background: transparent !important;
    }

    body .main-container .form-group .fa-envelope,
    body .main-container .form-group .fa-lock {
      position: absolute !important;
      left: 0.9rem !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      color: #9f9f9f !important;
      font-size: 0.95rem !important;
      pointer-events: none !important;
    }

    body .main-container .password-group { position: relative !important; }

    body .main-container .password-toggle {
      position: absolute !important;
      top: 50% !important;
      right: 0.6rem !important;
      transform: translateY(-50%) !important;
      color: #9f9f9f !important;
      background: none !important;
      border: 0 !important;
      padding: 0.15rem !important;
      z-index: 3 !important;
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

    /* Page outline and dense margin watermarks */
    .page-outline {
      position: fixed;
      inset: 8px;
      border: 1px solid rgba(0,0,0,0.04);
      border-radius: 8px;
      pointer-events: none;
      z-index: 0;
    }

    .watermark-side {
      position: fixed;
      pointer-events: none;
      z-index: 1;
      display: flex;
      gap: 4px;
      align-items: center;
      justify-content: center;
      color: rgba(80,80,80,0.28);
      font-weight: 800;
      letter-spacing: 1.5px;
      -webkit-text-stroke: 0.3px rgba(80,80,80,0.28);
      overflow: hidden;
      padding: 2px;
      mix-blend-mode: normal;
    }

    .watermark-left {
      left: 6px;
      top: 6px;
      bottom: 6px;
      width: 30px;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
    }

    .watermark-right {
      right: 6px;
      top: 6px;
      bottom: 6px;
      width: 30px;
      flex-direction: column-reverse;
      justify-content: space-between;
      align-items: center;
    }

    .watermark-top {
      top: 8px;
      /* leave more space for vertical side marks to avoid overlap */
      left: 64px;
      right: 64px;
      height: 38px;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      flex-wrap: wrap;
      gap: 2px;
      padding-left: 6px;
    }

    .watermark-bottom {
      bottom: 8px;
      /* leave more space for vertical side marks to avoid overlap */
      left: 64px;
      right: 64px;
      height: 30px;
      flex-direction: row-reverse;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .watermark-vertical span {
      writing-mode: vertical-rl;
      transform: rotate(180deg);
      font-size: 11px;
      line-height: 1;
      white-space: nowrap;
      display: block;
      color: rgba(80,80,80,0.36);
      opacity: 0.36;
    }

    .watermark-horizontal span {
      font-size: 11px;
      white-space: nowrap;
      display: inline-block;
      padding: 0 2px;
      color: rgba(80,80,80,0.36);
      opacity: 0.36;
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
  <div class="page-outline" aria-hidden="true"></div>

  <div class="watermark-side watermark-left watermark-vertical" aria-hidden="true">
    <!-- repeated to densely fill left margin -->
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
  </div>

  <div class="watermark-side watermark-right watermark-vertical" aria-hidden="true">
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
  </div>

  <div class="watermark-side watermark-top watermark-horizontal" aria-hidden="true">
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span>
  </div>

  <div class="watermark-side watermark-bottom watermark-horizontal" aria-hidden="true">
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
    <span>CCS - TALISAY</span><span>CCS - TALISAY</span><span>CCS - TALISAY</span>
  </div>

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
    
    // Intercept login form submit and show modal on error response (with fallback)
    (function () {
      const form = document.getElementById('loginForm');
      if (!form) return;

      form.addEventListener('submit', async function (e) {
        // If we've flagged to bypass AJAX, allow normal submit to proceed
        if (form.dataset.bypassAjax === '1') return;

        e.preventDefault();
        const email = (form.querySelector('input[name="email"]') || {}).value || '';
        const password = (form.querySelector('input[name="password"]') || {}).value || '';

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: String(email).trim(), password: String(password) })
          });

          const payload = await res.json().catch(() => ({}));

          if (res.ok && payload && payload.success === true) {
            window.location.href = payload.redirect || payload.redirectUrl || '/';
            return;
          }

          const msg = (payload && (payload.error || payload.message)) || 'Invalid credentials';
          if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Login Failed', text: String(msg), confirmButtonColor: '#2962ff' });
          } else {
            alert('Login Failed: ' + String(msg));
          }

          // If server returned a non-JSON redirect or unexpected response, fall back to normal submit
          form.dataset.bypassAjax = '1';
          form.submit();
        } catch (err) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to the server', confirmButtonColor: '#2962ff' });
          } else {
            alert('Network Error: Could not connect to the server');
          }

          // Try a normal form submit as fallback (will follow server redirects)
          form.dataset.bypassAjax = '1';
          form.submit();
        }
      });
    })();
  </script>
  <footer class="site-footer text-center" style="position:fixed;left:0;right:0;bottom:18px;z-index:1;pointer-events:none;">
    <div style="background:rgba(0,0,0,0.28);color:#fff;padding:6px 12px;border-radius:6px;display:inline-block;">
      © 2026 Social Welfare System. All Rights Reserved.
    </div>
  </footer>
</body>
</html>
