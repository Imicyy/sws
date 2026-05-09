<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Register</title>
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

    body .main-container .register-container {
      max-width: 980px !important;
      width: 100% !important;
      min-height: 520px !important;
      display: flex !important;
      gap: 0 !important;
      border-radius: 18px !important;
      overflow: hidden !important;
      border: 0 !important;
      backdrop-filter: blur(6px) saturate(1.05);
      box-shadow: 0 12px 36px rgba(9, 30, 66, 0.28) !important;
      margin: 0 auto !important;
    }

    body .main-container .register-image,
    body .main-container .register-form {
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    body .main-container .register-image {
      background:
        linear-gradient(170deg, rgba(248, 211, 76, 0.12) 0%, rgba(255, 255, 255, 0.03) 45%, rgba(13, 29, 110, 0.14) 100%),
        linear-gradient(180deg, rgba(244, 240, 223, 0.6) 0%, rgba(229, 233, 246, 0.5) 100%) !important;
      border-right: none !important;
      flex: 1 !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      padding: 2.5rem 1.8rem !important;
    }

    body .main-container .register-form {
      background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,255,255,0.98));
      flex: 1.05 !important;
      padding: 2.4rem 2.6rem 2rem !important;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    body .main-container .register-image .logo-circle {
      width: 136px !important;
      height: 136px !important;
      border-radius: 50% !important;
      background: rgba(255, 255, 255, 0.95) !important;
      border: 3px solid rgba(242, 191, 18, 0.95) !important;
      box-shadow: 0 10px 24px rgba(11, 25, 44, 0.2) !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0 auto 1.2rem !important;
      padding: 10px !important;
    }

    body .main-container .register-image .logo {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
      border-radius: 50% !important;
      display: block !important;
    }

    body .main-container .register-image .register-copy {
      width: 100% !important;
      margin: 0 auto !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      text-align: center !important;
    }

    body .main-container .register-image .register-copy h2,
    body .main-container .register-image .register-copy h1,
    body .main-container .register-image .register-copy p {
      width: 100% !important;
      text-align: center !important;
      margin-left: auto !important;
      margin-right: auto !important;
    }

    body .main-container .register-form h3 {
      color: #383838 !important;
      font-size: 2rem !important;
      font-weight: 600 !important;
      margin-bottom: 1.1rem !important;
    }

    .role-selector {
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      justify-content: space-between;
      gap: 0.55rem;
      margin-bottom: 1.1rem;
      overflow-x: auto;
      padding-bottom: 4px;
      scrollbar-width: none;
    }

    .role-selector::-webkit-scrollbar {
      display: none;
    }

    .role-option {
      flex: 0 0 auto;
      min-width: 110px;
      padding: 0.45rem 0.9rem;
      border: 1px solid rgba(27,47,138,0.08);
      border-radius: 999px;
      background: rgba(255,255,255,0.6);
      color: #273150;
      font-weight: 700;
      font-size: 0.95rem;
      text-align: center;
      cursor: pointer;
      box-shadow: 0 6px 14px rgba(12,34,82,0.06);
      transition: transform 160ms ease, background-color 180ms ease, box-shadow 180ms ease;
    }

    .role-option:hover {
      color: #1b2f8a;
    }

    .role-option.active {
      color: #fff;
      background: linear-gradient(90deg,#2b60ff 0%, #1b2f8a 100%);
      transform: translateY(-2px);
      box-shadow: 0 10px 24px rgba(27,47,138,0.18), 0 2px 6px rgba(11,25,44,0.06);
    }

    .register-copy {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .register-copy h2,
    .register-copy h1,
    .register-copy p {
      width: 100%;
      text-align: center;
      margin-left: 0;
      margin-right: 0;
    }

    .register-copy h2,
    .register-copy h1 {
      margin-bottom: 0.5rem;
    }

    .register-copy p {
      margin-bottom: 0;
    }

    body .main-container .register-image .register-copy h2 {
      color: #23398f !important;
      font-size: 2rem !important;
      line-height: 1.1 !important;
      font-weight: 700 !important;
    }

    body .main-container .register-image .register-copy h1 {
      color: #d5a90d !important;
      font-size: 2.1rem !important;
      line-height: 1.05 !important;
      font-weight: 700 !important;
      margin-bottom: 0.65rem !important;
    }

    body .main-container .register-image .register-copy p {
      color: #243983 !important;
      font-size: 0.86rem !important;
      font-weight: 500 !important;
      line-height: 1.3 !important;
    }

    body .main-container .form-group {
      margin-bottom: 0.9rem !important;
      position: relative !important;
    }

    /* Modern input boxes */
    body .main-container .form-control,
    body .main-container .select-wrapper select {
      border: 1px solid rgba(39,57,88,0.08) !important;
      background: #ffffff !important;
      border-radius: 12px !important;
      box-shadow: 0 6px 18px rgba(15,35,75,0.04) inset !important;
      font-size: 0.96rem !important;
      height: 48px !important;
      padding: 0.6rem 1.2rem 0.6rem 3.2rem !important; /* space for left icon */
      transition: border-color 200ms cubic-bezier(.2,.9,.2,1), box-shadow 200ms cubic-bezier(.2,.9,.2,1), transform 120ms ease;
    }

    body .main-container .form-control:focus {
      outline: none !important;
      border-color: rgba(43,96,255,0.9) !important;
      box-shadow: 0 10px 28px rgba(43,96,255,0.06) !important;
      background: #ffffff !important;
      transform: translateY(-1px);
    }

    /* unified input wrapper: keeps icons contained inside the input field */
    .input-wrapper { position: relative; }
    .input-wrapper .input-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      color: #9aa6c7;
      pointer-events: none;
      z-index: 3;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .input-wrapper .input-icon.right {
      left: auto;
      right: 12px;
    }

    /* select wrapper should also be positioned so right icons stay inside */
    body .main-container .select-wrapper { position: relative; }
    body .main-container .select-wrapper .input-icon.right {
      position: absolute !important;
      right: 10px !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      font-size: 0.95rem !important;
      color: #9f9f9f !important;
      pointer-events: none !important;
    }

    /* Password toggle placed inside the wrapper on the right */
    .input-wrapper .password-toggle {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      border: 0;
      background: transparent;
      color: #6b7280;
      padding: 6px;
      cursor: pointer;
      z-index: 5;
    }
    .input-wrapper .password-toggle:focus { outline: none; }

    /* ensure inputs have space for left and right icons */
    .input-wrapper .form-control {
      padding-left: 3.2rem !important;
      padding-right: 3.2rem !important;
      box-sizing: border-box;
    }

    /* password-strength bar sits below the input and uses a pseudo element for the fill */
    .password-strength {
      height: 6px;
      width: 100%;
      border-radius: 6px;
      background: linear-gradient(90deg, rgba(14,50,110,0.06), rgba(14,50,110,0.02));
      margin-top: 0.5rem;
      overflow: hidden;
    }
    .password-strength::before {
      content: '';
      display: block;
      height: 100%;
      width: var(--strength-width, 0%);
      background: var(--strength-color, #ef4444);
      transition: width 160ms linear;
    }

    body .main-container .requirements {
      margin-top: 0.35rem !important;
    }

    body .main-container .requirement {
      font-size: 0.72rem !important;
      margin-bottom: 0.15rem !important;
      color: #666 !important;
    }

    body .main-container .requirement i {
      color: #9ca3af !important;
      font-size: 0.5rem !important;
      margin-right: 0.4rem !important;
      transition: color 0.2s ease !important;
    }

    body .main-container .requirement.valid {
      color: #15803d !important;
    }

    body .main-container .requirement.valid i {
      color: #22c55e !important;
    }

    body .main-container .btn-register {
      margin-top: 0.95rem !important;
      border: 0 !important;
      border-radius: 12px !important;
      padding: 0.72rem 1rem !important;
      font-weight: 800 !important;
      font-size: 0.95rem !important;
      background: linear-gradient(90deg,#2b60ff 0%, #1b2f8a 100%) !important;
      color: #fff !important;
      box-shadow: 0 10px 30px rgba(27,47,138,0.18) !important;
      text-transform: none !important;
    }
    body .main-container .btn-register:hover { transform: translateY(-2px); }

    body .main-container .login-link {
      margin-top: 0.8rem !important;
      font-size: 0.78rem !important;
      color: #595959 !important;
    }

    body .main-container .login-link a {
      color: #1b2f8a !important;
      text-decoration: none !important;
      font-weight: 600 !important;
    }

    @media (max-width: 768px) {
      body .main-container .register-container {
        flex-direction: column !important;
        max-width: 640px !important;
      }

      body .main-container .register-image {
        border-right: 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.35) !important;
      }

      .role-selector {
        justify-content: flex-start;
      }

      .role-option {
        min-width: 125px;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="register-container">
      <div class="register-image">
        <div class="logo-circle">
          <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Municipality of Enrique B. Magalona seal" class="logo">
        </div>
        <div class="register-copy">
          <h2>Welcome to</h2>
          <h1 class="mb-4">Enrique B. Magalona</h1>
          <p>Social Welfare System (Senior Citizens &amp; PWDs)<br>Registration.</p>
        </div>
      </div>
      <div class="register-form">
        <h3 class="text-center mb-4">Create Your Account</h3>

        <div class="role-selector">
          <div class="role-option" data-role="Staff">Staff</div>
          <div class="role-option" data-role="Admin">Admin</div>
          <div class="role-option" data-role="Super Admin">Super Admin</div>
          <div class="role-option" data-role="Barangay">Barangay</div>
        </div>

        <form method="POST" action="/create-user" id="registerForm">
          <input type="hidden" name="role" id="selected-role" value="user">

          <div class="form-group">
            <div class="input-wrapper">
              <span class="input-icon"><i class="fas fa-user" aria-hidden="true"></i></span>
              <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
            </div>
          </div>

          <div class="form-group" id="barangay-id-row" style="display: none;">
            <label for="barangay_id" class="sr-only">Barangay</label>
            <div class="select-wrapper input-wrapper">
              <select class="form-control" name="barangay_id" id="barangay_id">
                <option value="">Select your barangay</option>
                <?php if (!empty($barangayList) && is_array($barangayList)): ?>
                  <?php foreach ($barangayList as $barangay): ?>
                    <option value="<?= (int) ($barangay['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($barangay['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <span class="input-icon right"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
            </div>
          </div>

          <div class="form-group" id="staff-type-row" style="display: none;">
            <label for="staff_classification" class="sr-only">Staff Type</label>
            <div class="select-wrapper input-wrapper">
              <select class="form-control" name="staff_classification" id="staff_classification">
                <option value="">Select your department</option>
                <option value="PDAO">PDAO</option>
                <option value="OSCA">OSCA</option>
              </select>
              <span class="input-icon right"><i class="fas fa-user-tie" aria-hidden="true"></i></span>
            </div>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <span class="input-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
              <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            </div>
          </div>

          <div class="form-group password-group">
            <div class="input-wrapper">
              <span class="input-icon"><i class="fas fa-lock" aria-hidden="true"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
              <button type="button" class="password-toggle" id="toggleRegisterPassword" aria-label="Show password">
                <i class="fas fa-eye" id="toggleRegisterPasswordIcon"></i>
              </button>
              <div class="password-strength"></div>
            </div>
            <div class="requirements">
              <div class="requirement"><i class="fas fa-circle"></i> At least 8 characters</div>
              <div class="requirement"><i class="fas fa-circle"></i> Contains uppercase & lowercase</div>
              <div class="requirement"><i class="fas fa-circle"></i> Contains numbers</div>
            </div>
          </div>

          <div class="form-group password-group">
            <div class="input-wrapper">
              <span class="input-icon"><i class="fas fa-lock" aria-hidden="true"></i></span>
              <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
              <button type="button" class="password-toggle" id="toggleConfirmPassword" aria-label="Show confirm password">
                <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-register btn-block">Create Account</button>

          <div class="login-link" style="display: block; width: 100%; text-align: center;">
            Already have an account? <a href="/">Sign In</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="/files/assets/js/register.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const roleOptions = document.querySelectorAll('.role-option');
      const selectedRoleInput = document.getElementById('selected-role');
      const barangayRow = document.getElementById('barangay-id-row');
      const staffTypeRow = document.getElementById('staff-type-row');
      const barangaySelect = document.getElementById('barangay_id');
      const staffSelect = document.getElementById('staff_classification');

      roleOptions.forEach(option => {
        option.addEventListener('click', function () {
          roleOptions.forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          roleOptions.forEach(opt => opt.setAttribute('aria-pressed', 'false'));
          this.setAttribute('aria-pressed', 'true');

          const role = this.getAttribute('data-role');
          selectedRoleInput.value = role;

          if (role === 'Barangay') {
            barangayRow.style.display = 'block';
            staffTypeRow.style.display = 'none';
            barangaySelect.required = true;
            staffSelect.required = false;
          } else if (role === 'Staff' || role === 'Admin') {
            barangayRow.style.display = 'none';
            staffTypeRow.style.display = 'block';
            barangaySelect.required = false;
            staffSelect.required = true;
          } else {
            barangayRow.style.display = 'none';
            staffTypeRow.style.display = 'none';
            barangaySelect.required = false;
            staffSelect.required = false;
          }
        });
      });

      const passwordInput = document.getElementById('password');
      const requirements = document.querySelectorAll('.requirement');
      const strengthBar = document.querySelector('.password-strength');

      if (passwordInput) {
        passwordInput.addEventListener('input', function () {
          const password = this.value;
          let strength = 0;

          if (password.length >= 8) {
            requirements[0].classList.add('valid');
            strength += 33;
          } else {
            requirements[0].classList.remove('valid');
          }

          if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            requirements[1].classList.add('valid');
            strength += 33;
          } else {
            requirements[1].classList.remove('valid');
          }

          if (/\d/.test(password)) {
            requirements[2].classList.add('valid');
            strength += 34;
          } else {
            requirements[2].classList.remove('valid');
          }

          if (strengthBar) {
            strengthBar.style.setProperty('--strength-width', strength + '%');
            if (strength < 34) {
              strengthBar.style.setProperty('--strength-color', '#ef4444');
            } else if (strength < 67) {
              strengthBar.style.setProperty('--strength-color', '#f59e0b');
            } else {
              strengthBar.style.setProperty('--strength-color', '#10b981');
            }
          }
        });
      }

      const form = document.getElementById('registerForm');
      if (form) {
        form.addEventListener('submit', async function (e) {
          e.preventDefault();

          const password = document.getElementById('password').value;
          const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
          const selectedRole = selectedRoleInput.value;

          if (selectedRole === 'user') {
            Swal.fire({ icon: 'warning', title: 'Role Required', text: 'Please select a role (Staff, Admin, Super Admin, or Barangay)', confirmButtonColor: '#2962ff' });
            return false;
          }

          if (password !== confirmPassword) {
            Swal.fire({ icon: 'error', title: 'Password Mismatch', text: 'Passwords do not match!', confirmButtonColor: '#2962ff' });
            return false;
          }

          if (password.length < 8 || !/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/\d/.test(password)) {
            Swal.fire({ icon: 'warning', title: 'Weak Password', text: 'Please ensure your password meets all requirements', confirmButtonColor: '#2962ff' });
            return false;
          }

          if (selectedRole === 'Barangay' && !barangaySelect.value) {
            Swal.fire({ icon: 'warning', title: 'Barangay Required', text: 'Please select your barangay', confirmButtonColor: '#2962ff' });
            return false;
          }

          if ((selectedRole === 'Staff' || selectedRole === 'Admin') && !staffSelect.value) {
            Swal.fire({ icon: 'warning', title: 'Department Required', text: 'Please select your department', confirmButtonColor: '#2962ff' });
            return false;
          }

          const formData = new FormData(form);
          const data = Object.fromEntries(formData.entries());

          if (data.role !== 'Barangay') {
            delete data.barangay_id;
          }
          if (data.role !== 'Staff' && data.role !== 'Admin') {
            delete data.staff_classification;
          }

          try {
            const response = await fetch(form.action, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
            });

            const result = await response.json();
            const errorMessage = String(result.error || 'Something went wrong');
            const isDuplicateEmail = /email already exists/i.test(errorMessage);

            if (response.ok || result.success === true) {
              const createdEmail = String(data.email || '').trim();
              await Swal.fire({
                icon: 'success',
                title: 'Account Created',
                text: result.message || 'Enter the verification code sent to your email.',
                confirmButtonColor: '#2962ff'
              });

              const verifyResult = await Swal.fire({
                title: 'Email Verification Code',
                input: 'text',
                inputLabel: 'Enter the 6-digit code sent to your email',
                inputPlaceholder: 'e.g. 123456',
                inputAttributes: {
                  maxlength: '6',
                  autocapitalize: 'off',
                  autocorrect: 'off'
                },
                confirmButtonText: 'Verify',
                showCancelButton: false,
                allowOutsideClick: false,
                confirmButtonColor: '#2962ff',
                preConfirm: async (code) => {
                  const normalizedCode = String(code || '').trim();
                  if (!/^\d{6}$/.test(normalizedCode)) {
                    Swal.showValidationMessage('Please enter a valid 6-digit code.');
                    return false;
                  }
                  const verifyResponse = await fetch('/verify-email-code', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                      email: createdEmail,
                      code: normalizedCode
                    })
                  });
                  const verifyPayload = await verifyResponse.json().catch(() => ({}));
                  if (!verifyResponse.ok || verifyPayload.success !== true) {
                    Swal.showValidationMessage(String(verifyPayload.error || 'Verification failed. Please check your code.'));
                    return false;
                  }
                  return true;
                }
              });

              if (verifyResult.isConfirmed) {
                await Swal.fire({
                  icon: 'success',
                  title: 'Verified',
                  text: 'Email verified successfully. You can now sign in.',
                  confirmButtonColor: '#2962ff'
                });
                window.location.href = '/';
              }
              return;
            }

            Swal.fire({
              icon: isDuplicateEmail ? 'warning' : 'error',
              title: isDuplicateEmail ? 'Email already exists' : 'Error',
              text: isDuplicateEmail ? 'Email already exists' : errorMessage,
              confirmButtonColor: '#2962ff'
            });
          } catch (error) {
            Swal.fire({
              icon: 'error',
              title: 'Network Error',
              text: 'Could not connect to the server',
              confirmButtonColor: '#2962ff'
            });
          }
        });
      }
    });
  </script>
  <script>
    (function () {
      const pwd = document.getElementById('password');
      const toggle = document.getElementById('toggleRegisterPassword');
      const toggleIcon = document.getElementById('toggleRegisterPasswordIcon');
      const confirm = document.getElementById('confirm_password');
      const toggleConfirm = document.getElementById('toggleConfirmPassword');
      const toggleConfirmIcon = document.getElementById('toggleConfirmPasswordIcon');

      if (toggle && pwd) {
        toggle.addEventListener('click', function () {
          const hidden = pwd.type === 'password';
          pwd.type = hidden ? 'text' : 'password';
          toggleIcon.classList.toggle('fa-eye-slash', hidden);
          toggleIcon.classList.toggle('fa-eye', !hidden);
        });
      }

      if (toggleConfirm && confirm) {
        toggleConfirm.addEventListener('click', function () {
          const hidden = confirm.type === 'password';
          confirm.type = hidden ? 'text' : 'password';
          toggleConfirmIcon.classList.toggle('fa-eye-slash', hidden);
          toggleConfirmIcon.classList.toggle('fa-eye', !hidden);
        });
      }
    })();
  </script>
  <footer class="site-footer text-center" style="position:fixed;left:0;right:0;bottom:18px;z-index:1;pointer-events:none;">
    <div style="background:rgba(0,0,0,0.28);color:#fff;padding:6px 12px;border-radius:6px;display:inline-block;">
      © 2026 Social Welfare System. All Rights Reserved.
    </div>
  </footer>
</body>
</html>
