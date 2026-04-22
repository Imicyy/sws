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
    .table-toolbar { margin-top: 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .table-toolbar .toolbar-search { flex: 1 1 200px; max-width: 320px; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .btn-print-app { background: #0f766e; color: #fff; border-color: #0f766e; }
    .btn-print-app:hover { background: #0d5f59; color: #fff; }
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

        <div class="table-toolbar">
          <input type="text" id="pwdSearchInput" class="toolbar-search" placeholder="Search name, barangay, or purok…" autocomplete="off">
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
        <button type="button" id="printApplicationBtn" class="btn-print-app">Print Application</button>
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

  <div id="smsModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="smsModalTitle">
    <div class="modal-card">
      <div class="modal-header">
        <h3 id="smsModalTitle">Send SMS Assistance</h3>
        <button type="button" class="modal-close" data-close="smsModal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="recipientsList" style="max-height:160px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px; padding:8px; margin-bottom:10px;"></div>
        <label for="smsMessage" style="font-size:12px;color:#6b7280;font-weight:600;">Message</label>
        <textarea id="smsMessage" rows="4" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px;"></textarea>
        <div style="margin-top:6px; font-size:12px; color:#6b7280;">Characters: <span id="charCount">0</span></div>
      </div>
      <div class="modal-actions">
        <button type="button" id="sendSmsModalBtn" class="primary">Send SMS</button>
        <button type="button" data-close="smsModal">Close</button>
      </div>
    </div>
  </div>

  <div id="smsHistoryModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="smsHistoryTitle">
    <div class="modal-card" style="width:min(1100px,100%);">
      <div class="modal-header">
        <h3 id="smsHistoryTitle">SMS History</h3>
        <button type="button" class="modal-close" data-close="smsHistoryModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="overflow:auto;">
          <table class="table table-sm table-bordered mb-0">
            <thead>
              <tr>
                <th>Sent At</th><th>Phone</th><th>Name</th><th>Barangay</th><th>Purok</th><th>Message</th><th>Status</th><th>Received</th>
              </tr>
            </thead>
            <tbody id="smsHistoryTableBody">
              <tr><td colspan="8" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" data-close="smsHistoryModal">Close</button>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
  <script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const barangayFilter = document.getElementById('barangayFilter');
    const purokFilter = document.getElementById('purokFilter');
    const editPwdFrame = document.getElementById('editPwdFrame');
    const viewPwdFrame = document.getElementById('viewPwdFrame');
    let currentViewPwdId = null;
    let currentViewPwdData = null;

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

    function asDateMmDdYyyy(value) {
      if (!value) return '';
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) return '';
      const mm = String(date.getMonth() + 1).padStart(2, '0');
      const dd = String(date.getDate()).padStart(2, '0');
      const yyyy = String(date.getFullYear());
      return mm + '/' + dd + '/' + yyyy;
    }

    function setTextField(form, fieldName, value) {
      try {
        const field = form.getTextField(fieldName);
        field.setText(String(value || ''));
      } catch (error) {}
    }

    function setCheckField(form, fieldName, checked) {
      try {
        const field = form.getCheckBox(fieldName);
        if (checked) field.check();
        else field.uncheck();
      } catch (error) {}
    }

    async function ensurePdfLibLoaded() {
      if (window.PDFLib && window.PDFLib.PDFDocument) {
        return;
      }

      const cdnUrls = [
        'https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js',
        'https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js'
      ];

      for (const url of cdnUrls) {
        try {
          await new Promise(function (resolve, reject) {
            const script = document.createElement('script');
            script.src = url;
            script.async = true;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
          });
          if (window.PDFLib && window.PDFLib.PDFDocument) {
            return;
          }
        } catch (error) {}
      }

      throw new Error('Unable to load pdf-lib library.');
    }

    async function fetchTemplateBytes(pathCandidates) {
      let lastError = 'Template not found.';
      for (const candidate of pathCandidates) {
        try {
          const response = await fetch(candidate, { cache: 'no-store' });
          if (!response.ok) {
            lastError = 'HTTP ' + response.status + ' for ' + candidate;
            continue;
          }
          return await response.arrayBuffer();
        } catch (error) {
          lastError = (error && error.message) ? error.message : String(error);
        }
      }
      throw new Error(lastError);
    }

    async function buildPwdApplicationPdf(pwd) {
      if (!pwd || !pwd.id) {
        throw new Error('Missing PWD record.');
      }
      await ensurePdfLibLoaded();
      const templateBytes = await fetchTemplateBytes([
        '/pdf-template/pwd',
        '/default/pdf/PWD-APPLICATION-FORMFIELD.pdf',
        'default/pdf/PWD-APPLICATION-FORMFIELD.pdf',
        '../default/pdf/PWD-APPLICATION-FORMFIELD.pdf',
        '../../default/pdf/PWD-APPLICATION-FORMFIELD.pdf'
      ]);

      const pdfDoc = await PDFLib.PDFDocument.load(templateBytes);
      const form = pdfDoc.getForm();

      setTextField(form, 'LAST NAME', pwd.last_name || '');
      setTextField(form, 'FIRST NAME', pwd.first_name || '');
      setTextField(form, 'MIDDLE NAME', pwd.middle_name || '');
      setTextField(form, 'Barangay', [pwd.barangay || '', pwd.purok || ''].filter(Boolean).join(' / '));
      setTextField(form, 'DATE OF BIRTH', asDateMmDdYyyy(pwd.birthday || pwd.date_of_birth || ''));
      setTextField(form, 'Employment Category', pwd.employment_type || '');
      setTextField(form, 'SSS NO', pwd.sss_id || '');
      setTextField(form, 'GSIS NO', pwd.gsis_sss_no || '');
      setTextField(form, 'PSN NO', pwd.psn_no || '');
      setTextField(form, 'PhilHealth NO', pwd.philhealth_no || '');

      setTextField(form, 'LAST NAMEFATHERS NAME', pwd.father_last_name || '');
      setTextField(form, 'FIRST NAMEFATHERS NAME', pwd.father_first_name || '');
      setTextField(form, 'MIDDLE NAMEFATHERS NAME', pwd.father_middle_name || '');
      setTextField(form, 'LAST NAMEMOTHERS NAME', pwd.mother_last_name || '');
      setTextField(form, 'FIRST NAMEMOTHERS NAME', pwd.mother_first_name || '');
      setTextField(form, 'MIDDLE NAMEMOTHERS NAME', pwd.mother_middle_name || '');

      const contacts = Array.isArray(pwd.contacts) ? pwd.contacts : [];
      const primary = contacts.find(function (c) { return c && c.phone; }) || null;
      const phone = primary && primary.phone ? primary.phone : '';
      setTextField(form, 'Mobile No', phone);
      setTextField(form, 'Landline No', phone);
      setTextField(form, 'Email Address', primary && primary.email ? primary.email : '');

      setCheckField(form, 'Male', (pwd.gender || '') === 'Male');
      setCheckField(form, 'Female', (pwd.gender || '') === 'Female');
      setCheckField(form, 'APPLICANT', true);

      try { form.flatten(); } catch (error) {}
      return pdfDoc.save();
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
      const searchInput = document.getElementById('pwdSearchInput');
      const searchRaw = (searchInput && searchInput.value) ? searchInput.value.trim().toLowerCase() : '';
      let visibleCount = 0;

      document.querySelectorAll('#pwdTable tbody tr').forEach(row => {
        const rowBarangay = row.dataset.barangay || '';
        const rowPurok = row.dataset.purok || '';
        const rowStatus = row.dataset.status || '';
        const cells = row.querySelectorAll('td');
        const nameText = cells[1] ? cells[1].textContent.toLowerCase() : '';
        const matchesSearch = !searchRaw
          || nameText.indexOf(searchRaw) !== -1
          || rowBarangay.toLowerCase().indexOf(searchRaw) !== -1
          || rowPurok.toLowerCase().indexOf(searchRaw) !== -1;
        const matchesBarangay = !selectedBarangay || rowBarangay === selectedBarangay;
        const matchesPurok = !selectedPurok || rowPurok === selectedPurok;
        const matchesStatus = !statusFilter || rowStatus === statusFilter;
        const show = matchesBarangay && matchesPurok && matchesStatus && matchesSearch;
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

    const pwdSearchInput = document.getElementById('pwdSearchInput');
    if (pwdSearchInput) {
      pwdSearchInput.addEventListener('input', filterTable);
    }

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
      const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked'));
      const recipientsList = document.getElementById('recipientsList');
      recipientsList.innerHTML = '';

      selected.forEach(function (checkbox) {
        const row = checkbox.closest('tr');
        const pwd = getPwdFromRow(row);
        const contacts = Array.isArray(pwd.contacts) ? pwd.contacts : [];
        const primary = contacts.find(c => c && c.phone) || {};
        const phone = String(primary.phone || pwd.contact || '').trim();
        if (!phone) return;
        const fullName = [pwd.first_name, pwd.middle_name, pwd.last_name].filter(Boolean).join(' ').trim() || row.children[1].textContent.trim();
        const div = document.createElement('div');
        div.className = 'recipient-item';
        div.dataset.phone = phone;
        div.dataset.name = fullName;
        div.dataset.firstName = pwd.first_name || '';
        div.dataset.middleName = pwd.middle_name || '';
        div.dataset.lastName = pwd.last_name || '';
        div.dataset.barangay = pwd.barangay || '';
        div.dataset.purok = pwd.purok || '';
        div.dataset.recordId = String(pwd.id || checkbox.value || '');
        div.innerHTML = '<strong>' + escapeHtml(fullName) + '</strong> <span class="text-muted">' + escapeHtml(phone) + '</span>';
        recipientsList.appendChild(div);
      });

      if (!recipientsList.children.length) {
        alert('No selected records have a mobile number.');
        return;
      }

      openModal('smsModal');
    });

    const smsMessage = document.getElementById('smsMessage');
    if (smsMessage) {
      smsMessage.addEventListener('input', function () {
        document.getElementById('charCount').textContent = String(smsMessage.value.length);
      });
    }

    document.getElementById('sendSmsModalBtn').addEventListener('click', async function () {
      const btn = this;
      const message = (document.getElementById('smsMessage').value || '').trim();
      const recipients = Array.from(document.querySelectorAll('#recipientsList .recipient-item')).map(function (el) {
        return {
          phone: el.dataset.phone || '',
          name: el.dataset.name || '',
          first_name: el.dataset.firstName || '',
          middle_name: el.dataset.middleName || '',
          last_name: el.dataset.lastName || '',
          barangay: el.dataset.barangay || '',
          purok: el.dataset.purok || '',
          record_id: el.dataset.recordId || '',
          recipient_type: 'PWD'
        };
      });

      if (!message) {
        alert('Please enter a message.');
        return;
      }
      if (!recipients.length) {
        alert('No recipients selected.');
        return;
      }

      const originalLabel = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Sending...';
      try {
        const response = await fetch('/send-sms', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ recipients: recipients, message: message })
        });
        const payload = await response.json();
        if (!response.ok || !payload || payload.success !== true) {
          alert((payload && (payload.error || payload.message)) ? (payload.error || payload.message) : 'Failed to send SMS.');
          return;
        }
        alert('SMS has been sent successfully!');
        document.getElementById('smsMessage').value = '';
        document.getElementById('charCount').textContent = '0';
        closeModal('smsModal');
      } catch (error) {
        alert('The SMS service is down at the moment, sorry. Please try again later.');
      } finally {
        btn.disabled = false;
        btn.textContent = originalLabel;
      }
    });

    document.getElementById('viewHistoryBtn').addEventListener('click', async function () {
      openModal('smsHistoryModal');
      const tableBody = document.getElementById('smsHistoryTableBody');
      tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Loading...</td></tr>';
      try {
        const response = await fetch('/sms-history?recipient_type=PWD&limit=200');
        const payload = await response.json();
        if (!response.ok || !payload || payload.success !== true || !Array.isArray(payload.data)) {
          tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading SMS history</td></tr>';
          return;
        }
        if (!payload.data.length) {
          tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No SMS history found</td></tr>';
          return;
        }
        tableBody.innerHTML = payload.data.map(function (record) {
          const sentAt = record.sent_at ? new Date(String(record.sent_at).replace(' ', 'T')).toLocaleString() : 'N/A';
          const fullName = [record.first_name, record.middle_name, record.last_name].filter(Boolean).join(' ').trim() || 'N/A';
          const checked = record.received ? 'checked' : '';
          return '<tr>'
            + '<td>' + escapeHtml(sentAt) + '</td>'
            + '<td>' + escapeHtml(record.phone_number || 'N/A') + '</td>'
            + '<td>' + escapeHtml(fullName) + '</td>'
            + '<td>' + escapeHtml(record.barangay || 'N/A') + '</td>'
            + '<td>' + escapeHtml(record.purok || 'N/A') + '</td>'
            + '<td>' + escapeHtml(record.message || 'N/A') + '</td>'
            + '<td>' + escapeHtml(record.status || 'N/A') + '</td>'
            + '<td class="text-center"><input type="checkbox" class="sms-received-checkbox" data-sms-id="' + escapeHtml(record._id || '') + '" ' + checked + '></td>'
            + '</tr>';
        }).join('');
        document.querySelectorAll('.sms-received-checkbox').forEach(function (checkbox) {
          checkbox.addEventListener('change', async function () {
            const isChecked = checkbox.checked;
            try {
              const response = await fetch('/update-sms-received', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ smsId: checkbox.dataset.smsId, received: isChecked })
              });
              if (!response.ok) {
                checkbox.checked = !isChecked;
                alert('Failed to update SMS received status');
              }
            } catch (error) {
              checkbox.checked = !isChecked;
              alert('Failed to update SMS received status');
            }
          });
        });
      } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading SMS history</td></tr>';
      }
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
        currentViewPwdId = String(id);
        currentViewPwdData = pwd;

        openModal('viewModal');
      });
    });

    document.getElementById('printApplicationBtn').addEventListener('click', async function () {
      if (!currentViewPwdId) {
        alert('Please open a PWD record before printing.');
        return;
      }
      try {
        const bytes = await buildPwdApplicationPdf(currentViewPwdData || { id: currentViewPwdId });
        const blob = new Blob([bytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
        setTimeout(function () { URL.revokeObjectURL(url); }, 30000);
      } catch (error) {
        console.error('PWD browser PDF generation failed:', error);
        alert('Failed to generate application PDF in browser: ' + ((error && error.message) ? error.message : String(error)));
      }
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
