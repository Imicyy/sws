<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Barangay — Person With Disability List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; max-height: 100vh; overflow-y: auto; position: sticky; top: 0; height: 100vh; align-self: start; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th, td { padding: 8px; border-bottom: 1px solid #e8edf3; text-align: left; }
    .actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #d1d5db; background: none; cursor: pointer; }
    .btn-view { color: #2563eb; }
    .btn-edit { color: #4f46e5; }
    .btn-archive { color: #dc2626; }
    .btn-sm:hover { background: #f3f4f6; }
    .btn-print-app { background: #0f766e; color: #fff; border-color: #0f766e; }
    .btn-print-app:hover { background: #0d5f59; color: #fff; }
    .status-badge { display: inline-block; padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-archived { background: #e5e7eb; color: #374151; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 2000; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.show { display: flex; }
    .modal-card { width: min(1100px, 100%); background: #fff; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,.25); overflow: hidden; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-bottom: 1px solid #e5e7eb; }
    .modal-body { padding: 12px; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 8px; padding: 12px 14px; border-top: 1px solid #e5e7eb; }
    .modal-actions button { border: 1px solid #d1d5db; background: #fff; border-radius: 8px; padding: 8px 12px; font-size: 12px; font-weight: 700; cursor: pointer; }
    .modal-actions .btn-print-app { background: #0f766e; border-color: #0f766e; color: #fff; }
    .modal-close { border: none; background: transparent; font-size: 22px; line-height: 1; cursor: pointer; }
    .edit-frame-body { padding: 0; height: min(78vh, 760px); }
    .edit-frame-body iframe { width: 100%; height: 100%; border: none; }
    .toolbar-search { width: 100%; max-width: 320px; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px; }
    .birthday-modal-table { width: 100%; border-collapse: collapse; }
    .birthday-modal-table th, .birthday-modal-table td { border: 1px solid #e5e7eb; padding: 8px; font-size: 12px; }
    .birthday-modal-table th { background: #f8fafc; }
    .modal-actions .primary { background: #2563eb; border-color: #2563eb; color: #fff; }
    .muted { color: #6b7280; font-size: 12px; }
    @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } .sidebar { position: static; height: auto; max-height: none; } }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="/barangay">Person With Disability Analytics</a>
      <a class="nav-link" href="/barangay-senior-dashboard">Senior Citizen Analytics</a>
      <a class="nav-link active" href="/barangay-pwd">Person With Disability List</a>
      <a class="nav-link" href="/barangay-senior">Senior Citizens List</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <h2 style="margin:0;">PWD — <?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
          <div style="display:flex;align-items:center;gap:8px;">
            <button type="button" class="btn btn-sm" id="birthdaysBtn" style="border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;background:#fff;font-weight:700;">
              🎂 Birthdays <span id="birthdaysBadge" style="display:none;margin-left:6px;background:#0f766e;color:#fff;border-radius:999px;padding:2px 8px;font-size:12px;">0</span>
            </button>
            <a href="/add_pwd" class="btn btn-info" style="background:#0f766e;border-color:#0f766e;color:#fff;">ADD PWD</a>
          </div>
        </div>

        <div style="margin-top:16px; background:#fff; padding:14px; border-radius:8px; border:1px solid #eef2f6; display:flex; gap:12px; align-items:center;">
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Barangay:</label>
            <input type="text" value="<?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?>" disabled style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb; background:#f8fafc;">
          </div>
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Purok:</label>
            <select id="purokFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Select Purok</option>
              <?php foreach (($puroks ?? []) as $pr): ?>
                <option value="<?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div style="width:160px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Status:</label>
            <select id="statusFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Active Only</option>
              <option value="Active">Active</option>
              <option value="Archived">Archived</option>
              <option value="all">All</option>
            </select>
          </div>
          <div style="width:220px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Search:</label>
            <input id="searchInput" type="text" placeholder="Search..." style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
          </div>
        </div>

        <div style="margin-top:16px; overflow:auto">
          <table id="pwdTable" class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th style="width:40px;"><input id="selectAll" type="checkbox"></th>
                <th>FULL NAME</th>
                <th>AGE</th>
                <th>BARANGAY</th>
                <th>PUROK</th>
                <th>CIVIL STATUS</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($pwds ?? []) as $p): ?>
                <?php $full = trim(((string)($p['last_name'] ?? '')) . ' ' . ((string)($p['first_name'] ?? ''))); ?>
                <?php
                  $status = (string)($p['status'] ?? 'Active');
                  $statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
                ?>
                <tr data-purok="<?= htmlspecialchars((string)($p['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" data-pwd='<?= htmlspecialchars(json_encode($p, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                  <td><input class="rowCheckbox" type="checkbox" value="<?= (int)($p['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars(strtoupper($full), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($p['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($p['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($p['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($p['civil_status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="actions">
                      <button type="button" class="btn-sm btn-view view-btn">View</button>
                      <button type="button" class="btn-sm btn-edit edit-btn">Edit</button>
                      <button type="button" class="btn-sm btn-archive archive-btn" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"><?= $status === 'Archived' ? 'Unarchive' : 'Archive' ?></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
  <div id="viewModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">View PWD Record</h5>
        <button type="button" class="modal-close" data-close="viewModal">&times;</button>
      </div>
      <div class="modal-body edit-frame-body">
        <iframe id="viewPwdFrame" title="View PWD Form" loading="lazy" src="about:blank"></iframe>
      </div>
      <div class="modal-actions">
        <button type="button" id="printApplicationBtn" class="btn-print-app">Print Application</button>
        <button type="button" data-close="viewModal">Close</button>
      </div>
    </div>
  </div>
  <div id="editModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">Edit PWD Record</h5>
        <button type="button" class="modal-close" data-close="editModal">&times;</button>
      </div>
      <div class="modal-body edit-frame-body">
        <iframe id="editPwdFrame" title="Edit PWD Form" loading="lazy" src="about:blank"></iframe>
      </div>
      <div class="modal-actions">
        <button type="button" data-close="editModal">Close</button>
      </div>
    </div>
  </div>
  <div id="birthdaysModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card" style="width:min(1120px,100%);">
      <div class="modal-header">
        <h5 style="margin:0;">PWD Birthdays</h5>
        <button type="button" class="modal-close" data-close="birthdaysModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:10px;">
          <input id="bdaySearch" class="toolbar-search" placeholder="Search name/barangay/purok...">
          <select id="bdayRange" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="today">Today's birthdays</option>
            <option value="month" selected>This month's birthdays</option>
          </select>
          <input id="bdayPurok" placeholder="Purok (optional)" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;min-width:180px;">
        </div>
        <div style="overflow:auto;max-height:420px;border:1px solid #e5e7eb;border-radius:8px;">
          <table class="birthday-modal-table">
            <thead>
              <tr>
                <th style="width:34px;"><input type="checkbox" id="birthdaysSelectAll"></th>
                <th>Full Name</th>
                <th>Birth Date</th>
                <th>Barangay</th>
                <th>Purok</th>
              </tr>
            </thead>
            <tbody id="birthdaysTableBody"><tr><td colspan="5" class="text-center muted">Loading...</td></tr></tbody>
          </table>
        </div>
        <small id="birthdaysCount" class="muted" style="display:block;margin-top:8px;">0 results</small>
      </div>
      <div class="modal-actions">
        <button type="button" id="birthdaysSendSmsBtn" class="primary" disabled>Send SMS</button>
        <button type="button" id="birthdaysViewHistoryBtn">View SMS History</button>
        <button type="button" data-close="birthdaysModal">Close</button>
      </div>
    </div>
  </div>
  <div id="smsModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">Send SMS Assistance</h5>
        <button type="button" class="modal-close" data-close="smsModal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="recipientsList" style="max-height:160px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px; padding:8px; margin-bottom:10px;"></div>
        <label for="smsMessage" class="muted" style="font-weight:700;">Message</label>
        <textarea id="smsMessage" rows="4" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px;"></textarea>
        <div class="muted" style="margin-top:6px;">Characters: <span id="charCount">0</span></div>
      </div>
      <div class="modal-actions">
        <button type="button" id="sendSmsModalBtn" class="primary">Send SMS</button>
        <button type="button" data-close="smsModal">Close</button>
      </div>
    </div>
  </div>
  <div id="smsHistoryModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card" style="width:min(1100px,100%);">
      <div class="modal-header">
        <h5 style="margin:0;">SMS History</h5>
        <button type="button" class="modal-close" data-close="smsHistoryModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="overflow:auto;">
          <table class="birthday-modal-table">
            <thead><tr><th>Sent At</th><th>Phone</th><th>Name</th><th>Barangay</th><th>Purok</th><th>Message</th><th>Status</th></tr></thead>
            <tbody id="smsHistoryTableBody"><tr><td colspan="7" class="text-center muted">No SMS history yet.</td></tr></tbody>
          </table>
        </div>
      </div>
      <div class="modal-actions"><button type="button" data-close="smsHistoryModal">Close</button></div>
    </div>
  </div>
</body>
</html>
<script>
  (function(){
    const purokFilter = document.getElementById('purokFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const selectAll = document.getElementById('selectAll');
    const viewPwdFrame = document.getElementById('viewPwdFrame');
    const editPwdFrame = document.getElementById('editPwdFrame');
    const assignedBarangay = <?= json_encode((string)($assignedBarangayName ?? ''), JSON_UNESCAPED_UNICODE) ?>;
    const birthdayGreetingMessage = 'Happy Birthday! Greetings from Mayor Matthew Louis P. Malacon and Vice Mayor Marvin M. Malacon.';
    let currentViewPwdId = null;
    let currentViewPwdData = null;
    let birthdaysCache = [];

    function openModal(id) {
      const modal = document.getElementById(id);
      if (modal) modal.classList.add('show');
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.remove('show');
      if (id === 'viewModal' && viewPwdFrame) viewPwdFrame.src = 'about:blank';
      if (id === 'editModal' && editPwdFrame) editPwdFrame.src = 'about:blank';
    }

    function getPwdFromRow(row) {
      if (!row) return {};
      const raw = row.getAttribute('data-pwd');
      if (!raw) return {};
      try { return JSON.parse(raw) || {}; } catch (_) { return {}; }
    }

    function formatBirthDate(value) {
      const d = new Date(value);
      if (Number.isNaN(d.getTime())) return value || 'N/A';
      return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function getBirthdayRowById(id) {
      return birthdaysCache.find(function (row) { return String(row.id || '') === String(id || ''); }) || null;
    }

    function updateRowStatus(row, status) {
      const badge = row.querySelector('.status-badge');
      const archiveBtn = row.querySelector('.archive-btn');
      if (badge) {
        badge.textContent = status;
        badge.classList.remove('status-active', 'status-archived');
        badge.classList.add(status === 'Archived' ? 'status-archived' : 'status-active');
      }
      row.dataset.status = status;
      if (archiveBtn) {
        archiveBtn.dataset.status = status;
        archiveBtn.textContent = status === 'Archived' ? 'Unarchive' : 'Archive';
      }
      const pwd = getPwdFromRow(row);
      pwd.status = status;
      row.setAttribute('data-pwd', JSON.stringify(pwd));
    }

    function applyFilters(){
      const purok = purokFilter ? purokFilter.value.trim().toLowerCase() : '';
      const status = statusFilter ? statusFilter.value : '';
      const search = searchInput ? searchInput.value.trim().toLowerCase() : '';
      document.querySelectorAll('#pwdTable tbody tr').forEach(function(row){
        const rowPurok = (row.dataset.purok || '').toLowerCase();
        const cols = row.querySelectorAll('td');
        const name = (cols[1] ? cols[1].textContent : '').toLowerCase();
        const rowStatus = (cols[6] ? cols[6].textContent : '').trim();

        let show = true;
        if (purok && rowPurok !== purok) show = false;
        if (status && status !== 'all' && rowStatus !== status) show = false;
        if (search && name.indexOf(search) === -1 && rowPurok.indexOf(search) === -1) show = false;

        row.style.display = show ? '' : 'none';
      });
    }

    if (purokFilter) purokFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (searchInput) searchInput.addEventListener('input', applyFilters);
    document.querySelectorAll('[data-close]').forEach(function (button) {
      button.addEventListener('click', function () {
        closeModal(button.dataset.close);
      });
    });
    document.querySelectorAll('.modal-overlay').forEach(function (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) closeModal(modal.id);
      });
    });
    if (selectAll) selectAll.addEventListener('change', function(){
      const checked = !!selectAll.checked;
      document.querySelectorAll('.rowCheckbox').forEach(function(cb){ cb.checked = checked; });
    });

    const birthdaysBtn = document.getElementById('birthdaysBtn');
    const birthdaysBadge = document.getElementById('birthdaysBadge');
    const birthdaysBody = document.getElementById('birthdaysTableBody');
    const birthdaysCount = document.getElementById('birthdaysCount');
    const bdaySearch = document.getElementById('bdaySearch');
    const bdayRange = document.getElementById('bdayRange');
    const bdayPurok = document.getElementById('bdayPurok');
    const birthdaysSelectAll = document.getElementById('birthdaysSelectAll');
    const birthdaysSendSmsBtn = document.getElementById('birthdaysSendSmsBtn');
    const birthdaysViewHistoryBtn = document.getElementById('birthdaysViewHistoryBtn');

    function renderBirthdaysTable() {
      if (!birthdaysBody) return;
      const q = (bdaySearch && bdaySearch.value ? bdaySearch.value : '').trim().toLowerCase();
      const purokQ = (bdayPurok && bdayPurok.value ? bdayPurok.value : '').trim().toLowerCase();
      const filtered = birthdaysCache.filter(function (row) {
        const fullName = String(row.full_name || '').toLowerCase();
        const barangay = String(row.barangay || '').toLowerCase();
        const purok = String(row.purok || '').toLowerCase();
        if (assignedBarangay && barangay !== String(assignedBarangay).toLowerCase()) return false;
        if (purokQ && purok.indexOf(purokQ) === -1) return false;
        if (q && fullName.indexOf(q) === -1 && barangay.indexOf(q) === -1 && purok.indexOf(q) === -1) return false;
        return true;
      });

      if (!filtered.length) {
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center muted">No birthdays found.</td></tr>';
      } else {
        birthdaysBody.innerHTML = filtered.map(function (row) {
          return '<tr>'
            + '<td><input type="checkbox" class="birthday-row-checkbox" value="' + String(row.id || '') + '"></td>'
            + '<td>' + String(row.full_name || 'Unnamed') + '</td>'
            + '<td>' + formatBirthDate(row.birth_date || row.birthday || '') + '</td>'
            + '<td>' + String(row.barangay || 'N/A') + '</td>'
            + '<td>' + String(row.purok || 'N/A') + '</td>'
            + '</tr>';
        }).join('');
      }

      birthdaysCount.textContent = filtered.length + ' results';
      birthdaysSendSmsBtn.disabled = true;
      birthdaysSelectAll.checked = false;
      birthdaysBody.querySelectorAll('.birthday-row-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
          const all = birthdaysBody.querySelectorAll('.birthday-row-checkbox');
          const checked = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked');
          birthdaysSelectAll.checked = all.length > 0 && all.length === checked.length;
          birthdaysSendSmsBtn.disabled = checked.length === 0;
        });
      });
    }

    async function loadBirthdays(range) {
      birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center muted">Loading...</td></tr>';
      try {
        const res = await fetch('/api/birthdays?type=pwd&range=' + encodeURIComponent(range || 'month'), { credentials: 'same-origin' });
        const json = await res.json();
        birthdaysCache = (json && json.success && Array.isArray(json.data)) ? json.data : [];
        renderBirthdaysTable();
      } catch (_) {
        birthdaysCache = [];
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load birthdays.</td></tr>';
        birthdaysCount.textContent = '0 results';
      }
    }

    async function updateBirthdaysBadge() {
      try {
        const res = await fetch('/api/birthdays?type=pwd&range=today', { credentials: 'same-origin' });
        const json = await res.json();
        const data = (json && json.success && Array.isArray(json.data)) ? json.data : [];
        const todayCount = data.filter(function (row) {
          return !assignedBarangay || String(row.barangay || '').toLowerCase() === String(assignedBarangay).toLowerCase();
        }).length;
        birthdaysBadge.style.display = todayCount > 0 ? '' : 'none';
        birthdaysBadge.textContent = String(todayCount);
      } catch (_) {
        birthdaysBadge.style.display = 'none';
      }
    }

    document.querySelectorAll('.view-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const id = Number(pwd.id || 0);
        if (!id) return;
        if (viewPwdFrame) viewPwdFrame.src = '/add_pwd?edit=' + encodeURIComponent(String(id)) + '&modal=1&view=1';
        currentViewPwdId = String(id);
        currentViewPwdData = pwd;
        openModal('viewModal');
      });
    });

    const printApplicationBtn = document.getElementById('printApplicationBtn');
    if (printApplicationBtn) {
      printApplicationBtn.addEventListener('click', function () {
        if (!currentViewPwdId) return;
        window.open('/pwd/' + encodeURIComponent(String(currentViewPwdId)) + '/application-pdf', '_blank');
      });
    }

    document.querySelectorAll('.edit-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const id = Number(pwd.id || 0);
        if (!id) return;
        if (editPwdFrame) editPwdFrame.src = '/add_pwd?edit=' + encodeURIComponent(String(id)) + '&modal=1';
        openModal('editModal');
      });
    });

    window.addEventListener('message', function (event) {
      if (!event || event.origin !== window.location.origin || !event.data) return;
      if (event.data.type === 'pwd-edit-saved') window.location.reload();
      if (event.data.type === 'pwd-edit-cancel') closeModal('editModal');
      if (event.data.type === 'pwd-view-close') closeModal('viewModal');
    });

    if (birthdaysBtn) {
      birthdaysBtn.addEventListener('click', async function () {
        openModal('birthdaysModal');
        await loadBirthdays(bdayRange ? bdayRange.value : 'month');
      });
    }
    if (bdayRange) bdayRange.addEventListener('change', function () { loadBirthdays(bdayRange.value); });
    if (bdaySearch) bdaySearch.addEventListener('input', renderBirthdaysTable);
    if (bdayPurok) bdayPurok.addEventListener('input', renderBirthdaysTable);
    if (birthdaysSelectAll) {
      birthdaysSelectAll.addEventListener('change', function () {
        birthdaysBody.querySelectorAll('.birthday-row-checkbox').forEach(function (cb) { cb.checked = birthdaysSelectAll.checked; });
        birthdaysSendSmsBtn.disabled = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked').length === 0;
      });
    }
    if (birthdaysSendSmsBtn) {
      birthdaysSendSmsBtn.addEventListener('click', function () {
        const selected = Array.from(birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked'));
        const recipientsList = document.getElementById('recipientsList');
        recipientsList.innerHTML = '';
        selected.forEach(function (checkbox) {
          const row = getBirthdayRowById(checkbox.value);
          const phone = String((row && row.mobile_number) || '').trim();
          if (!phone) return;
          const div = document.createElement('div');
          div.textContent = (row.full_name || 'Unnamed') + ' — ' + phone;
          div.dataset.phone = phone;
          div.dataset.firstName = row.first_name || '';
          div.dataset.middleName = row.middle_name || '';
          div.dataset.lastName = row.last_name || '';
          div.dataset.barangay = row.barangay || '';
          div.dataset.purok = row.purok || '';
          div.dataset.recordId = String(row.id || '');
          recipientsList.appendChild(div);
        });
        if (!recipientsList.children.length) {
          alert('No selected birthday records have a mobile number.');
          return;
        }
        const smsMessageEl = document.getElementById('smsMessage');
        const charCountEl = document.getElementById('charCount');
        smsMessageEl.value = birthdayGreetingMessage;
        charCountEl.textContent = String(birthdayGreetingMessage.length);
        closeModal('birthdaysModal');
        openModal('smsModal');
      });
    }
    if (birthdaysViewHistoryBtn) {
      birthdaysViewHistoryBtn.addEventListener('click', function () {
        closeModal('birthdaysModal');
        loadSmsHistory();
        openModal('smsHistoryModal');
      });
    }

    const smsMessage = document.getElementById('smsMessage');
    if (smsMessage) smsMessage.addEventListener('input', function () { document.getElementById('charCount').textContent = String(smsMessage.value.length); });
    const sendSmsModalBtn = document.getElementById('sendSmsModalBtn');
    if (sendSmsModalBtn) {
      sendSmsModalBtn.addEventListener('click', async function () {
        const message = (document.getElementById('smsMessage').value || '').trim();
        const recipients = Array.from(document.getElementById('recipientsList').children).map(function (div) {
          return {
            phone: div.dataset.phone || '',
            first_name: div.dataset.firstName || '',
            middle_name: div.dataset.middleName || '',
            last_name: div.dataset.lastName || '',
            barangay: div.dataset.barangay || '',
            purok: div.dataset.purok || '',
            record_id: div.dataset.recordId || ''
          };
        }).filter(function (r) { return r.phone; });
        if (!message || !recipients.length) return;
        sendSmsModalBtn.disabled = true;
        const oldText = sendSmsModalBtn.textContent;
        sendSmsModalBtn.textContent = 'Sending...';
        try {
          const response = await fetch('/send-sms', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ message: message, recipients: recipients, recipient_type: 'PWD' })
          });
          const payload = await response.json();
          if (!response.ok || !payload || payload.success === false) throw new Error((payload && payload.message) ? payload.message : 'SMS send failed.');
          alert(payload.message || 'SMS sent successfully.');
          closeModal('smsModal');
        } catch (error) {
          alert(error.message || 'Failed to send SMS.');
        } finally {
          sendSmsModalBtn.disabled = false;
          sendSmsModalBtn.textContent = oldText;
        }
      });
    }

    async function loadSmsHistory() {
      const tableBody = document.getElementById('smsHistoryTableBody');
      tableBody.innerHTML = '<tr><td colspan="7" class="text-center muted">Loading...</td></tr>';
      try {
        const response = await fetch('/sms-history?recipient_type=PWD&limit=200', { credentials: 'same-origin' });
        const payload = await response.json();
        const rows = (payload && payload.success && Array.isArray(payload.data)) ? payload.data : [];
        if (!rows.length) {
          tableBody.innerHTML = '<tr><td colspan="7" class="text-center muted">No SMS history yet.</td></tr>';
          return;
        }
        tableBody.innerHTML = rows.map(function (row) {
          return '<tr>'
            + '<td>' + String(row.created_at || row.sent_at || '') + '</td>'
            + '<td>' + String(row.phone || '') + '</td>'
            + '<td>' + String(row.name || [row.first_name, row.middle_name, row.last_name].filter(Boolean).join(' ') || '') + '</td>'
            + '<td>' + String(row.barangay || '') + '</td>'
            + '<td>' + String(row.purok || '') + '</td>'
            + '<td>' + String(row.message || '') + '</td>'
            + '<td>' + String(row.status || '') + '</td>'
            + '</tr>';
        }).join('');
      } catch (_) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load SMS history.</td></tr>';
      }
    }

    document.querySelectorAll('.archive-btn').forEach(function (button) {
      button.addEventListener('click', async function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const isArchived = (button.dataset.status || pwd.status || '') === 'Archived';
        const endpoint = isArchived ? '/unarchive-pwd' : '/archive-pwd';
        const payload = { pwd_id: pwd.id };

        if (!isArchived) {
          payload.reason = window.prompt('Enter archive reason (optional):', '') || '';
        }

        if (!window.confirm(isArchived ? 'Unarchive this PWD record?' : 'Archive this PWD record?')) return;

        try {
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
          });
          const data = await response.json();
          if (!response.ok || !data || data.success === false) {
            throw new Error((data && data.message) ? data.message : 'Failed to update status.');
          }
          updateRowStatus(row, isArchived ? 'Active' : 'Archived');
        } catch (error) {
          alert(error.message || 'Failed to update status.');
        }
      });
    });
    updateBirthdaysBadge();
  })();
</script>
