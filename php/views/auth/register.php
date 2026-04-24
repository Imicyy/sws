<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
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
      max-width: 930px !important;
      width: 100% !important;
      min-height: 460px !important;
      display: flex !important;
      gap: 0 !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      border: 1px solid rgba(255, 255, 255, 0.5) !important;
      box-shadow: 0 20px 48px rgba(7, 18, 51, 0.45) !important;
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
        linear-gradient(170deg, rgba(248, 211, 76, 0.2) 0%, rgba(255, 255, 255, 0.07) 45%, rgba(13, 29, 110, 0.27) 100%),
        linear-gradient(180deg, rgba(244, 240, 223, 0.78) 0%, rgba(229, 233, 246, 0.7) 100%) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.35) !important;
      flex: 0.95 !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      padding: 2rem 1.5rem !important;
    }

    body .main-container .register-form {
      background: #fff !important;
      flex: 1.05 !important;
      padding: 2rem 2.2rem 1.75rem !important;
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
      flex: 1 1 0;
      min-width: 78px;
      padding: 0.25rem 0.2rem;
      border: none;
      border-radius: 0;
      background: transparent;
      color: #444;
      font-weight: 600;
      font-size: 1rem;
      text-align: center;
      cursor: pointer;
      box-shadow: none;
      transition: color 0.2s ease, text-shadow 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .role-option:hover {
      color: #1b2f8a;
    }

    .role-option.active {
      color: #1b2f8a;
      text-decoration: underline;
      text-underline-offset: 4px;
      text-decoration-thickness: 2px;
      background: linear-gradient(90deg, rgba(255, 214, 51, 0.2) 0%, rgba(31, 58, 173, 0.18) 100%);
      border-radius: 8px;
      box-shadow:
        0 0 10px rgba(255, 214, 51, 0.55),
        0 0 14px rgba(37, 99, 235, 0.45);
      text-shadow:
        0 0 6px rgba(255, 214, 51, 0.65),
        0 0 8px rgba(37, 99, 235, 0.55);
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
      margin-bottom: 0.82rem !important;
      position: relative !important;
    }

    body .main-container .form-control,
    body .main-container .select-wrapper select {
      border: none !important;
      border-bottom: 1px solid #d4d4d4 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      background: transparent !important;
      font-size: 0.9rem !important;
      height: 36px !important;
      padding: 0.5rem 1.6rem 0.5rem 0.05rem !important;
    }

    body .main-container .form-group i,
    body .main-container .select-wrapper i {
      right: 0.2rem !important;
      left: auto !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      font-size: 0.78rem !important;
      color: #9f9f9f !important;
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
      margin-top: 0.65rem !important;
      border: 0 !important;
      border-radius: 8px !important;
      padding: 0.62rem 1rem !important;
      font-weight: 700 !important;
      font-size: 0.82rem !important;
      background: linear-gradient(90deg, #c29d1d 0%, #b99013 100%) !important;
      color: #fff !important;
      box-shadow: 0 7px 16px rgba(185, 144, 19, 0.32) !important;
      text-transform: none !important;
    }

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
          <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>" alt="Municipality of Enrique B. Magalona seal" class="logo">
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
            <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
            <i class="fas fa-user"></i>
          </div>

          <div class="form-group" id="barangay-id-row" style="display: none;">
            <label for="barangay_id" class="sr-only">Barangay</label>
            <div class="select-wrapper">
              <select name="barangay_id" id="barangay_id">
                <option value="">Select your barangay</option>
                <?php if (!empty($barangayList) && is_array($barangayList)): ?>
                  <?php foreach ($barangayList as $barangay): ?>
                    <option value="<?= (int) ($barangay['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($barangay['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <i class="fas fa-map-marker-alt"></i>
            </div>
          </div>

          <div class="form-group" id="staff-type-row" style="display: none;">
            <label for="staff_classification" class="sr-only">Staff Type</label>
            <div class="select-wrapper">
              <select name="staff_classification" id="staff_classification">
                <option value="">Select your department</option>
                <option value="PDAO">PDAO</option>
                <option value="OSCA">OSCA</option>
              </select>
              <i class="fas fa-user-tie"></i>
            </div>
          </div>

          <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            <i class="fas fa-envelope"></i>
          </div>

          <div class="form-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            <i class="fas fa-lock"></i>
            <div class="password-strength"></div>
            <div class="requirements">
              <div class="requirement"><i class="fas fa-circle"></i> At least 8 characters</div>
              <div class="requirement"><i class="fas fa-circle"></i> Contains uppercase & lowercase</div>
              <div class="requirement"><i class="fas fa-circle"></i> Contains numbers</div>
            </div>
          </div>

          <div class="form-group">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            <i class="fas fa-lock"></i>
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
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: result.message || 'User created successfully',
                confirmButtonColor: '#2962ff'
              }).then(() => {
                window.location.href = '/';
              });
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
</body>
</html>
