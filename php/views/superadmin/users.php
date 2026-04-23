<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title><?= htmlspecialchars((string)($title ?? 'Superadmin Users'), ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="/files/assets/css/user.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: #f4f7fb; display: flex; }
    
    /* Sidebar Styles */
    .sidebar { 
      width: 260px; 
      background: #0f766e; 
      color: #fff; 
      min-height: 100vh; 
      padding: 20px 0; 
      position: fixed; 
      left: 0; 
      top: 0; 
      overflow-y: auto; 
    }
    .sidebar-header { 
      padding: 0 20px 24px; 
      border-bottom: 1px solid rgba(255,255,255,0.1); 
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
    .sidebar-nav a { 
      display: block; 
      padding: 12px 20px; 
      color: rgba(255,255,255,0.8); 
      text-decoration: none; 
      transition: all 0.3s ease; 
      border-left: 3px solid transparent; 
    }
    .sidebar-nav a:hover { 
      background: rgba(255,255,255,0.1); 
      color: #fff; 
      border-left-color: #fff; 
    }
    .sidebar-nav a.active { 
      background: #0f766e; 
      color: #fff; 
      border-left-color: #fbbf24; 
    }
    .sidebar-nav-label {
      font-size: 12px;
      font-weight: 600;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      padding: 16px 20px 8px;
      letter-spacing: 0.5px;
    }
    .user-name {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      padding: 0 20px;
      margin-bottom: 8px;
      word-break: break-word;
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
    }
    
    .page-wrap { max-width: 100%; }
    .card-shell { background: #fff; border-radius: 12px; box-shadow: 0 10px 24px rgba(0,0,0,.08); padding: 20px; }
    .table thead th { border-top: 0; background: #f8fafc; }
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.45); }
    .modal-content { background: #fff; margin: 6% auto; padding: 20px; border-radius: 12px; width: 94%; max-width: 620px; }
    .close-btn { float: right; cursor: pointer; font-size: 22px; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px; }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="user-name"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="sidebar-nav-label">Navigation</div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="/index-superadmin" class="<?= strpos($_SERVER['REQUEST_URI'], 'index-superadmin') !== false ? 'active' : '' ?>">Dashboard</a></li>
      <li><a href="/superadmin-users" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-users') !== false ? 'active' : '' ?>">User Management</a></li>
      <li><a href="/superadmin-logs" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-logs') !== false ? 'active' : '' ?>">System Logs</a></li>
      <li style="margin-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px;"><a href="/logout">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
  <div class="page-wrap">
    <div class="card-shell">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">User Management</h3>
        <div>
          <a href="/index-superadmin" class="btn btn-sm btn-outline-secondary">Dashboard</a>
          <a href="/register" class="btn btn-sm btn-primary" target="_blank">Add User</a>
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
          <select id="pageSize" class="form-control form-control-sm">
            <option value="5">5 / page</option>
            <option value="10" selected>10 / page</option>
            <option value="20">20 / page</option>
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
        for (let i = 1; i <= totalPages; i++) {
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
      }

      function applyTable() {
        rows.forEach((row) => { row.style.display = 'none'; });
        const filtered = getFilteredRows();
        const pageSize = parseInt(pageSizeSelect.value, 10) || 10;
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
  </div>
  </div>
</body>
</html>
