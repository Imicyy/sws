<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Social Welfare System - PDAO Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    * { box-sizing: border-box; }
    html, body { width: 100%; min-height: 100%; }
    .layout { display: flex; align-items: stretch; min-height: 100vh; width: 100%; }
    .sidebar { flex: 0 0 260px; width: 260px; background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; overflow-y: auto; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; margin-top: 16px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 14px; }
    .nav-link.active { background: #2563eb; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { flex: 1 1 auto; min-width: 0; padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .top-left { display: flex; align-items: center; gap: 12px; }
    .top-right { display: flex; align-items: center; gap: 12px; }
    .add-pwd-btn {
      display: inline-block;
      padding: 9px 14px;
      border-radius: 8px;
      background: #0f766e;
      color: #fff;
      font-weight: 700;
      text-decoration: none;
      border: none;
      cursor: pointer;
    }
    .add-pwd-btn:hover { background: #115e59; color: #fff; text-decoration: none; }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #eff6ff; color: #1e40af; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
    .dept-badge { display: inline-block; padding: 4px 10px; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px; }
    .filter-group { display: flex; flex-direction: column; }
    .filter-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #6b7280; }
    .filter-group select { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .table-container { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow-x: auto; margin-top: 16px; }
    table { margin: 0; }
    table thead { background: #374151; color: #fff; font-weight: 600; font-size: 12px; }
    table th { padding: 12px; text-align: left; border: none; white-space: nowrap; }
    table td { padding: 12px; border-top: 1px solid #e5e7eb; vertical-align: middle; }
    table tbody tr:hover { background: #f9fafb; }
    .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: #dbeafe; color: #1e40af; }
    .status-archived { background: #f3f4f6; color: #374151; }
    .actions { display: flex; gap: 8px; }
    .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #d1d5db; background: none; cursor: pointer; }
    .btn-view { color: #2563eb; }
    .btn-edit { color: #4f46e5; }
    .btn-archive { color: #dc2626; }
    .btn-sm:hover { background: #f3f4f6; }
    .action-bar { margin-top: 16px; display: flex; gap: 12px; }
    .action-bar button { padding: 10px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
    .btn-sms { background: #2563eb; color: white; }
    .btn-sms:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
    .btn-history { background: #1e40af; color: white; }
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(17, 24, 39, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 18px;
      z-index: 1200;
    }
    .modal-overlay.open { display: flex; }
    .modal-card {
      width: min(760px, 100%);
      max-height: 88vh;
      overflow: auto;
      background: #fff;
      border-radius: 12px;
      border: 1px solid #d1d5db;
      box-shadow: 0 20px 45px rgba(15, 23, 42, 0.25);
    }
    .modal-card.edit-frame {
      width: min(1240px, 100%);
      height: 92vh;
      max-height: 92vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 16px;
      border-bottom: 1px solid #e5e7eb;
    }
    .modal-header h3 { margin: 0; font-size: 18px; }
    .modal-close {
      border: none;
      background: transparent;
      font-size: 22px;
      line-height: 1;
      color: #4b5563;
      cursor: pointer;
    }
    .modal-body { padding: 16px; }
    .modal-body.edit-frame-body {
      padding: 0;
      flex: 1 1 auto;
      overflow: hidden;
      background: #f3f6fb;
    }
    .edit-frame-body iframe {
      width: 100%;
      height: 100%;
      border: 0;
      background: #f3f6fb;
    }
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
    }
    .detail-item { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 12px; }
    .detail-item strong { display: block; font-size: 12px; color: #6b7280; margin-bottom: 4px; }
    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      padding: 14px 16px;
      border-top: 1px solid #e5e7eb;
    }
    .modal-actions button {
      border: 1px solid #d1d5db;
      border-radius: 8px;
      padding: 8px 12px;
      cursor: pointer;
      font-weight: 600;
    }
    .modal-actions .primary {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
    }
    .edit-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
    }
    .edit-grid label { font-size: 12px; color: #6b7280; margin-bottom: 6px; display: block; }
    .edit-grid input,
    .edit-grid select {
      width: 100%;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      padding: 8px 10px;
      font-size: 14px;
    }
    @media (max-width: 980px) {
      .layout { flex-direction: column; }
      .sidebar { flex: none; width: 100%; }
      .cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div style="font-size: 11px; color: #6b7280; margin-bottom: 14px;"><span class="dept-badge">PDAO Department</span></div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/pdao-dashboard">Dashboard</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <div class="top-left">
          <h1 class="h4 mb-0">PDAO Dashboard</h1>
        </div>
        <div class="top-right">
          <button type="button" class="add-pwd-btn" onclick="window.location.href = window.location.origin + '/add_pwd'">Add PWD</button>
          <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'staff'), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      </div>

      <section class="panel">
        <h2 class="h6 mb-3">Persons With Disabilities</h2>
        <div class="filters">
          <div class="filter-group">
            <label>Barangay:</label>
            <select id="barangayFilter">
              <option value="">All Barangays</option>
              <?php foreach (($barangays ?? []) as $brgy => $puroks): ?>
                <option value="<?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filter-group">
            <label>Purok:</label>
            <select id="purokFilter" disabled>
              <option value="">First select a Barangay</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Status:</label>
            <select id="statusFilter" onchange="filterTable()">
              <option value="Active">Active Only</option>
              <option value="Archived">Archived Only</option>
              <option value="">All Records</option>
            </select>
          </div>
        </div>

        <div class="table-container">
          <table id="pwdTable" class="table table-hover mb-0">
            <thead>
              <tr>
                <th style="width: 30px;"><input type="checkbox" id="selectAll"></th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Barangay</th>
                <th>Purok</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($pwds ?? []) as $pwd):
                $fullName = trim((string) ($pwd['first_name'] ?? '') . ' ' . (string) ($pwd['middle_name'] ?? '') . ' ' . (string) ($pwd['last_name'] ?? ''));
                $barangay = (string) ($pwd['barangay'] ?? '');
                $purok = (string) ($pwd['purok'] ?? '');
                $status = (string) ($pwd['status'] ?? 'Active');
                $statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
              ?>
                <tr data-barangay="<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>" data-purok="<?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" data-pwd='<?= htmlspecialchars(json_encode($pwd, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                  <td><input type="checkbox" class="rowCheckbox" value="<?= (int) ($pwd['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars($fullName !== '' ? $fullName : 'Unnamed record', ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= isset($pwd['age']) && $pwd['age'] !== null ? (int) $pwd['age'] : 'N/A' ?></td>
                  <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="actions">
                      <button type="button" class="btn-sm btn-view view-btn" title="View">View</button>
                      <button type="button" class="btn-sm btn-edit edit-btn" title="Edit">Edit</button>
                      <button type="button" class="btn-sm btn-archive archive-btn" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars(($status === 'Archived') ? 'Unarchive' : 'Archive', ENT_QUOTES, 'UTF-8') ?>"><?= $status === 'Archived' ? 'Unarchive' : 'Archive' ?></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="action-bar">
          <button class="btn-sms" id="sendSmsBtn" disabled>Send SMS to Selected</button>
          <button class="btn-history" id="viewHistoryBtn">View SMS History</button>
        </div>
      </section>
    </main>
  </div>

  <div id="viewModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="viewModalTitle">
    <div class="modal-card edit-frame">
      <div class="modal-header">
        <h3 id="viewModalTitle">View PWD Record</h3>
        <button type="button" class="modal-close" data-close="viewModal">&times;</button>
      </div>
      <div class="modal-body edit-frame-body">
        <iframe id="viewPwdFrame" title="View PWD Form" loading="lazy" src="about:blank"></iframe>
      </div>
      <div class="modal-actions">
        <button type="button" data-close="viewModal">Close</button>
      </div>
    </div>
  </div>

  <div id="editModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-card edit-frame">
      <div class="modal-header">
        <h3 id="editModalTitle">Edit PWD Record</h3>
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

  <script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const barangayFilter = document.getElementById('barangayFilter');
    const purokFilter = document.getElementById('purokFilter');
    const editPwdFrame = document.getElementById('editPwdFrame');
    const viewPwdFrame = document.getElementById('viewPwdFrame');

    function openModal(id) {
      const modal = document.getElementById(id);
      if (modal) {
        modal.classList.add('open');
      }
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (modal) {
        modal.classList.remove('open');
      }
      if (id === 'editModal' && editPwdFrame) {
        editPwdFrame.src = 'about:blank';
      }
      if (id === 'viewModal' && viewPwdFrame) {
        viewPwdFrame.src = 'about:blank';
      }
    }

    function getPwdFromRow(row) {
      if (!row) return {};
      try {
        return JSON.parse(row.dataset.pwd || '{}');
      } catch (error) {
        return {};
      }
    }

    function escapeHtml(value) {
      return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function updateRowFromPayload(row, payloadData) {
      if (!row || !payloadData) return;
      const firstName = payloadData.first_name || '';
      const middleName = payloadData.middle_name || '';
      const lastName = payloadData.last_name || '';
      const fullName = [firstName, middleName, lastName].filter(Boolean).join(' ').trim() || 'Unnamed record';
      const age = payloadData.age === null || payloadData.age === undefined || payloadData.age === '' ? 'N/A' : payloadData.age;
      const status = payloadData.status || 'Active';

      row.dataset.barangay = payloadData.barangay || '';
      row.dataset.purok = payloadData.purok || '';
      row.dataset.status = status;
      row.dataset.pwd = JSON.stringify(payloadData);

      const cells = row.querySelectorAll('td');
      if (cells[1]) cells[1].textContent = fullName;
      if (cells[2]) cells[2].textContent = String(age);
      if (cells[3]) cells[3].textContent = payloadData.barangay || '';
      if (cells[4]) cells[4].textContent = payloadData.purok || '';

      const statusBadge = row.querySelector('.status-badge');
      if (statusBadge) {
        statusBadge.textContent = status;
        statusBadge.className = 'status-badge ' + (status === 'Archived' ? 'status-archived' : 'status-active');
      }

      const archiveBtn = row.querySelector('.archive-btn');
      if (archiveBtn) {
        archiveBtn.dataset.status = status;
        archiveBtn.textContent = status === 'Archived' ? 'Unarchive' : 'Archive';
        archiveBtn.title = status === 'Archived' ? 'Unarchive' : 'Archive';
      }

      filterTable();
    }

    function getVisibleRows() {
      return Array.from(document.querySelectorAll('#pwdTable tbody tr')).filter(row => row.style.display !== 'none');
    }

    function filterTable() {
      const statusFilter = document.getElementById('statusFilter').value;
      const selectedBarangay = barangayFilter.value;
      const selectedPurok = purokFilter.value;
      let visibleCount = 0;

      document.querySelectorAll('#pwdTable tbody tr').forEach(row => {
        const rowBarangay = row.dataset.barangay || '';
        const rowPurok = row.dataset.purok || '';
        const rowStatus = row.dataset.status || '';
        const matchesBarangay = !selectedBarangay || rowBarangay === selectedBarangay;
        const matchesPurok = !selectedPurok || rowPurok === selectedPurok;
        const matchesStatus = !statusFilter || rowStatus === statusFilter;
        const show = matchesBarangay && matchesPurok && matchesStatus;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
      });

      const entryCountEl = document.getElementById('entryCount');
      if (entryCountEl) {
        entryCountEl.textContent = visibleCount;
      }
      updateActions();
    }

    function updateActions() {
      document.getElementById('sendSmsBtn').disabled = document.querySelectorAll('.rowCheckbox:checked').length === 0;
    }

    barangayFilter.addEventListener('change', function () {
      const selected = this.value;
      purokFilter.innerHTML = '<option value="">First select a Barangay</option>';
      purokFilter.disabled = true;

      if (selected && barangays[selected]) {
        barangays[selected].forEach(function (purok) {
          const option = document.createElement('option');
          option.value = purok;
          option.textContent = purok;
          purokFilter.appendChild(option);
        });
        purokFilter.disabled = false;
      }

      filterTable();
    });

    purokFilter.addEventListener('change', filterTable);

    document.getElementById('selectAll').addEventListener('change', function () {
      getVisibleRows().forEach(function (row) {
        const checkbox = row.querySelector('.rowCheckbox');
        if (checkbox) {
          checkbox.checked = this.checked;
        }
      }, this);
      updateActions();
    });

    document.querySelectorAll('.rowCheckbox').forEach(function (checkbox) {
      checkbox.addEventListener('change', updateActions);
    });

    document.getElementById('sendSmsBtn').addEventListener('click', function () {
      alert('SMS action is connected to the selected PWD records.');
    });

    document.getElementById('viewHistoryBtn').addEventListener('click', function () {
      alert('SMS history view is not wired yet.');
    });

    document.querySelectorAll('[data-close]').forEach(function (button) {
      button.addEventListener('click', function () {
        closeModal(button.dataset.close);
      });
    });

    document.querySelectorAll('.modal-overlay').forEach(function (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal(modal.id);
        }
      });
    });

    document.querySelectorAll('.view-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const id = pwd.id || 0;
        if (!id) {
          alert('Unable to open view form: missing PWD ID.');
          return;
        }

        if (viewPwdFrame) {
          viewPwdFrame.src = '/add_pwd?edit=' + encodeURIComponent(String(id)) + '&modal=1&view=1';
        }

        openModal('viewModal');
      });
    });

    document.querySelectorAll('.edit-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const id = pwd.id || 0;
        if (!id) {
          alert('Unable to open edit form: missing PWD ID.');
          return;
        }
        if (editPwdFrame) {
          editPwdFrame.src = '/add_pwd?edit=' + encodeURIComponent(String(id)) + '&modal=1';
        }
        openModal('editModal');
      });
    });

    window.addEventListener('message', function (event) {
      if (!event || event.origin !== window.location.origin || !event.data) {
        return;
      }

      if (event.data.type === 'pwd-edit-saved') {
        closeModal('editModal');
        window.location.reload();
      }

      if (event.data.type === 'pwd-edit-cancel') {
        closeModal('editModal');
      }

      if (event.data.type === 'pwd-view-close') {
        closeModal('viewModal');
      }
    });

    document.querySelectorAll('.archive-btn').forEach(function (button) {
      button.addEventListener('click', async function () {
        const row = button.closest('tr');
        const pwd = getPwdFromRow(row);
        const isArchived = (button.dataset.status || pwd.status || '') === 'Archived';
        const endpoint = isArchived ? '/unarchive-pwd' : '/archive-pwd';

        let reason = '';
        if (!isArchived) {
          reason = window.prompt('Enter archive reason (optional):', '') || '';
        }

        const confirmationText = isArchived
          ? 'Unarchive this PWD record?'
          : 'Archive this PWD record?';

        if (!window.confirm(confirmationText)) {
          return;
        }

        try {
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json'
            },
            body: JSON.stringify({
              pwd_id: pwd.id,
              reason: reason
            })
          });
          const payload = await response.json();
          if (!response.ok || !payload || payload.success === false) {
            alert((payload && payload.message) ? payload.message : 'Failed to update archive status.');
            return;
          }

          updateRowFromPayload(row, payload.data || {});
          alert(payload.message || 'Record status updated.');
        } catch (error) {
          alert('Network error while updating archive status.');
        }
      });
    });
  </script>
</body>
</html>
