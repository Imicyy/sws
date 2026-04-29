<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title><?= htmlspecialchars((string)($title ?? 'Superadmin Users'), ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="/files/assets/css/user.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: linear-gradient(135deg, #fffde8 0%, #eef4ff 52%, #fff9d9 100%); display: flex; }
    
    /* Sidebar Styles */
    .sidebar { 
      width: 260px; 
      background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
      color: #1e3a8a; 
      min-height: 100vh; 
      padding: 20px 0; 
      position: fixed; 
      left: 0; 
      top: 0; 
      overflow-y: auto;
      border-right: 1px solid #dbe5f3;
      box-shadow: 10px 0 24px rgba(37, 99, 235, 0.08);
      display: flex; flex-direction: column;
    }
    .sidebar-header { 
      padding: 0 20px 24px; 
      border-bottom: 1px solid rgba(59,130,246,0.18); 
      margin-bottom: 20px; 
    }
    .sidebar-header h2 { 
      font-size: 18px; 
      font-weight: 600; 
      white-space: nowrap; 
    }
    .sidebar-nav { 
      list-style: none; 
    }
    .sidebar-nav li { 
      margin: 0; 
    }
    .nav-center { display: flex; flex-direction: column; gap: 6px; margin: 10px 0; align-items: center; justify-content: center; }
    .sidebar-nav a, .nav-center a { 
      display: block; 
      padding: 12px 20px; 
      color: #1e3a8a; 
      text-decoration: none; 
      transition: all 0.3s ease; 
      border-left: 3px solid transparent;
      border-radius: 10px;
      margin: 0 10px 6px;
      font-weight: 600;
      width: 100%; max-width: 220px; text-align: center;
    }
    .logout-btn { margin-top: auto; display: block; width: 100%; max-width: 220px; text-align: center; padding: 10px 12px; border-radius: 8px; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 6px 14px rgba(220,38,38,0.18); text-decoration: none; }
    .logout-btn:hover { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }
    .sidebar-nav a:hover { 
      background: #dbeafe;
      color: #1e3a8a; 
      border-left-color: #3b82f6; 
    }
    .sidebar-nav a.active { 
      background: linear-gradient(135deg, #60a5fa, #3b82f6); 
      color: #fff; 
      border-left-color: #facc15;
      box-shadow: 0 8px 18px rgba(59, 130, 246, 0.25);
    }
    .sidebar-nav-label {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      padding: 16px 20px 8px;
      letter-spacing: 0.5px;
    }
    .user-name {
      font-size: 14px;
      font-weight: 600;
      color: #1e3a8a;
      padding: 0 20px;
      margin-bottom: 8px;
      word-break: break-word;
    }
    .sidebar-nav .nav-logout {
      margin-top: 24px;
      border-top: 1px solid rgba(59,130,246,0.18);
      padding-top: 16px;
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
    }
    
    .page-wrap { max-width: 100%; }
    .card-shell { background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); border: 1px solid #dbe5f3; border-radius: 12px; box-shadow: 0 12px 26px rgba(37, 99, 235, 0.12); padding: 20px; }
    .table thead th { border-top: 0; background: linear-gradient(135deg, #eff6ff, #fef9c3); color: #1e3a8a; }
    #user-table thead th,
    #user-table tbody td { text-align: center; vertical-align: middle; }
    #user-table tbody td:last-child { white-space: nowrap; }
    .card-shell h3 { text-align: center; width: 100%; }
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.45); }
    .modal-content { background: #fff; margin: 6% auto; padding: 20px; border-radius: 12px; width: 94%; max-width: 620px; border: 1px solid #dbe5f3; box-shadow: 0 12px 24px rgba(37,99,235,0.14); }
    .close-btn { float: right; cursor: pointer; font-size: 22px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px; }
    #paginationControls { display: flex; gap: 6px; align-items: center; }
    #paginationControls .btn-outline-secondary {
      padding: 8px 14px;
      border: 1px solid #3b82f6;
      background: #3b82f6;
      color: #fff;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      line-height: 1.1;
      min-width: 34px;
    }
    #paginationControls .btn-outline-secondary:hover {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
    }
    #paginationControls .btn-outline-secondary.active {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.18);
    }
    #paginationControls .btn-outline-secondary:disabled {
      opacity: .55;
      cursor: not-allowed;
      background: #93c5fd;
      border-color: #93c5fd;
      color: #eff6ff;
    }
    /* Add User button styling: square and colored */
    .btn-add-user {
      border-radius: 0 !important;
      background: linear-gradient(90deg, #06b6d4 0%, #0ea5e9 100%) !important;
      border-color: #06b6d4 !important;
      color: #fff !important;
      padding: 6px 12px !important;
      box-shadow: 0 8px 18px rgba(14,165,233,0.16) !important;
      font-weight: 700 !important;
    }
    .btn-add-user:hover {
      background: linear-gradient(90deg, #0b94a6 0%, #0b93d1 100%) !important;
      border-color: #0b94a6 !important;
      color: #fff !important;
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="user-name"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="sidebar-nav-label">Navigation</div>
    </div>
    <div class="nav-center">
      <a href="/index-superadmin" class="<?= strpos($_SERVER['REQUEST_URI'], 'index-superadmin') !== false ? 'active' : '' ?>">Dashboard</a>
      <a href="/superadmin-users" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-users') !== false ? 'active' : '' ?>">User Management</a>
      <a href="/superadmin-logs" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-logs') !== false ? 'active' : '' ?>">System Logs</a>
    </div>
    <a class="logout-btn" href="/logout">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
  <div class="page-wrap">
    <div class="card-shell">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">User Management</h3>
        <div class="btn-group" role="group" aria-label="Page actions">
          <a href="/register" class="btn btn-sm btn-add-user" target="_blank">Add User</a>
        </div>
      </div>

      <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" href="#" data-status="Active">Active</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-status="Inactive">Inactive</a></li>
      </ul>

      <div class="row mt-3 mb-2">
        <div class="col-md-5 mb-2 mb-md-0">
          <input type="text" id="userSearchInput" class="form-control form-control-sm" placeholder="Search name, email, role, or status...">
        </div>
        <div class="col-md-3 mb-2 mb-md-0">
          <select id="roleFilter" class="form-control form-control-sm">
            <option value="">All Roles</option>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Super Admin">Super Admin</option>
            <option value="Barangay">Barangay</option>
          </select>
        </div>
        <div class="col-md-2">
          <select id="pageSize" class="form-control form-control-sm" disabled>
            <option value="10" selected>10 / page</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-sm" id="user-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="user-list">
            <?php if (!empty($users) && is_array($users)): ?>
              <?php foreach ($users as $user): ?>
                <?php $userId = (int) ($user['id'] ?? 0); ?>
                <tr class="user-row status-<?= htmlspecialchars((string) ($user['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                  <td><?= htmlspecialchars((string) ($user['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string) ($user['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string) ($user['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td>
                    <button type="button" class="btn btn-link btn-sm p-0 open-edit"
                      data-id="<?= $userId ?>"
                      data-name="<?= htmlspecialchars((string) ($user['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                      data-email="<?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                      data-role="<?= htmlspecialchars((string) ($user['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                      data-barangay-id="<?= htmlspecialchars((string) ($user['barangay_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                      data-status="<?= htmlspecialchars((string) ($user['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">Edit</button>

                    <form action="/edit-user" method="POST" style="display:inline-block; margin-left:8px;" class="status-form">
                      <input type="hidden" name="id" value="<?= $userId ?>" required>
                      <?php $isInactive = (($user['status'] ?? '') === 'Inactive'); ?>
                      <button type="button" class="btn btn-link btn-sm p-0 status-btn <?= $isInactive ? '' : 'text-danger' ?>" data-action="<?= $isInactive ? 'activate' : 'deactivate' ?>">
                        <?= $isInactive ? 'Activate' : 'Deactivate' ?>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr id="no-users-row"><td colspan="5">No users found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-3" id="paginationContainer">
        <small id="paginationInfo">Showing 0-0 of 0 users</small>
        <div id="paginationControls" class="btn-group btn-group-sm" role="group" aria-label="User pagination"></div>
      </div>
    </div>
  </div>

  <div id="edit-modal" class="modal">
    <div class="modal-content">
      <span class="close-btn" id="edit-close-btn">&times;</span>
      <h4>Edit User</h4>
      <form id="edit-user-form" method="POST" action="/update-user">
        <input type="hidden" id="edit-user-id" name="id">
        <div class="form-group">
          <label for="edit-username">Name</label>
          <input type="text" id="edit-username" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit-email">Email</label>
          <input type="email" id="edit-email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit-password">New Password</label>
          <input type="password" id="edit-password" name="password" class="form-control" minlength="6" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
          <label for="edit-confirm">Confirm Password</label>
          <input type="password" id="edit-confirm" name="confirm_password" class="form-control" minlength="6" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
          <label for="edit-role">Role</label>
          <select id="edit-role" name="role" class="form-control" required>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Super Admin">Super Admin</option>
            <option value="Barangay">Barangay</option>
          </select>
        </div>
        <div class="form-group" id="edit-barangay-row" style="display:none;">
          <label for="edit-barangay-id">Barangay</label>
          <select id="edit-barangay-id" name="barangay_id" class="form-control">
            <option value="">Select barangay</option>
            <?php if (!empty($barangayList) && is_array($barangayList)): ?>
              <?php foreach ($barangayList as $barangay): ?>
                <option value="<?= (int) ($barangay['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($barangay['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-light" id="edit-cancel-btn">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (function () {
      const rows = Array.from(document.querySelectorAll('.user-row'));
      const searchInput = document.getElementById('userSearchInput');
      const roleFilter = document.getElementById('roleFilter');
      const pageSizeSelect = document.getElementById('pageSize');
      const paginationInfo = document.getElementById('paginationInfo');
      const paginationControls = document.getElementById('paginationControls');
      const tabs = Array.from(document.querySelectorAll('#userTabs .nav-link'));
      let activeStatus = 'Active';
      let currentPage = 1;

      function getFilteredRows() {
        const search = (searchInput.value || '').trim().toLowerCase();
        const role = roleFilter.value;

        return rows.filter((row) => {
          const status = row.children[3].textContent.trim();
          if (status !== activeStatus) return false;
          const roleText = row.children[2].textContent.trim();
          if (role && roleText !== role) return false;
          if (!search) return true;
          return row.textContent.toLowerCase().includes(search);
        });
      }

      function renderPagination(totalPages) {
        paginationControls.innerHTML = '';
        if (totalPages <= 1) return;
        const maxVisiblePages = 10;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = startPage + maxVisiblePages - 1;
        if (endPage > totalPages) {
          endPage = totalPages;
          startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn btn-outline-secondary';
        prevBtn.textContent = 'Previous';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', function () {
          if (currentPage <= 1) return;
          currentPage -= 1;
          applyTable();
        });
        paginationControls.appendChild(prevBtn);

        for (let i = startPage; i <= endPage; i++) {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'btn btn-outline-secondary' + (i === currentPage ? ' active' : '');
          btn.textContent = String(i);
          btn.addEventListener('click', function () {
            currentPage = i;
            applyTable();
          });
          paginationControls.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn btn-outline-secondary';
        nextBtn.textContent = 'Next';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', function () {
          if (currentPage >= totalPages) return;
          currentPage += 1;
          applyTable();
        });
        paginationControls.appendChild(nextBtn);
      }

      function applyTable() {
        rows.forEach((row) => { row.style.display = 'none'; });
        const filtered = getFilteredRows();
        const pageSize = 10;
        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        if (currentPage > totalPages) currentPage = 1;
        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);

        filtered.slice(start, end).forEach((row) => { row.style.display = ''; });
        paginationInfo.textContent = total === 0
          ? 'Showing 0-0 of 0 users'
          : ('Showing ' + (start + 1) + '-' + end + ' of ' + total + ' users');
        renderPagination(totalPages);
      }

      tabs.forEach((tab) => {
        tab.addEventListener('click', function (e) {
          e.preventDefault();
          tabs.forEach((x) => x.classList.remove('active'));
          tab.classList.add('active');
          activeStatus = tab.getAttribute('data-status') || 'Active';
          currentPage = 1;
          applyTable();
        });
      });

      searchInput.addEventListener('input', function () { currentPage = 1; applyTable(); });
      roleFilter.addEventListener('change', function () { currentPage = 1; applyTable(); });
      pageSizeSelect.addEventListener('change', function () { currentPage = 1; applyTable(); });

      const modal = document.getElementById('edit-modal');
      const closeBtn = document.getElementById('edit-close-btn');
      const cancelBtn = document.getElementById('edit-cancel-btn');
      const editRole = document.getElementById('edit-role');
      const editBarangayRow = document.getElementById('edit-barangay-row');

      function syncBarangayField() {
        editBarangayRow.style.display = editRole.value === 'Barangay' ? 'block' : 'none';
      }

      document.querySelectorAll('.open-edit').forEach((btn) => {
        btn.addEventListener('click', function () {
          document.getElementById('edit-user-id').value = btn.getAttribute('data-id') || '';
          document.getElementById('edit-username').value = btn.getAttribute('data-name') || '';
          document.getElementById('edit-email').value = btn.getAttribute('data-email') || '';
          editRole.value = btn.getAttribute('data-role') || 'Staff';
          document.getElementById('edit-barangay-id').value = btn.getAttribute('data-barangay-id') || '';
          syncBarangayField();
          modal.style.display = 'block';
        });
      });

      closeBtn.addEventListener('click', function () { modal.style.display = 'none'; });
      cancelBtn.addEventListener('click', function () { modal.style.display = 'none'; });
      window.addEventListener('click', function (event) { if (event.target === modal) modal.style.display = 'none'; });
      editRole.addEventListener('change', syncBarangayField);

      document.querySelectorAll('.status-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
          const form = btn.closest('form');
          const action = btn.getAttribute('data-action') || 'deactivate';
          const title = action === 'deactivate' ? 'Confirm Deactivation' : 'Confirm Activation';
          const text = action === 'deactivate'
            ? 'Make sure you want to deactivate this account.'
            : 'Make sure you want to activate this account.';
          Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes', cancelButtonText: 'Cancel' })
            .then((result) => { if (result.isConfirmed) form.submit(); });
        });
      });

      applyTable();
    })();
  </script>
  <script>
    (function () {
      const navLinks = document.querySelectorAll('.sidebar-nav a, .nav-center a');
      if (!navLinks || navLinks.length === 0) return;

      function normalizePath(p) {
        try { return new URL(p, window.location.origin).pathname.replace(/\/$/, ''); } catch (e) { return String(p || '').replace(/\/$/, ''); }
      }

      function setActiveByLocation() {
        const current = window.location.pathname.replace(/\/$/, '');
        navLinks.forEach(a => {
          const href = a.getAttribute('href') || '';
          const hrefPath = normalizePath(href);
          if (hrefPath !== '' && hrefPath !== '/' && current.includes(hrefPath)) {
            a.classList.add('active');
          } else if (hrefPath === current) {
            a.classList.add('active');
          } else {
            a.classList.remove('active');
          }
        });
      }

      navLinks.forEach(a => {
        a.addEventListener('click', function () {
          navLinks.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
        });
      });

      setActiveByLocation();
    })();
  </script>
  </div>
  </div>
</body>
</html>
