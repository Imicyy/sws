<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title>Social Welfare System - PDAO Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-bg: linear-gradient(180deg, #ffffff 0%, #fffcf3 100%);
      --panel-border: #dbe5f3;
      --text-main: #1f2937;
      --text-muted: #6b7280;
      --brand-primary: #2563eb;
      --brand-primary-dark: #1e40af;
      --brand-accent: #3b82f6;
      --brand-accent-dark: #2563eb;
    }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: var(--bg-page); color: var(--text-main); }
    * { box-sizing: border-box; }
    html, body { width: 100%; min-height: 100%; }
    .layout { display: flex; align-items: stretch; min-height: 100vh; width: 100%; }
    .sidebar { flex: 0 0 260px; width: 260px; background: #fffef7; border-right: 1px solid var(--panel-border); padding: 20px 14px; overflow-y: auto; position: sticky; top: 0; height: 100vh; align-self: flex-start; box-shadow: 10px 0 24px rgba(59, 130, 246, 0.08); display: flex; flex-direction: column; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; margin-top: 16px; }
    .nav-link { display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 14px; }
    .nav-link.active { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; box-shadow: 0 10px 22px rgba(37, 99, 235, 0.22); }
    .nav-link:hover { background: #eaf3ff; }
    .sidebar .logout-link { margin-top: auto; background: #fee2e2; color: #991b1b; font-weight: 700; }
    .sidebar .logout-link:hover { background: #ef4444; color: #fff; }
    .main { flex: 1 1 auto; min-width: 0; padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; padding: 14px 16px; background: rgba(255, 255, 255, 0.9); border: 1px solid var(--panel-border); border-radius: 14px; box-shadow: 0 12px 24px rgba(59, 130, 246, 0.08); backdrop-filter: blur(2px); }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .top-left { display: flex; align-items: center; gap: 12px; }
    .top-right { display: flex; align-items: center; gap: 12px; }
    .add-pwd-btn {
      display: inline-block;
      padding: 9px 14px;
      border-radius: 8px;
      background: linear-gradient(135deg, #60a5fa, #3b82f6);
      color: #fff;
      font-weight: 700;
      text-decoration: none;
      border: none;
      cursor: pointer;
      box-shadow: 0 10px 20px rgba(59, 130, 246, 0.22);
    }
    .add-pwd-btn:hover { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; text-decoration: none; transform: translateY(-1px); }
    .panel { margin-top: 16px; background: var(--panel-bg); border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06); }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #eff6ff; color: #1e40af; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
    .dept-badge { display: inline-block; padding: 4px 10px; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px; }
    .filter-group { display: flex; flex-direction: column; }
    .filter-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #6b7280; }
    .filter-group select { padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff; transition: border-color .15s ease, box-shadow .15s ease; }
    .filter-group select:focus,
    .table-toolbar .toolbar-search:focus { border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.16); outline: none; }
    .table-container { background: #fff; border-radius: 12px; border: 1px solid var(--panel-border); overflow-x: auto; margin-top: 16px; box-shadow: inset 0 1px 0 #fff, 0 8px 22px rgba(59, 130, 246, 0.08); }
    table { margin: 0; }
    table thead { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: #fff; font-weight: 600; font-size: 12px; }
    table th { padding: 12px; text-align: left; border: none; white-space: nowrap; }
    table td { padding: 12px; border-top: 1px solid #e5e7eb; vertical-align: middle; }
    table tbody tr:hover { background: #f9fafb; }
    .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: #dbeafe; color: #1e40af; }
    .status-archived { background: #f3f4f6; color: #374151; }
    .actions { display: flex; gap: 8px; align-items: center; }
    .action {
      width: 34px;
      height: 34px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      background: #ffffff;
      color: #374151;
      cursor: pointer;
      transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease;
    }
    .action:hover { background: #f3f4f6; transform: translateY(-1px); }
    .action svg { width: 16px; height: 16px; }
    .action.view-btn { color: #3b82f6; }
    .action.action-edit { color: #2563eb; }
    .action.action-archive { color: #dc2626; }
    .actions .action:focus { outline: 2px solid rgba(59, 130, 246, 0.25); outline-offset: 2px; }
    .table-toolbar { margin-top: 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .table-toolbar .toolbar-search { flex: 1 1 240px; max-width: 360px; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #fff; }
    .btn-print-app { background: #3b82f6; color: #fff; border-color: #3b82f6; }
    .btn-print-app:hover { background: #2563eb; color: #fff; }
    .action-bar { margin-top: 16px; display: flex; gap: 12px; }
    .action-bar button { padding: 10px 16px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; box-shadow: 0 8px 16px rgba(15, 23, 42, 0.12); transition: transform .15s ease, box-shadow .15s ease; }
    .action-bar button:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.15); }
    .btn-sms { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
    .btn-sms:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
    .btn-history { background: linear-gradient(135deg, #93c5fd, #3b82f6); color: white; }
    .notif-button {
      position: relative;
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      background: #fff;
      font-weight: 700;
      box-shadow: 0 6px 12px rgba(15, 23, 42, 0.08);
    }
    .notif-dropdown {
      display: none;
      position: absolute;
      right: 20px;
      top: 60px;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.12);
      width: 320px;
      max-height: 300px;
      overflow: auto;
      padding: 8px;
      z-index: 2000;
    }
    .birthdays-button {
      border: 1px solid #d1d5db;
      border-radius: 10px;
      padding: 9px 12px;
      background: #fff;
      font-weight: 700;
      box-shadow: 0 6px 12px rgba(15, 23, 42, 0.08);
    }
    .pagination-wrap { margin-top: 12px; display: flex; justify-content: space-between; align-items: center; }
    #pwdPaginationControls button { border-radius: 8px !important; border: 1px solid #d1d5db !important; background: #fff; }
    #pwdPaginationControls button.active,
    #pwdPaginationControls button.btn-primary { background: var(--brand-primary) !important; border-color: var(--brand-primary) !important; }
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
      .sidebar { flex: none; width: 100%; position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <script>window.__APP_BASE__=<?= json_encode(app_base_path(), JSON_UNESCAPED_UNICODE) ?>;</script>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div style="font-size: 11px; color: #6b7280; margin-bottom: 14px;"><span class="dept-badge">PDAO Department</span></div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="<?= htmlspecialchars(app_url('/pdao-dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a>
      <a class="nav-link logout-link" href="<?= htmlspecialchars(app_url('/logout'), ENT_QUOTES, 'UTF-8') ?>">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <div class="top-left">
          <h1 class="h4 mb-0">Person With Disability - Dashboard</h1>
        </div>
        <div class="top-right">
          <div class="notif-container" style="display:flex;align-items:center;margin-right:12px;">
            <button id="notifBell" class="btn notif-button">
              🔔 <span id="notifBadge" style="position:absolute;top:0;right:0;background:#dc2626;color:#fff;border-radius:10px;padding:2px 6px;font-size:12px;display:none;">0</span>
            </button>
            <div id="notifDropdown" class="notif-dropdown">
              <div style="font-weight:700;padding:8px;border-bottom:1px solid #f3f4f6;">Notifications</div>
              <div id="notifList" style="padding:8px;font-size:13px;color:#374151;"></div>
            </div>
          </div>
          <button type="button" class="btn-sm birthdays-button" id="birthdaysBtn">
            🎂 Birthdays <span id="birthdaysBadge" style="display:none;margin-left:6px;background:#0f766e;color:#fff;border-radius:999px;padding:2px 8px;font-size:12px;">0</span>
          </button>
          <button type="button" class="add-pwd-btn" onclick='window.open(<?= json_encode(app_url('/add_pwd')) ?>, "_blank", "noopener,noreferrer")'>Add PWD</button>
          <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? $user['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
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
                <tr class="pwd-row" data-barangay="<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>" data-purok="<?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" data-pwd='<?= htmlspecialchars(json_encode($pwd, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                  <td><input type="checkbox" class="rowCheckbox" value="<?= (int) ($pwd['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars($fullName !== '' ? $fullName : 'Unnamed record', ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= isset($pwd['age']) && $pwd['age'] !== null ? (int) $pwd['age'] : 'N/A' ?></td>
                  <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="actions">
                      <button type="button" class="action view-btn" title="View" aria-label="View">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <button type="button" class="action action-edit edit-btn" title="Edit" aria-label="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button type="button" class="action action-archive archive-btn" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars(($status === 'Archived') ? 'Unarchive' : 'Archive', ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars(($status === 'Archived') ? 'Unarchive' : 'Archive', ENT_QUOTES, 'UTF-8') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="pagination-wrap">
          <small id="pwdPaginationInfo">Showing 0-0 of 0 records</small>
          <div id="pwdPaginationControls" class="btn-group btn-group-sm" style="display: flex; gap: 4px;"></div>
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
        <div style="display:flex;flex-direction:column;gap:2px;">
          <h3 id="viewModalTitle" style="margin:0;">View PWD Record</h3>
          <div id="pwdSubmittedMeta" style="font-size:12px;color:#6b7280;">Submitted by: <span id="pwdSubmittedBy">—</span> • Submitted at: <span id="pwdSubmittedAt">—</span></div>
        </div>
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

  <div id="birthdaysModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="birthdaysModalTitle">
    <div class="modal-card" style="width:min(980px,100%);">
      <div class="modal-header">
        <h3 id="birthdaysModalTitle">PWD Birthdays</h3>
        <button type="button" class="modal-close" data-close="birthdaysModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
          <input id="bdaySearch" class="toolbar-search" placeholder="Search name/barangay/purok…" style="max-width:320px;">
          <select id="bdayRange" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="today">Today's birthdays</option>
            <option value="month" selected>This month's birthdays</option>
          </select>
          <select id="bdayBarangay" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="">All Barangays</option>
            <?php foreach (($barangays ?? []) as $brgy => $puroks): ?>
              <option value="<?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
          <input id="bdayPurok" placeholder="Purok (optional)" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;min-width:180px;">
        </div>
        <div style="overflow:auto;border:1px solid #e5e7eb;border-radius:10px;">
          <table class="table table-sm mb-0">
            <thead style="background:#f9fafb;">
              <tr>
                <th style="white-space:nowrap;width:34px;"><input type="checkbox" id="birthdaysSelectAll"></th>
                <th style="white-space:nowrap;">Name</th>
                <th style="white-space:nowrap;">Birthday</th>
                <th style="white-space:nowrap;">Barangay</th>
                <th style="white-space:nowrap;">Purok</th>
              </tr>
            </thead>
            <tbody id="birthdaysTableBody">
              <tr><td colspan="5" class="text-center">Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <small id="birthdaysCount" style="display:block;margin-top:8px;color:#6b7280;">0 results</small>
      </div>
      <div class="modal-actions">
        <button type="button" id="birthdaysSendSmsBtn" class="primary">Send SMS</button>
        <button type="button" id="birthdaysViewHistoryBtn">View SMS History</button>
        <button type="button" data-close="birthdaysModal">Close</button>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    function appPath(p) {
      var b = window.__APP_BASE__ || '';
      return b + (p.charAt(0) === '/' ? p : '/' + p);
    }
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

    function parseDbDateTimeToLocalString(value) {
      if (!value) return 'N/A';
      const raw = String(value).trim();
      if (!raw) return 'N/A';
      // DB returns "YYYY-MM-DD HH:MM:SS" (no timezone). Treat as UTC then display locally.
      const iso = raw.includes('T') ? raw : raw.replace(' ', 'T');
      const hasTz = /([zZ]|[+\-]\d\d:\d\d)$/.test(iso);
      const date = new Date(hasTz ? iso : (iso + 'Z'));
      if (Number.isNaN(date.getTime())) return raw;
      return date.toLocaleString();
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
        field.setFontSize(10);
      } catch (error) {}
    }

    function setTextFieldByNamePattern(form, patterns, value) {
      try {
        const normalizedPatterns = Array.isArray(patterns) ? patterns : [patterns];
        const fields = form.getFields();
        fields.forEach(function (field) {
          if (!field || typeof field.getName !== 'function') return;
          const fieldName = String(field.getName() || '');
          const normalizedName = fieldName.toLowerCase().replace(/[^a-z]/g, '');
          const matched = normalizedPatterns.some(function (pattern) {
            return normalizedName.indexOf(String(pattern).toLowerCase()) !== -1;
          });
          if (matched) {
            setTextField(form, fieldName, value);
          }
        });
      } catch (error) {}
    }

    function setLocationFieldsByGeometry(form) {
      try {
        const textFields = [];
        const fields = form.getFields();
        fields.forEach(function (field) {
          if (!field || typeof field.getName !== 'function' || typeof field.getText !== 'function') return;
          const name = String(field.getName() || '');
          const normalized = name.toLowerCase().replace(/[^a-z]/g, '');
          if (normalized.includes('municipality') || normalized.includes('province') || normalized.includes('region')) {
            return;
          }
          let rect = null;
          try {
            const widgets = field.acroField && typeof field.acroField.getWidgets === 'function'
              ? field.acroField.getWidgets()
              : [];
            if (widgets && widgets[0] && typeof widgets[0].getRectangle === 'function') {
              rect = widgets[0].getRectangle();
            }
          } catch (error) {}
          if (!rect || typeof rect.x !== 'number' || typeof rect.y !== 'number') return;
          const currentValue = String(field.getText() || '').trim();
          textFields.push({ name: name, x: rect.x, y: rect.y, w: Number(rect.width || 0), value: currentValue });
        });

        const emptyCandidates = textFields.filter(function (f) { return f.value === ''; });
        if (emptyCandidates.length < 3) return;

        const rows = [];
        emptyCandidates
          .sort(function (a, b) { return (b.y - a.y) || (a.x - b.x); })
          .forEach(function (f) {
            const row = rows.find(function (r) { return Math.abs(r.y - f.y) <= 2.5; });
            if (row) {
              row.items.push(f);
            } else {
              rows.push({ y: f.y, items: [f] });
            }
          });

        const row = rows.find(function (r) {
          if (r.items.length < 3) return false;
          const items = r.items.slice().sort(function (a, b) { return a.x - b.x; });
          const widths = items.slice(0, 3).map(function (i) { return i.w; });
          const minX = items[0].x;
          const maxX = items[Math.min(items.length - 1, 2)].x;
          const avgW = widths.reduce(function (acc, n) { return acc + n; }, 0) / widths.length;
          return minX <= 25 && maxX >= 120 && avgW >= 40 && avgW <= 90;
        });

        if (!row) return;
        const sorted = row.items.slice().sort(function (a, b) { return a.x - b.x; });
        setTextField(form, sorted[0].name, 'ENRIQUE B. MAGALONA');
        setTextField(form, sorted[1].name, 'NEGROS OCCIDENTAL');
        setTextField(form, sorted[2].name, 'NIR');
      } catch (error) {}
    }

    function setCheckField(form, fieldName, checked) {
      try {
        const field = form.getCheckBox(fieldName);
        if (checked) field.check();
        else field.uncheck();
      } catch (error) {}
    }

    function setRadioField(form, fieldName, option) {
      try {
        const field = form.getRadioGroup(fieldName);
        if (option) {
          field.select(option);
        } else {
          field.clear();
        }
      } catch (error) {}
    }

    function normalizeToken(value) {
      return String(value || '').toLowerCase().replace(/[^a-z0-9]/g, '');
    }

    function selectRadioOptionByPattern(form, radioFieldNames, optionPatterns) {
      const patterns = (Array.isArray(optionPatterns) ? optionPatterns : [optionPatterns]).map(normalizeToken).filter(Boolean);
      const names = Array.isArray(radioFieldNames) ? radioFieldNames : [radioFieldNames];
      for (let i = 0; i < names.length; i += 1) {
        const groupName = names[i];
        try {
          const group = form.getRadioGroup(groupName);
          if (!group || typeof group.getOptions !== 'function') continue;
          const options = group.getOptions();
          const match = options.find(function (opt) {
            const token = normalizeToken(opt);
            return patterns.some(function (p) { return token.includes(p) || p.includes(token); });
          });
          if (match) {
            group.select(match);
            return true;
          }
        } catch (error) {}
      }
      return false;
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
      function pick() {
        for (let i = 0; i < arguments.length; i += 1) {
          const value = arguments[i];
          if (value !== undefined && value !== null && String(value).trim() !== '') return value;
        }
        return '';
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

      setTextField(form, 'LAST NAME', pick(pwd.last_name, pwd.lastName));
      setTextField(form, 'FIRST NAME', pick(pwd.first_name, pwd.firstName));
      setTextField(form, 'MIDDLE NAME', pick(pwd.middle_name, pwd.middleName));
      setTextField(form, 'Barangay', [pick(pwd.barangay), pick(pwd.purok)].filter(Boolean).join(' / '));
      setTextFieldByNamePattern(form, ['municipality'], 'ENRIQUE B. MAGALONA');
      setTextFieldByNamePattern(form, ['province'], 'NEGROS OCCIDENTAL');
      setTextFieldByNamePattern(form, ['region'], 'NIR');
      setLocationFieldsByGeometry(form);
      setTextField(form, 'DATE OF BIRTH', asDateMmDdYyyy(pick(pwd.birthday, pwd.date_of_birth)));
      setTextField(form, 'Employment Category', pick(pwd.employment_type, pwd.employmentType));
      setTextField(form, 'SSS NO', pick(pwd.sss_id, pwd.sssId));
      setTextField(form, 'GSIS NO', pick(pwd.gsis_sss_no, pwd.gsisSssNo));
      setTextField(form, 'PSN NO', pick(pwd.psn_no, pwd.psnNo));
      setTextField(form, 'PhilHealth NO', pick(pwd.philhealth_no, pwd.philhealthNo));

      setTextField(form, 'LAST NAMEFATHERS NAME', pick(pwd.father_last_name, pwd.fatherLastName));
      setTextField(form, 'FIRST NAMEFATHERS NAME', pick(pwd.father_first_name, pwd.fatherFirstName));
      setTextField(form, 'MIDDLE NAMEFATHERS NAME', pick(pwd.father_middle_name, pwd.fatherMiddleName));
      setTextField(form, 'LAST NAMEMOTHERS NAME', pick(pwd.mother_last_name, pwd.motherLastName));
      setTextField(form, 'FIRST NAMEMOTHERS NAME', pick(pwd.mother_first_name, pwd.motherFirstName));
      setTextField(form, 'MIDDLE NAMEMOTHERS NAME', pick(pwd.mother_middle_name, pwd.motherMiddleName));

      const contacts = Array.isArray(pwd.contacts) ? pwd.contacts : [];
      const primary = contacts.find(function (c) { return c && c.phone; }) || null;
      const phone = primary && primary.phone ? primary.phone : '';
      setTextField(form, 'Mobile No', phone);
      setTextField(form, 'Landline No', phone);
      setTextField(form, 'Email Address', primary && primary.email ? primary.email : '');

      setCheckField(form, 'Male', pick(pwd.gender) === 'Male');
      setCheckField(form, 'Female', pick(pwd.gender) === 'Female');
      const civilStatus = pick(pwd.civil_status, pwd.marital_status);
      const civilStatusMap = {
        Single: 'Single',
        'Single but Head of the Family': 'Single',
        Separated: 'Separated',
        'Cohabitation (live-in)': 'Cohabitation livein',
        Married: 'Married',
        'Widow/er': 'Widower',
        Widowed: 'Widower'
      };
      setRadioField(form, '7 CIVIL STATUS', civilStatusMap[civilStatus] || '');
      setCheckField(form, 'APPLICANT', true);

      const educational = normalizeToken(pick(pwd.education_level));
      const educationTarget = educational.includes('notattended') ? 'None'
        : educational.includes('elementary') ? 'Elementary'
        : educational.includes('college') || educational.includes('postgraduate') || educational.includes('vocational') || educational.includes('highschool')
          ? 'Junior High School'
          : 'Junior High School';
      setRadioField(form, '12 EDUCATIONAL ATTAINMENT', educationTarget);
      selectRadioOptionByPattern(form, ['12 EDUCATIONAL ATTAINMENT', 'Select Educational Level', 'Educational Attainment'], [educationTarget, educational]);
      setCheckField(form, 'Senior High School', educational.includes('highschool'));
      setCheckField(form, 'College', educational.includes('college') || educational.includes('postgraduate'));
      setCheckField(form, 'Vocational', educational.includes('vocational'));
      setCheckField(form, 'Post Graduate', educational.includes('postgraduate'));

      const employmentStatusRaw = pick(pwd.employment_status);
      const employmentStatus = normalizeToken(employmentStatusRaw);
      const employmentStatusOption = employmentStatus.includes('self') ? 'Selfemployed'
        : employmentStatus.includes('unemployed') ? 'Unemployed'
        : 'Employed';
      setRadioField(form, '13 STATUS OF EMPLOYMENT', employmentStatusOption);
      selectRadioOptionByPattern(form, ['13 STATUS OF EMPLOYMENT', 'Status of Employment'], [employmentStatusOption, employmentStatusRaw]);

      const employmentCategoryRaw = pick(pwd.employment_category);
      setRadioField(form, '13 a CATEGORY OF EMPLOYMENT', employmentCategoryRaw);
      selectRadioOptionByPattern(form, ['13 a CATEGORY OF EMPLOYMENT', 'Category of Employment'], [employmentCategoryRaw]);

      const employmentTypeRaw = pick(pwd.employment_type, pwd.employmentType);
      selectRadioOptionByPattern(form, ['Type of Employment', 'Employment Type'], [employmentTypeRaw]);
      setCheckField(form, employmentTypeRaw, true);

      try { form.flatten(); } catch (error) {}
      return pdfDoc.save();
    }

    async function fetchPwdRecordForPrint(id) {
      const response = await fetch(appPath('/api/pwd-record/' + encodeURIComponent(String(id))), {
        credentials: 'same-origin',
        headers: { Accept: 'application/json' }
      });
      const payload = await response.json();
      if (!response.ok || !payload || payload.success === false || !payload.data) {
        throw new Error((payload && (payload.message || payload.error)) ? (payload.message || payload.error) : 'Unable to load PWD record for printing.');
      }
      return payload.data;
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
        archiveBtn.title = status === 'Archived' ? 'Unarchive' : 'Archive';
        archiveBtn.setAttribute('aria-label', status === 'Archived' ? 'Unarchive' : 'Archive');
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
      updatePwdPagination();
    }

    function updateActions() {
      document.getElementById('sendSmsBtn').disabled = document.querySelectorAll('.rowCheckbox:checked').length === 0;
    }

    // Pagination variables
    let pwdCurrentPage = 1;
    const pwdItemsPerPage = 10;

    function getPwdVisibleRows() {
      return Array.from(document.querySelectorAll('#pwdTable tbody .pwd-row')).filter(row => row.style.display !== 'none');
    }

    function updatePwdPagination() {
      const visibleRows = getPwdVisibleRows();
      const totalItems = visibleRows.length;
      const totalPages = Math.ceil(totalItems / pwdItemsPerPage);
      
      // Reset to first page if current page is out of bounds
      if (pwdCurrentPage > totalPages && totalPages > 0) {
        pwdCurrentPage = totalPages;
      } else if (totalPages === 0) {
        pwdCurrentPage = 1;
      }
      
      // Update pagination info
      const startItem = totalItems === 0 ? 0 : (pwdCurrentPage - 1) * pwdItemsPerPage + 1;
      const endItem = Math.min(pwdCurrentPage * pwdItemsPerPage, totalItems);
      document.getElementById('pwdPaginationInfo').textContent = `Showing ${startItem}-${endItem} of ${totalItems} records`;
      
      // Show/hide rows based on current page
      visibleRows.forEach((row, index) => {
        const rowPage = Math.floor(index / pwdItemsPerPage) + 1;
        row.style.display = rowPage === pwdCurrentPage ? '' : 'none';
      });
      
      // Update pagination controls
      renderPwdPaginationControls(totalPages);
    }

    function renderPwdPaginationControls(totalPages) {
      const controls = document.getElementById('pwdPaginationControls');
      controls.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      // Previous button
      const prevBtn = document.createElement('button');
      prevBtn.className = 'btn btn-sm btn-outline-secondary';
      prevBtn.textContent = 'Previous';
      prevBtn.disabled = pwdCurrentPage === 1;
      prevBtn.onclick = () => {
        if (pwdCurrentPage > 1) {
          pwdCurrentPage--;
          updatePwdPagination();
        }
      };
      controls.appendChild(prevBtn);
      
      // Page numbers
      const maxVisiblePages = 5;
      let startPage = Math.max(1, pwdCurrentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
      
      if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `btn btn-sm ${i === pwdCurrentPage ? 'btn-primary' : 'btn-outline-secondary'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => {
          pwdCurrentPage = i;
          updatePwdPagination();
        };
        controls.appendChild(pageBtn);
      }
      
      // Next button
      const nextBtn = document.createElement('button');
      nextBtn.className = 'btn btn-sm btn-outline-secondary';
      nextBtn.textContent = 'Next';
      nextBtn.disabled = pwdCurrentPage === totalPages;
      nextBtn.onclick = () => {
        if (pwdCurrentPage < totalPages) {
          pwdCurrentPage++;
          updatePwdPagination();
        }
      };
      controls.appendChild(nextBtn);
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
        const response = await fetch(appPath('/send-sms'), {
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
        const response = await fetch(appPath('/sms-history?recipient_type=PWD&limit=200'));
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
              const response = await fetch(appPath('/update-sms-received'), {
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
          viewPwdFrame.src = appPath('/add_pwd') + '?edit=' + encodeURIComponent(String(id)) + '&modal=1&view=1';
        }
        currentViewPwdId = String(id);
        currentViewPwdData = pwd;

        (function renderSubmitMetaFromRecord() {
          const byEl = document.getElementById('pwdSubmittedBy');
          const atEl = document.getElementById('pwdSubmittedAt');
          const by = pwd.created_by || pwd.createdBy || pwd.submitted_by || pwd.submittedBy || pwd.added_by || pwd.addedBy || 'Unknown';
          const atRaw = pwd.created_at || pwd.createdAt || pwd.submitted_at || pwd.submittedAt || '';
          const at = parseDbDateTimeToLocalString(atRaw);
          if (byEl) byEl.textContent = String(by || 'Unknown');
          if (atEl) atEl.textContent = at || 'N/A';
        })();

        openModal('viewModal');
      });
    });

    document.getElementById('printApplicationBtn').addEventListener('click', async function () {
      if (!currentViewPwdId) {
        alert('Please open a PWD record before printing.');
        return;
      }
      try {
        const fullRecord = await fetchPwdRecordForPrint(currentViewPwdId);
        const bytes = await buildPwdApplicationPdf(fullRecord);
        const rawLastName = String(fullRecord.last_name || fullRecord.lastName || 'APPLICANT').trim();
        const safeLastName = rawLastName.replace(/[\\/:*?"<>|]/g, '').replace(/\s+/g, '_') || 'APPLICANT';
        const fileName = safeLastName.toUpperCase() + '.pdf';
        const file = new File([bytes], fileName, { type: 'application/pdf' });
        const url = URL.createObjectURL(file);
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
          editPwdFrame.src = appPath('/add_pwd') + '?edit=' + encodeURIComponent(String(id)) + '&modal=1&nocache=' + String(Date.now());
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
        const endpoint = appPath(isArchived ? '/unarchive-pwd' : '/archive-pwd');
        const actionLabel = isArchived ? 'unarchive' : 'archive';

        let reason = '';
        if (window.Swal && typeof Swal.fire === 'function') {
          const result = await Swal.fire({
            title: isArchived ? 'Unarchive PWD record?' : 'Archive PWD record?',
            input: 'textarea',
            inputLabel: isArchived ? 'Reason for unarchiving (optional)' : 'Reason for archiving (optional)',
            inputPlaceholder: isArchived ? 'Reason for unarchiving' : 'Reason for archiving',
            showCancelButton: true,
            confirmButtonText: isArchived ? 'Unarchive' : 'Archive',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0f766e'
          });
          if (!result.isConfirmed) return;
          reason = String(result.value || '').trim();
        } else {
          reason = window.prompt(
            isArchived ? 'Enter unarchive reason (optional):' : 'Enter archive reason (optional):',
            ''
          ) || '';
          const confirmationText = isArchived ? 'Unarchive this PWD record?' : 'Archive this PWD record?';
          if (!window.confirm(confirmationText)) return;
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
            throw new Error((payload && payload.message) ? payload.message : 'Failed to update archive status.');
          }

          updateRowFromPayload(row, payload.data || {});
          if (window.Swal && typeof Swal.fire === 'function') {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: payload.message || ('Record ' + actionLabel + 'd successfully')
            });
          } else {
            alert(payload.message || 'Record status updated.');
          }
        } catch (error) {
          if (window.Swal && typeof Swal.fire === 'function') {
            Swal.fire({ icon: 'error', title: 'Error', text: error.message || 'Network error while updating archive status.' });
          } else {
            alert(error.message || 'Network error while updating archive status.');
          }
        }
      });
    });

    // Initialize pagination
    updatePwdPagination();

    // Birthdays (PWD)
    const birthdaysBtn = document.getElementById('birthdaysBtn');
    const birthdaysBadge = document.getElementById('birthdaysBadge');
    const birthdaysBody = document.getElementById('birthdaysTableBody');
    const birthdaysCount = document.getElementById('birthdaysCount');
    const bdaySearch = document.getElementById('bdaySearch');
    const bdayRange = document.getElementById('bdayRange');
    const bdayBarangay = document.getElementById('bdayBarangay');
    const bdayPurok = document.getElementById('bdayPurok');
    const birthdaysSelectAll = document.getElementById('birthdaysSelectAll');
    const birthdaysSendSmsBtn = document.getElementById('birthdaysSendSmsBtn');
    const birthdaysViewHistoryBtn = document.getElementById('birthdaysViewHistoryBtn');
    const birthdayGreetingMessage = 'Happy Birthday! Greetings from Mayor Matthew Louis P. Malacon and Vice Mayor Marvin M. Malacon.';
    let birthdaysCache = [];

    function getPwdRecordById(recordId) {
      if (!recordId) return null;
      const selector = '#pwdTable tbody .rowCheckbox[value="' + String(recordId).replace(/"/g, '\\"') + '"]';
      const checkbox = document.querySelector(selector);
      if (!checkbox) return null;
      const row = checkbox.closest('tr');
      return row ? getPwdFromRow(row) : null;
    }

    function getBirthdayRowById(recordId) {
      const targetId = String(recordId || '');
      return birthdaysCache.find(function (row) {
        return String(row.id || '') === targetId;
      }) || null;
    }

    function renderBirthdaysTable() {
      if (!birthdaysBody) return;
      const q = (bdaySearch && bdaySearch.value ? bdaySearch.value : '').trim().toLowerCase();
      const brgy = bdayBarangay ? bdayBarangay.value : '';
      const purokQ = (bdayPurok && bdayPurok.value ? bdayPurok.value : '').trim().toLowerCase();
      const filtered = birthdaysCache.filter(function (row) {
        const name = String(row.full_name || '').toLowerCase();
        const barangay = String(row.barangay || '');
        const purok = String(row.purok || '');
        const matchesQ = !q || name.includes(q) || barangay.toLowerCase().includes(q) || purok.toLowerCase().includes(q);
        const matchesBrgy = !brgy || barangay === brgy;
        const matchesPurok = !purokQ || purok.toLowerCase().includes(purokQ);
        return matchesQ && matchesBrgy && matchesPurok;
      });

      if (!filtered.length) {
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No birthdays found.</td></tr>';
      } else {
        birthdaysBody.innerHTML = filtered.map(function (row) {
          return '<tr>'
            + '<td><input type="checkbox" class="birthday-row-checkbox" value="' + escapeHtml(row.id || '') + '"></td>'
            + '<td>' + escapeHtml(row.full_name || 'N/A') + '</td>'
            + '<td>' + escapeHtml(row.birth_date || 'N/A') + '</td>'
            + '<td>' + escapeHtml(row.barangay || 'N/A') + '</td>'
            + '<td>' + escapeHtml(row.purok || 'N/A') + '</td>'
            + '</tr>';
        }).join('');
      }
      if (birthdaysCount) birthdaysCount.textContent = filtered.length + ' results';
      const rowChecks = birthdaysBody.querySelectorAll('.birthday-row-checkbox');
      rowChecks.forEach(function (cb) {
        cb.addEventListener('change', function () {
          if (birthdaysSelectAll) {
            const all = birthdaysBody.querySelectorAll('.birthday-row-checkbox');
            const checked = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked');
            birthdaysSelectAll.checked = all.length > 0 && all.length === checked.length;
          }
          if (birthdaysSendSmsBtn) {
            birthdaysSendSmsBtn.disabled = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked').length === 0;
          }
        });
      });
      if (birthdaysSelectAll) {
        birthdaysSelectAll.checked = false;
      }
      if (birthdaysSendSmsBtn) {
        birthdaysSendSmsBtn.disabled = true;
      }
    }

    async function loadBirthdays(range) {
      if (!birthdaysBody) return;
      birthdaysBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
      try {
        const res = await fetch(appPath('/api/birthdays?type=pwd&range=' + encodeURIComponent(range || 'month')), { credentials: 'same-origin' });
        const json = await res.json();
        birthdaysCache = (json && json.success && Array.isArray(json.data)) ? json.data : [];
        renderBirthdaysTable();
      } catch (e) {
        birthdaysCache = [];
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load birthdays.</td></tr>';
        if (birthdaysCount) birthdaysCount.textContent = '0 results';
        if (birthdaysSendSmsBtn) birthdaysSendSmsBtn.disabled = true;
      }
    }

    async function updateBirthdaysBadge() {
      try {
        const res = await fetch(appPath('/api/birthdays?type=pwd&range=today'), { credentials: 'same-origin' });
        const json = await res.json();
        const todayCount = (json && json.success && Array.isArray(json.data)) ? json.data.length : 0;
        if (birthdaysBadge) {
          birthdaysBadge.style.display = todayCount > 0 ? '' : 'none';
          birthdaysBadge.textContent = String(todayCount);
        }
      } catch (e) {
        if (birthdaysBadge) birthdaysBadge.style.display = 'none';
      }
    }

    if (birthdaysBtn) {
      birthdaysBtn.addEventListener('click', async function () {
        openModal('birthdaysModal');
        const range = bdayRange ? bdayRange.value : 'month';
        await loadBirthdays(range);
      });
    }
    if (bdayRange) bdayRange.addEventListener('change', function () { loadBirthdays(bdayRange.value); });
    if (bdaySearch) bdaySearch.addEventListener('input', renderBirthdaysTable);
    if (bdayBarangay) bdayBarangay.addEventListener('change', renderBirthdaysTable);
    if (bdayPurok) bdayPurok.addEventListener('input', renderBirthdaysTable);
    if (birthdaysSelectAll) {
      birthdaysSelectAll.addEventListener('change', function () {
        birthdaysBody.querySelectorAll('.birthday-row-checkbox').forEach(function (cb) {
          cb.checked = birthdaysSelectAll.checked;
        });
        if (birthdaysSendSmsBtn) {
          birthdaysSendSmsBtn.disabled = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked').length === 0;
        }
      });
    }
    if (birthdaysSendSmsBtn) {
      birthdaysSendSmsBtn.addEventListener('click', function () {
        const selected = Array.from(birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked'));
        const recipientsList = document.getElementById('recipientsList');
        recipientsList.innerHTML = '';

        selected.forEach(function (checkbox) {
          const birthdayRow = getBirthdayRowById(checkbox.value);
          const pwd = getPwdRecordById(checkbox.value);
          const contacts = Array.isArray(pwd && pwd.contacts) ? pwd.contacts : [];
          const primary = contacts.find(function (c) { return c && c.phone; }) || {};
          const phone = String((birthdayRow && birthdayRow.mobile_number) || primary.phone || (pwd && pwd.contact) || '').trim();
          if (!phone) return;
          const fullName = (birthdayRow && birthdayRow.full_name)
            ? String(birthdayRow.full_name)
            : ([pwd && pwd.first_name, pwd && pwd.middle_name, pwd && pwd.last_name].filter(Boolean).join(' ').trim() || 'N/A');
          const div = document.createElement('div');
          div.className = 'recipient-item';
          div.dataset.phone = phone;
          div.dataset.name = fullName;
          div.dataset.firstName = (birthdayRow && birthdayRow.first_name) || (pwd && pwd.first_name) || '';
          div.dataset.middleName = (birthdayRow && birthdayRow.middle_name) || (pwd && pwd.middle_name) || '';
          div.dataset.lastName = (birthdayRow && birthdayRow.last_name) || (pwd && pwd.last_name) || '';
          div.dataset.barangay = (birthdayRow && birthdayRow.barangay) || (pwd && pwd.barangay) || '';
          div.dataset.purok = (birthdayRow && birthdayRow.purok) || (pwd && pwd.purok) || '';
          div.dataset.recordId = String((birthdayRow && birthdayRow.id) || (pwd && pwd.id) || checkbox.value || '');
          div.innerHTML = '<strong>' + escapeHtml(fullName) + '</strong> <span class="text-muted">' + escapeHtml(phone) + '</span>';
          recipientsList.appendChild(div);
        });

        if (!recipientsList.children.length) {
          alert('No selected birthday records have a mobile number.');
          return;
        }

        const smsMessageEl = document.getElementById('smsMessage');
        const charCountEl = document.getElementById('charCount');
        if (smsMessageEl) {
          smsMessageEl.value = birthdayGreetingMessage;
        }
        if (charCountEl) {
          charCountEl.textContent = String(birthdayGreetingMessage.length);
        }

        closeModal('birthdaysModal');
        openModal('smsModal');
      });
    }
    if (birthdaysViewHistoryBtn) {
      birthdaysViewHistoryBtn.addEventListener('click', function () {
        closeModal('birthdaysModal');
        const btn = document.getElementById('viewHistoryBtn');
        if (btn) btn.click();
      });
    }
    updateBirthdaysBadge();
  </script>
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    (function () {
      const badge = document.getElementById('notifBadge');
      const list = document.getElementById('notifList');
      const dropdown = document.getElementById('notifDropdown');
      const bell = document.getElementById('notifBell');
      let notifications = [];
      function escapeHtml(text) {
        return String(text == null ? '' : text).replace(/[&<>"']/g, function (c) {
          return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
      }
      function formatDate(value) {
        if (!value) return new Date().toLocaleString();
        const parsed = new Date(value);
        return Number.isNaN(parsed.getTime()) ? new Date().toLocaleString() : parsed.toLocaleString();
      }
      async function markNotificationRead(notificationId) {
        try {
          await fetch(appPath('/api/notifications/mark-read'), {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: notificationId })
          });
        } catch (error) {
          console.warn('Failed to mark notification as read', error);
        }
      }
      function renderNotifications() {
        if (!list) return;
        const unreadCount = notifications.filter(n => !n.is_read).length;
        if (badge) {
          badge.style.display = unreadCount > 0 ? '' : 'none';
          badge.textContent = String(unreadCount);
        }
        if (notifications.length === 0) {
          list.innerHTML = '<div style="padding:8px;color:#6b7280;">No notifications yet.</div>';
          return;
        }
        list.innerHTML = '';
        notifications.forEach(function (notif) {
          const item = document.createElement('div');
          item.style.padding = '10px';
          item.style.borderBottom = '1px solid #f3f4f6';
          item.style.cursor = 'pointer';
          item.style.background = notif.is_read ? '#fff' : '#f8fafc';
          item.innerHTML =
            '<div style="display:flex;justify-content:space-between;gap:8px;">' +
              '<div style="font-weight:700;">' + escapeHtml(notif.subject || notif.from || 'Alert') + '</div>' +
              (notif.is_read ? '' : '<span style="font-size:11px;color:#0f766e;font-weight:700;">NEW</span>') +
            '</div>' +
            '<div style="font-size:13px;margin-top:4px;">' + escapeHtml(notif.message || '') + '</div>' +
            '<div style="font-size:12px;color:#6b7280;margin-top:6px;">' + escapeHtml(formatDate(notif.created_at)) + '</div>';
          item.addEventListener('click', async function () {
            if (!notif.is_read) {
              await markNotificationRead(notif.id);
              notif.is_read = true;
              renderNotifications();
            }
          });
          list.appendChild(item);
        });
      }
      async function loadNotifications() {
        try {
          const res = await fetch(appPath('/api/notifications'), { credentials: 'same-origin' });
          const json = await res.json();
          notifications = (json && json.success && Array.isArray(json.data)) ? json.data : [];
          renderNotifications();
        } catch (error) {
          console.warn('Failed to load notifications', error);
        }
      }
      async function markVisibleNotificationsRead() {
        const unread = notifications.filter(n => !n.is_read && n.id);
        if (unread.length === 0) return;
        await Promise.all(unread.map(n => markNotificationRead(n.id)));
        notifications = notifications.map(n => ({ ...n, is_read: true }));
        renderNotifications();
      }

      if (bell) {
        bell.addEventListener('click', async function (e) {
          e.stopPropagation();
          if (!dropdown) return;
          dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
          if (dropdown.style.display === 'block') {
            await loadNotifications();
            await markVisibleNotificationsRead();
          }
        });
        if (dropdown) {
          dropdown.addEventListener('click', function (e) { e.stopPropagation(); });
        }
        document.addEventListener('click', function () { if (dropdown) dropdown.style.display = 'none'; });
      }

      // Same origin first, then common Node ports (skip page port to avoid duplicate tries on :8080 etc.)
      const host = window.location.hostname || 'localhost';
      const proto = window.location.protocol === 'https:' ? 'https' : 'http';
      var __pagePort = String(window.location.port || '');
      var ports = [null];
      ['3000', '8080'].forEach(function (p) { if (p !== __pagePort) { ports.push(p); } });
      (function tryConnect(i) {
        if (i >= ports.length) return;
        try {
          const s = ports[i] === null ? io({ transports: ['websocket','polling'], timeout: 4000 }) : io(proto + '://' + host + ':' + ports[i], { transports: ['websocket','polling'], timeout: 4000 });
          s.on('connect', function () { window._socket = s; console.debug('notif socket connected', s.id); });
          s.on('connect_error', function () { tryConnect(i+1); });
          s.on('receive-alert', function () { loadNotifications(); });
        } catch (e) { tryConnect(i+1); }
      })(0);
      loadNotifications();
      setInterval(loadNotifications, 10000);
      document.addEventListener('visibilitychange', function () {
        if (!document.hidden) loadNotifications();
      });
    })();

    (function monitorSessionReplacement() {
      const expectedUserId = <?= json_encode((int) ($user['_id'] ?? 0), JSON_UNESCAPED_UNICODE) ?>;
      if (!expectedUserId) return;
      const storageKey = 'swsActiveUserId';

      try { localStorage.setItem(storageKey, String(expectedUserId)); } catch (_) {}

      function forceLogout() {
        window.location.replace(appPath('/?session_replaced=1'));
      }

      function checkLocalActiveUser() {
        try {
          const active = Number(localStorage.getItem(storageKey) || 0);
          if (active && active !== expectedUserId) forceLogout();
        } catch (_) {}
      }

      window.addEventListener('storage', function (event) {
        if (event.key !== storageKey) return;
        const active = Number(event.newValue || 0);
        if (active && active !== expectedUserId) forceLogout();
      });

      async function checkSession() {
        try {
          const res = await fetch(appPath('/api/session-state'), { credentials: 'same-origin', cache: 'no-store' });
          if (!res.ok) {
            forceLogout();
            return;
          }
          const data = await res.json();
          const activeUserId = Number((data && data.user && data.user._id) || 0);
          if (!data || data.success !== true || activeUserId !== expectedUserId) {
            forceLogout();
          }
        } catch (_) {
          forceLogout();
        }
      }

      checkLocalActiveUser();
      setInterval(checkSession, 3000);
      setInterval(checkLocalActiveUser, 1000);
      document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
          checkLocalActiveUser();
          checkSession();
        }
      });
    })();
  </script>
</body>
</html>
