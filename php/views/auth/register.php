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
  <link rel="stylesheet" type="text/css" href="/files/assets/css/register.css?v=20260418-ui3">
  <style>
    body .main-container {
      min-height: 100vh !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 1.5rem !important;
    }

    body .main-container .register-container {
      max-width: 980px !important;
      min-height: 600px !important;
      display: flex !important;
      gap: 0 !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      border: 1px solid rgba(255, 255, 255, 0.5) !important;
      box-shadow: 0 22px 48px rgba(0, 16, 54, 0.32) !important;
      margin: 0 auto !important;
    }

    body .main-container .register-image,
    body .main-container .register-form {
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    body .main-container .register-image {
      background: linear-gradient(180deg, rgba(204, 185, 136, 0.72) 0%, rgba(180, 166, 136, 0.58) 100%) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.35) !important;
      flex: 1.02 !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
    }

    body .main-container .register-form {
      background: #fff !important;
      flex: 1.18 !important;
      padding: 2rem 2.2rem !important;
    }

    body .main-container .register-image .logo {
      display: block !important;
      margin: 0 auto 1.2rem !important;
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

    .role-selector {
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      gap: 12px;
      margin-bottom: 1.5rem;
      overflow-x: auto;
      padding-bottom: 4px;
      scrollbar-width: none;
    }

    .role-selector::-webkit-scrollbar {
      display: none;
    }

    .role-option {
      flex: 1 1 0;
      min-width: 130px;
      padding: 14px 12px;
      border: 1px solid rgba(41, 98, 255, 0.22);
      border-radius: 16px;
      background: #f8fbff;
      color: #1f2937;
      font-weight: 600;
      text-align: center;
      cursor: pointer;
      box-shadow: 0 10px 20px rgba(17, 24, 39, 0.08);
      transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .role-option:hover {
      transform: translateY(-1px);
      border-color: rgba(41, 98, 255, 0.45);
    }

    .role-option.active {
      background: linear-gradient(135deg, #2962ff 0%, #1a237e 100%);
      color: #ffffff;
      border-color: #2962ff;
      box-shadow: 0 14px 28px rgba(41, 98, 255, 0.28);
      transform: translateY(-2px);
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

    @media (max-width: 768px) {
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
          <img src="/files/assets/images/logo-ebmag.png" alt="Municipality of Enrique B. Magalona seal" class="logo" onerror="this.onerror=null;this.src='/assets/images/logo-ebmag.png';">
        <div class="register-copy">
          <h2>Welcome to</h2>
          <h1 class="mb-4">Enrique B. Magalona</h1>
          <p>Social Welfare System Registration</p>
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
