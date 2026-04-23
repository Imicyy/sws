<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Office of Senior Citizen Affairs Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    * { box-sizing: border-box; }
    html, body { width: 100%; min-height: 100%; }
    .layout { display: flex; align-items: stretch; min-height: 100vh; width: 100%; }
    .sidebar { flex: 0 0 260px; width: 260px; background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; overflow-y: auto; position: sticky; top: 0; height: 100vh; align-self: flex-start; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; margin-top: 16px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 14px; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .top-actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .header-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 14px;
      border-radius: 10px;
      background: #0f766e;
      color: #fff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 700;
      box-shadow: 0 8px 18px rgba(15, 118, 110, 0.18);
    }
    .header-action:hover { background: #115e59; color: #fff; text-decoration: none; }
    .main { flex: 1 1 auto; min-width: 0; padding: 20px; }
    .top { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 16px; gap: 12px; position: relative; }
    .top h1 { position: absolute; left: 50%; transform: translateX(-50%); margin: 0; text-align: center; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-teal { background: linear-gradient(135deg, #0d9488, #2dd4bf); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #ecfdf5; color: #065f46; text-decoration: none; }
    .quick a:hover { background: #d1fae5; }
    .dept-badge { display: inline-block; padding: 4px 10px; background: #d1fae5; color: #065f46; border-radius: 6px; font-size: 12px; font-weight: 600; }
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
    .status-active { background: #d1fae5; color: #065f46; }
    .status-archived { background: #f3f4f6; color: #374151; }
    .action-container { display: flex; gap: 8px; align-items: center; }
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
    .action.view-btn { color: #0f766e; }
    .action.action-edit { color: #2563eb; }
    .action.action-archive { color: #dc2626; }
    .action-container .action:focus { outline: 2px solid rgba(15, 118, 110, 0.25); outline-offset: 2px; }
    .action-bar { margin-top: 16px; display: flex; gap: 12px; }
    .action-bar button { padding: 10px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
    .btn-sms { background: #059669; color: white; }
    .btn-sms:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
    .btn-history { background: #0f766e; color: white; }
    .detail-section { margin-bottom: 14px; }
    .detail-section h6 { font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #0f766e; text-transform: uppercase; }
    .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px 16px; }
    .detail-item { font-size: 13px; line-height: 1.45; }
    .detail-label { font-weight: 700; color: #374151; }
    .detail-value { color: #111827; }
    .edit-log-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .edit-log-table th, .edit-log-table td { border: 1px solid #e5e7eb; padding: 8px; vertical-align: top; }
    .edit-log-table th { background: #f3f4f6; color: #374151; }
    .edit-log-muted { color: #6b7280; font-size: 12px; }
    @media (max-width: 980px) {
      .layout { flex-direction: column; }
      .sidebar { flex: none; width: 100%; position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div style="font-size: 11px; color: #6b7280; margin-bottom: 14px;"><span class="dept-badge">OSCA Department</span></div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/osca-dashboard">Dashboard</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">Office of Senior Citizen Affairs Dashboard</h1>
        <div class="top-actions" style="display:flex;align-items:center;gap:12px;">
          <div style="position:relative;">
            <button id="notifBellSenior" class="header-action" style="background:transparent;border:0;cursor:pointer;padding:8px;border-radius:8px;">
              🔔 <span id="notifBadgeSenior" style="background:#dc2626;color:#fff;border-radius:10px;padding:2px 6px;font-size:12px;display:none;margin-left:6px;">0</span>
            </button>
            <div id="notifDropdownSenior" style="display:none;position:absolute;right:0;top:44px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px;max-height:320px;overflow:auto;padding:8px;z-index:2000;">
              <div style="font-weight:700;padding:8px;border-bottom:1px solid #f3f4f6;">Notifications</div>
              <div id="notifListSenior" style="padding:8px;font-size:13px;color:#374151;"></div>
            </div>
          </div>
          <button type="button" class="header-action" id="birthdaysBtnSenior" style="background:#fff;border:1px solid #d1d5db;border-radius:10px;padding:8px 12px;display:flex;align-items:center;gap:8px;">
            <span>🎂 Birthdays</span>
            <span id="birthdaysBadgeSenior" style="display:none;background:#0f766e;color:#fff;border-radius:999px;padding:2px 8px;font-size:12px;font-weight:700;">0</span>
          </button>
          <a class="header-action" href="/add_senior" id="addSeniorBtn" target="_blank">
            <i class="feather icon-user-plus"></i>
            <span>Add Senior</span>
          </a>
          <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'staff'), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      </div>

      <section class="panel">
        <h2 class="h6 mb-3">Senior Citizens</h2>
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
          <table id="seniorTable" class="table table-hover mb-0">
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
              <?php foreach (($seniors ?? []) as $senior):
                $fullName = trim((string) ($senior['first_name'] ?? '') . ' ' . (string) ($senior['middle_name'] ?? '') . ' ' . (string) ($senior['last_name'] ?? ''));
                $barangay = (string) ($senior['barangay'] ?? '');
                $purok = (string) ($senior['purok'] ?? '');
                $status = (string) ($senior['status'] ?? 'Active');
                $statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
              ?>
                <tr data-barangay="<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>" data-purok="<?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                  <td><input type="checkbox" class="rowCheckbox" value="<?= (int) ($senior['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars($fullName !== '' ? $fullName : 'Unnamed record', ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= isset($senior['age']) && $senior['age'] !== null ? (int) $senior['age'] : 'N/A' ?></td>
                  <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="action-container">
                      <button type="button" class="action view-btn" aria-label="View" data-senior='<?= htmlspecialchars(json_encode($senior, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <button type="button" class="action action-edit edit-btn" aria-label="Edit" data-senior-id="<?= (int) ($senior['id'] ?? 0) ?>" data-senior='<?= htmlspecialchars(json_encode($senior, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button type="button" class="action action-archive archive-btn" aria-label="Archive" data-senior-id="<?= (int) ($senior['id'] ?? 0) ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars(($status === 'Archived') ? 'Unarchive' : 'Archive', ENT_QUOTES, 'UTF-8') ?>">
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

        <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
          <small id="seniorPaginationInfo">Showing 0-0 of 0 records</small>
          <div id="seniorPaginationControls" class="btn-group btn-group-sm" style="display: flex; gap: 4px;"></div>
        </div>

        <div class="action-bar">
          <button class="btn-sms" id="sendSmsBtn" disabled>Send SMS to Selected</button>
          <button class="btn-history" id="viewHistoryBtn">View SMS History</button>
        </div>
      </section>
    </main>
  </div>

  <div class="modal fade" id="viewSeniorModal" tabindex="-1" role="dialog" aria-labelledby="viewSeniorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 900px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewSeniorModalTitle">View Senior Citizen Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 20px;">
          <div id="viewSeniorMeta" class="mb-3" style="display: flex; flex-wrap: wrap; gap: 12px; color: #374151; font-size: 13px;">
           
          </div>

          <div class="mb-3" style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; background: #ffffff;">
            <div style="font-size: 13px; font-weight: 700; color: #0f766e; margin-bottom: 8px;">Edit Logs</div>
            <div id="viewEditLogsContainer" class="edit-log-muted">No edit logs yet.</div>
          </div>

          <div id="viewSeniorForm">
            <input type="hidden" id="viewResidentId">

            <div style="display: flex; justify-content: space-between; gap: 12px; margin-bottom: 24px; padding: 0 10px; position: relative;">
              <div style="position: absolute; top: 18px; left: 34px; right: 34px; height: 2px; background: #d1d5db; z-index: 0;"></div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step active">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #0f766e; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">1</div>
                <span>Personal</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">2</div>
                <span>Contact</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">3</div>
                <span>Family</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">4</div>
                <span>IDs</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">5</div>
                <span>Education</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="view-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">6</div>
                <span>Community</span>
              </div>
            </div>

            <fieldset class="view-fieldset active" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Personal Information</legend>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>First Name</label>
                  <input type="text" class="form-control" id="viewFirstName" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Middle Name</label>
                  <input type="text" class="form-control" id="viewMiddleName" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Last Name</label>
                  <input type="text" class="form-control" id="viewLastName" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Extension</label>
                  <input type="text" class="form-control" id="viewExtension" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Birthday</label>
                  <input type="date" class="form-control" id="viewBirthday" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Age</label>
                  <input type="text" class="form-control" id="viewAgeField" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Barangay</label>
                  <input type="text" class="form-control" id="viewBarangayField" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Purok</label>
                  <input type="text" class="form-control" id="viewPurokField" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label>Gender</label>
                  <input type="text" class="form-control" id="viewGenderField" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Place of Birth</label>
                  <input type="text" class="form-control" id="viewPlaceOfBirth" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Marital Status</label>
                  <input type="text" class="form-control" id="viewCivilStatus" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Spouse Name</label>
                  <input type="text" class="form-control" id="viewSpouseName" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Other Government ID</label>
                  <input type="text" class="form-control" id="viewOtherGovtId" readonly>
                </div>
              </div>
            </fieldset>

            <fieldset class="view-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Contact Information</legend>
              <div id="viewContactsContainer"></div>
            </fieldset>

            <fieldset class="view-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Family Composition</legend>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>Father Last Name</label>
                  <input type="text" class="form-control" id="viewFatherLastName" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label>Father First Name</label>
                  <input type="text" class="form-control" id="viewFatherFirstName" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label>Father Middle Name</label>
                  <input type="text" class="form-control" id="viewFatherMiddleName" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label>Extension (Jr/Sr)</label>
                  <input type="text" class="form-control" id="viewFatherExtension" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>Mother Last Name</label>
                  <input type="text" class="form-control" id="viewMotherLastName" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label>Mother First Name</label>
                  <input type="text" class="form-control" id="viewMotherFirstName" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Mother Middle Name</label>
                  <input type="text" class="form-control" id="viewMotherMiddleName" readonly>
                </div>
              </div>
              <div class="form-group mb-0">
                <label>Children</label>
                <div id="viewChildrenContainer"></div>
              </div>
            </fieldset>

            <fieldset class="view-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Identification Documents</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>OSCA/Senior Citizen ID</label>
                  <input type="text" class="form-control" id="viewOscaId" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>GSIS</label>
                  <input type="text" class="form-control" id="viewGsisId" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>SSS</label>
                  <input type="text" class="form-control" id="viewSssId" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Philhealth</label>
                  <input type="text" class="form-control" id="viewPhilhealthId" readonly>
                </div>
              </div>
            </fieldset>

            <fieldset class="view-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Education & HR Profile</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Educational Attainment</label>
                  <input type="text" class="form-control" id="viewEducationalAttainment" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Service / Business / Employment</label>
                  <input type="text" class="form-control" id="viewServiceBusinessEmployment" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Current Pension</label>
                  <input type="text" class="form-control" id="viewCurrentPension" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label>Capability to Travel</label>
                  <input type="text" class="form-control" id="viewCapabilityToTravel" readonly>
                </div>
              </div>
              <div class="form-group">
                <label>Skills / Specialization</label>
                <textarea class="form-control" id="viewSkills" rows="3" readonly></textarea>
              </div>
              <div class="form-group">
                <label>Other Skill</label>
                <input type="text" class="form-control" id="viewSkillOtherText" readonly>
              </div>
            </fieldset>

            <fieldset class="view-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Community Service</legend>
              <div class="form-group">
                <label>Community Service Involvement</label>
                <textarea class="form-control" id="viewCommunityService" rows="4" readonly></textarea>
              </div>
              <div class="form-group mb-0">
                <label>Other Community Service</label>
                <input type="text" class="form-control" id="viewCommunityServiceOtherText" readonly>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="modal-footer" style="padding: 12px 20px; border-top: 1px solid #e5e7eb;">
          <button type="button" class="btn btn-secondary" id="viewPrevBtn" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: none;">Back</button>
          <button type="button" class="btn btn-primary" id="viewNextBtn" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; background: #0f766e; color: #ffffff; border: none;">Next</button>
          <button type="button" class="btn btn-outline-primary" id="printApplicationBtn" style="min-width: 160px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700;">Print Application</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editSeniorModal" tabindex="-1" role="dialog" aria-labelledby="editSeniorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 900px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSeniorModalTitle">Edit Senior Citizen Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 20px;">
          <form id="editSeniorForm" novalidate>
            <input type="hidden" id="editResidentId" name="residentId">
            
            <!-- Progress Bar -->
            <div style="display: flex; justify-content: space-between; gap: 12px; margin-bottom: 24px; padding: 0 10px; position: relative;">
              <div style="position: absolute; top: 18px; left: 34px; right: 34px; height: 2px; background: #d1d5db; z-index: 0;"></div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step active">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #0f766e; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">1</div>
                <span>Personal</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">2</div>
                <span>Contact</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">3</div>
                <span>Family</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">4</div>
                <span>IDs</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">5</div>
                <span>Education</span>
              </div>
              <div style="color: #6b7280; font-size: 12px; cursor: default; text-align: center; min-width: 110px; position: relative; z-index: 1; font-weight: 600;" class="edit-step">
                <div style="display: grid; place-items: center; width: 36px; height: 36px; margin: 0 auto 8px; border-radius: 50%; background-color: #94a3b8; color: #ffffff; font-size: 13px; font-weight: 700; border: none;">6</div>
                <span>Community</span>
              </div>
            </div>

            <!-- Personal Information -->
            <fieldset class="edit-fieldset active" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Personal Information</legend>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="editFirstName" style="color: #374151; font-weight: 600;">First Name</label>
                  <input type="text" class="form-control" id="editFirstName" name="first_name" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editMiddleName" style="color: #374151; font-weight: 600;">Middle Name</label>
                  <input type="text" class="form-control" id="editMiddleName" name="middle_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editLastName" style="color: #374151; font-weight: 600;">Last Name</label>
                  <input type="text" class="form-control" id="editLastName" name="last_name" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="editExtension" style="color: #374151; font-weight: 600;">Extension</label>
                  <input type="text" class="form-control" id="editExtension" name="extension" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editBirthday" style="color: #374151; font-weight: 600;">Birthday</label>
                  <input type="date" class="form-control" id="editBirthday" name="birthday" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editAge" style="color: #374151; font-weight: 600;">Age</label>
                  <input type="number" class="form-control" id="editAge" name="age" readonly style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="editBarangay" style="color: #374151; font-weight: 600;">Barangay</label>
                  <select class="form-control" id="editBarangay" name="barangay" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Barangay</option>
                    <?php foreach (($barangays ?? []) as $brgy => $puroks): ?>
                      <option value="<?= htmlspecialchars((string) $brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $brgy, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="editPurok" style="color: #374151; font-weight: 600;">Purok</label>
                  <select class="form-control" id="editPurok" name="purok" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Purok</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="editGender" style="color: #374151; font-weight: 600;">Gender</label>
                  <select class="form-control" id="editGender" name="gender" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                    <option value="Prefer not to say">Prefer not to say</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editPlaceOfBirth" style="color: #374151; font-weight: 600;">Place of Birth</label>
                  <input type="text" class="form-control" id="editPlaceOfBirth" name="place_of_birth" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-6">
                  <label for="editCivilStatus" style="color: #374151; font-weight: 600;">Marital Status</label>
                  <select class="form-control" id="editCivilStatus" name="marital_status" required style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Separated">Separated</option>
                  </select>
                </div>
              </div>
              <div class="form-row" id="editSpouseGroup" style="display: none;">
                <div class="form-group col-md-6">
                  <label for="editSpouseName" style="color: #374151; font-weight: 600;">Spouse Name</label>
                  <input type="text" class="form-control" id="editSpouseName" name="spouse_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="editServiceBusinessEmployment" style="color: #374151; font-weight: 600;">Service / Business / Employment</label>
                  <input type="text" class="form-control" id="editServiceBusinessEmploymentStep5" name="service_business_employment_step5" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editCurrentPension" style="color: #374151; font-weight: 600;">Current Pension</label>
                  <input type="text" class="form-control" id="editCurrentPensionStep5" name="current_pension_step5" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-4">
                  <label for="editCapabilityToTravel" style="color: #374151; font-weight: 600;">Capability to Travel</label>
                  <select class="form-control" id="editCapabilityToTravelStep5" name="capability_to_travel_step5" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editOtherGovtId" style="color: #374151; font-weight: 600;">Other Government ID</label>
                  <input type="text" class="form-control" id="editOtherGovtId" name="other_govt_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
            </fieldset>

            <!-- Contact Information -->
            <fieldset class="edit-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Contact Information</legend>
              <div id="editContactsContainer"></div>
              <button type="button" class="btn btn-sm btn-secondary" id="editAddContact" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 14px; margin-top: 10px;">+ Add Contact</button>
            </fieldset>

            <!-- Family Information -->
            <fieldset class="edit-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Family Composition</legend>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="editFatherLastName" style="color: #374151; font-weight: 600;">Father Last Name</label>
                  <input type="text" class="form-control" id="editFatherLastName" name="father_last_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-3">
                  <label for="editFatherFirstName" style="color: #374151; font-weight: 600;">Father First Name</label>
                  <input type="text" class="form-control" id="editFatherFirstName" name="father_first_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-3">
                  <label for="editFatherMiddleName" style="color: #374151; font-weight: 600;">Father Middle Name</label>
                  <input type="text" class="form-control" id="editFatherMiddleName" name="father_middle_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-3">
                  <label for="editFatherExtension" style="color: #374151; font-weight: 600;">Extension (Jr/Sr)</label>
                  <input type="text" class="form-control" id="editFatherExtension" name="father_extension" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="editMotherLastName" style="color: #374151; font-weight: 600;">Mother Last Name</label>
                  <input type="text" class="form-control" id="editMotherLastName" name="mother_last_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-3">
                  <label for="editMotherFirstName" style="color: #374151; font-weight: 600;">Mother First Name</label>
                  <input type="text" class="form-control" id="editMotherFirstName" name="mother_first_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-6">
                  <label for="editMotherMiddleName" style="color: #374151; font-weight: 600;">Mother Middle Name</label>
                  <input type="text" class="form-control" id="editMotherMiddleName" name="mother_middle_name" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
            </fieldset>

            <!-- Identification Documents -->
            <fieldset class="edit-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Identification Documents</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editOscaId" style="color: #374151; font-weight: 600;">OSCA/Senior Citizen ID</label>
                  <input type="text" class="form-control" id="editOscaId" name="osca_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-6">
                  <label for="editGsisId" style="color: #374151; font-weight: 600;">GSIS</label>
                  <input type="text" class="form-control" id="editGsisId" name="gsis_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editSssId" style="color: #374151; font-weight: 600;">SSS</label>
                  <input type="text" class="form-control" id="editSssId" name="sss_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-6">
                  <label for="editPhilhealthId" style="color: #374151; font-weight: 600;">Philhealth</label>
                  <input type="text" class="form-control" id="editPhilhealthId" name="philhealth_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="editOtherId" style="color: #374151; font-weight: 600;">Other Government ID</label>
                  <input type="text" class="form-control" id="editOtherId" name="other_id" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
            </fieldset>

            <fieldset class="edit-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Education & HR Profile</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editEducationalAttainment" style="color: #374151; font-weight: 600;">Educational Attainment</label>
                  <select class="form-control" id="editEducationalAttainment" name="educational_attainment" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Educational Level</option>
                    <option value="Not Attended School">Not Attended School</option>
                    <option value="Elementary Level">Elementary Level</option>
                    <option value="Elementary Graduate">Elementary Graduate</option>
                    <option value="High School Level">High School Level</option>
                    <option value="High School Graduate">High School Graduate</option>
                    <option value="College Level">College Level</option>
                    <option value="College Graduate">College Graduate</option>
                    <option value="Vocational">Vocational</option>
                    <option value="Post Graduate">Post Graduate</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="editServiceBusinessEmployment" style="color: #374151; font-weight: 600;">Service / Business / Employment</label>
                  <input type="text" class="form-control" id="editServiceBusinessEmployment" name="service_business_employment" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editCurrentPension" style="color: #374151; font-weight: 600;">Current Pension</label>
                  <input type="text" class="form-control" id="editCurrentPension" name="current_pension" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div class="form-group col-md-6">
                  <label for="editCapabilityToTravel" style="color: #374151; font-weight: 600;">Capability to Travel</label>
                  <select class="form-control" id="editCapabilityToTravel" name="capability_to_travel" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Select Option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label style="color: #374151; font-weight: 600; display: block; margin-bottom: 8px;">Skills / Specialization</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Medical"> Medical</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Dental"> Dental</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Fishing"> Fishing</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Teaching"> Teaching</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Counseling"> Counseling</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Cooking"> Cooking</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Carpenter"> Carpenter</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Farming"> Farming</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Legal Services"> Legal Services</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editSkills[]" value="Other"> Other</label>
                </div>
                <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                  <label for="editSkillOtherText" style="color: #374151; font-weight: 600;">Other Skill</label>
                  <input type="text" class="form-control" id="editSkillOtherText" name="skill_other_text" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
            </fieldset>

            <fieldset class="edit-fieldset" style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 18px; background: #ffffff; display: none;">
              <legend style="padding: 0 10px; color: #0f766e; font-size: 16px; font-weight: 700;">Community Service</legend>
              <div class="form-group">
                <label style="color: #374151; font-weight: 600; display: block; margin-bottom: 8px;">Community Service Involvement</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Medical"> Medical</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Community / Organization Leader"> Community / Organization Leader</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Neighborhood Support Services"> Neighborhood Support Services</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Counseling / Referral"> Counseling / Referral</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Resource Volunteer"> Resource Volunteer</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Dental"> Dental</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Legal Services"> Legal Services</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Sponsorship"> Sponsorship</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Community Beautification"> Community Beautification</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Friendly Visits"> Friendly Visits</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Religious"> Religious</label>
                  <label style="display: flex; gap: 8px; align-items: center; margin: 0;"><input type="checkbox" name="editCommunityService[]" value="Other"> Other</label>
                </div>
                <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                  <label for="editCommunityServiceOtherText" style="color: #374151; font-weight: 600;">Other Community Service</label>
                  <input type="text" class="form-control" id="editCommunityServiceOtherText" name="community_service_other_text" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
              </div>
            </fieldset>
          </form>
        </div>
        <div class="modal-footer" style="padding: 12px 20px; border-top: 1px solid #e5e7eb;">
          <button type="button" class="btn btn-secondary" id="editPrevBtn" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: none;">Back</button>
          <button type="button" class="btn btn-primary" id="editNextBtn" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; background: #0f766e; color: #ffffff; border: none;">Next</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="assistanceModal" tabindex="-1" role="dialog" aria-labelledby="assistanceModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assistanceModalTitle">Send SMS Assistance</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div id="recipientsList" style="max-height: 180px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px; margin-bottom: 10px;"></div>
          <div class="form-group mb-2">
            <label for="smsMessage">Message</label>
            <textarea id="smsMessage" class="form-control" rows="4"></textarea>
          </div>
          <div class="text-muted" style="font-size: 12px;">Characters: <span id="charCount">0</span></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="sendSmsModalBtn">Send SMS</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="smsHistoryModal" tabindex="-1" role="dialog" aria-labelledby="smsHistoryTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smsHistoryTitle">SMS History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <div class="table-responsive">
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const barangayFilter = document.getElementById('barangayFilter');
    const purokFilter = document.getElementById('purokFilter');

    function getVisibleRows() {
      return Array.from(document.querySelectorAll('#seniorTable tbody tr')).filter(row => row.style.display !== 'none');
    }

    function filterTable() {
      const statusFilter = document.getElementById('statusFilter').value;
      const selectedBarangay = barangayFilter.value;
      const selectedPurok = purokFilter.value;
      let visibleCount = 0;

      document.querySelectorAll('#seniorTable tbody tr').forEach(row => {
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

      const entryCount = document.getElementById('entryCount');
      if (entryCount) {
        entryCount.textContent = visibleCount;
      }
      updateActions();
      updateSeniorPagination();
    }

    function updateActions() {
      document.getElementById('sendSmsBtn').disabled = document.querySelectorAll('.rowCheckbox:checked').length === 0;
    }

    // Pagination variables
    let seniorCurrentPage = 1;
    const seniorItemsPerPage = 10;

    function getSeniorVisibleRows() {
      return Array.from(document.querySelectorAll('#seniorTable tbody tr')).filter(row => row.style.display !== 'none');
    }

    function updateSeniorPagination() {
      const visibleRows = getSeniorVisibleRows();
      const totalItems = visibleRows.length;
      const totalPages = Math.ceil(totalItems / seniorItemsPerPage);
      
      // Reset to first page if current page is out of bounds
      if (seniorCurrentPage > totalPages && totalPages > 0) {
        seniorCurrentPage = totalPages;
      } else if (totalPages === 0) {
        seniorCurrentPage = 1;
      }
      
      // Update pagination info
      const startItem = totalItems === 0 ? 0 : (seniorCurrentPage - 1) * seniorItemsPerPage + 1;
      const endItem = Math.min(seniorCurrentPage * seniorItemsPerPage, totalItems);
      document.getElementById('seniorPaginationInfo').textContent = `Showing ${startItem}-${endItem} of ${totalItems} records`;
      
      // Show/hide rows based on current page
      visibleRows.forEach((row, index) => {
        const rowPage = Math.floor(index / seniorItemsPerPage) + 1;
        row.style.display = rowPage === seniorCurrentPage ? '' : 'none';
      });
      
      // Update pagination controls
      renderSeniorPaginationControls(totalPages);
    }

    function renderSeniorPaginationControls(totalPages) {
      const controls = document.getElementById('seniorPaginationControls');
      controls.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      // Previous button
      const prevBtn = document.createElement('button');
      prevBtn.className = 'btn btn-sm btn-outline-secondary';
      prevBtn.textContent = 'Previous';
      prevBtn.disabled = seniorCurrentPage === 1;
      prevBtn.onclick = () => {
        if (seniorCurrentPage > 1) {
          seniorCurrentPage--;
          updateSeniorPagination();
        }
      };
      controls.appendChild(prevBtn);
      
      // Page numbers
      const maxVisiblePages = 5;
      let startPage = Math.max(1, seniorCurrentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
      
      if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `btn btn-sm ${i === seniorCurrentPage ? 'btn-primary' : 'btn-outline-secondary'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => {
          seniorCurrentPage = i;
          updateSeniorPagination();
        };
        controls.appendChild(pageBtn);
      }
      
      // Next button
      const nextBtn = document.createElement('button');
      nextBtn.className = 'btn btn-sm btn-outline-secondary';
      nextBtn.textContent = 'Next';
      nextBtn.disabled = seniorCurrentPage === totalPages;
      nextBtn.onclick = () => {
        if (seniorCurrentPage < totalPages) {
          seniorCurrentPage++;
          updateSeniorPagination();
        }
      };
      controls.appendChild(nextBtn);
    }

    let currentViewSeniorId = '';
    let currentViewSeniorData = null;

    function getSeniorFromRow(row) {
      const viewBtn = row ? row.querySelector('.view-btn') : null;
      if (!viewBtn) return {};
      try {
        return JSON.parse(viewBtn.dataset.senior || '{}');
      } catch (error) {
        return {};
      }
    }

    function getSeniorPhone(senior) {
      const contacts = senior && senior.identifying_information && Array.isArray(senior.identifying_information.contacts)
        ? senior.identifying_information.contacts
        : (Array.isArray(senior.contacts) ? senior.contacts : []);
      const primary = contacts.find(function (c) { return c && c.phone; }) || {};
      return String(primary.phone || senior.contact || '').trim();
    }

    function setPdfText(form, field, value) {
      try {
        form.getTextField(field).setText(String(value || ''));
      } catch (error) {}
    }

    function setPdfCheck(form, field, checked) {
      try {
        const cb = form.getCheckBox(field);
        if (checked) cb.check();
        else cb.uncheck();
      } catch (error) {}
    }

    function formatMmDdYyyy(value) {
      if (!value) return '';
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) return '';
      const mm = String(date.getMonth() + 1).padStart(2, '0');
      const dd = String(date.getDate()).padStart(2, '0');
      const yyyy = String(date.getFullYear());
      return mm + '/' + dd + '/' + yyyy;
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

    async function buildSeniorApplicationPdf(senior) {
      if (!senior) throw new Error('Missing Senior record');
      await ensurePdfLibLoaded();
      const info = senior.identifying_information || {};
      const name = info.name || {};
      const address = info.address || {};
      const family = senior.family_composition || {};
      const education = senior.education_hr_profile || {};
      const contacts = Array.isArray(info.contacts) ? info.contacts : [];
      const primary = contacts.find(function (c) { return c && c.phone; }) || null;

      const templateBytes = await fetchTemplateBytes([
        '/pdf-template/senior',
        '/default/pdf/SENIOR-FORMFIELD.pdf',
        'default/pdf/SENIOR-FORMFIELD.pdf',
        '../default/pdf/SENIOR-FORMFIELD.pdf',
        '../../default/pdf/SENIOR-FORMFIELD.pdf'
      ]);
      const pdfDoc = await PDFLib.PDFDocument.load(templateBytes);
      const form = pdfDoc.getForm();

      setPdfText(form, 'LAST NAME', name.last_name || '');
      setPdfText(form, 'FIRST NAME', name.first_name || '');
      setPdfText(form, 'MIDDLE NAME', name.middle_name || '');
      setPdfText(form, 'BARANGAY', address.barangay || '');
      setPdfText(form, 'PUROK', address.purok || '');
      setPdfText(form, 'PLACE OF BIRTH', Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : (info.place_of_birth || ''));
      setPdfText(form, 'MARITAL STATUS', info.marital_status || '');
      setPdfText(form, 'GENDER', info.gender || '');
      setPdfText(form, 'CONTACT', primary && primary.phone ? primary.phone : '');
      setPdfText(form, 'EMAIL', primary && primary.email ? primary.email : '');
      setPdfText(form, 'OSCA ID', info.osca_id_number || '');
      setPdfText(form, 'GSIS/SSS', info.gsis_sss || '');
      setPdfText(form, 'TIN', info.tin || '');
      setPdfText(form, 'PHILHEALTH', info.philhealth || '');
      setPdfText(form, 'OTHER ID', info.other_govt_id || '');
      setPdfText(form, 'SERVICE BUSINESS EMPLOYMENT', info.service_business_employment || '');
      setPdfText(form, 'CURRENT PENSION', info.current_pension || '');
      setPdfText(form, 'NAME OF SPOUSE', family.spouse && family.spouse.name ? family.spouse.name : '');

      const father = family.father || {};
      const mother = family.mother || {};
      setPdfText(form, 'FATHER FIRST NAME', father.first_name || '');
      setPdfText(form, 'FATHER LAST NAME', father.last_name || '');
      setPdfText(form, 'FATHER MIDDLE NAME', father.middle_name || '');
      setPdfText(form, 'FATHER EXTENSION', father.extension || '');
      setPdfText(form, 'MOTHER FIRST NAME', mother.first_name || '');
      setPdfText(form, 'MOTHER LAST NAME', mother.last_name || '');
      setPdfText(form, 'MOTHER MIDDLE NAME', mother.middle_name || '');

      const dob = formatMmDdYyyy(info.date_of_birth || '');
      setPdfText(form, 'BIRTHDATE', dob);
      setPdfText(form, 'Text Field129', dob);
      setPdfText(form, 'Text Field128', dob);
      setPdfText(form, 'Text Field127', dob);

      setPdfCheck(form, 'TRAVEL YES', (info.capability_to_travel || '') === 'Yes');
      setPdfCheck(form, 'TRAVEL NO', (info.capability_to_travel || '') === 'No');

      const levels = Array.isArray(education.educational_attainment) ? education.educational_attainment : [];
      setPdfCheck(form, 'ELEMENTARY LEVEL', levels.some(function (e) { return String(e).includes('Elementary Level'); }));
      setPdfCheck(form, 'ELEMENTARY GRADUATE', levels.some(function (e) { return String(e).includes('Elementary Graduate'); }));
      setPdfCheck(form, 'HIGHSCHOOL LEVEL', levels.some(function (e) { return String(e).includes('High School Level'); }));
      setPdfCheck(form, 'HIGHSCHOOL GRADUATE', levels.some(function (e) { return String(e).includes('High School Graduate'); }));
      setPdfCheck(form, 'COLLEGE LEVEL', levels.some(function (e) { return String(e).includes('College Level'); }));
      setPdfCheck(form, 'COLLEGE GRADUATE', levels.some(function (e) { return String(e).includes('College Graduate'); }));
      setPdfCheck(form, 'POST GRADUATE', levels.some(function (e) { return String(e).includes('Post Graduate'); }));
      setPdfCheck(form, 'VOCATIONAL', levels.some(function (e) { return String(e).includes('Vocational'); }));
      setPdfCheck(form, 'NOT ATTENDED SCHOOL', levels.some(function (e) { return String(e).includes('Not Attended'); }));

      try { form.flatten(); } catch (error) {}
      return pdfDoc.save();
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
      const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked'));
      const recipientsList = document.getElementById('recipientsList');
      recipientsList.innerHTML = '';

      selected.forEach(function (checkbox) {
        const row = checkbox.closest('tr');
        const senior = getSeniorFromRow(row);
        const phone = getSeniorPhone(senior);
        if (!phone) return;
        const fullName = [senior.first_name, senior.middle_name, senior.last_name, senior.extension].filter(Boolean).join(' ').trim() || row.children[1].textContent.trim();
        const item = document.createElement('div');
        item.className = 'recipient-item';
        item.dataset.phone = phone;
        item.dataset.name = fullName;
        item.dataset.firstName = senior.first_name || '';
        item.dataset.middleName = senior.middle_name || '';
        item.dataset.lastName = senior.last_name || '';
        item.dataset.barangay = senior.barangay || '';
        item.dataset.purok = senior.purok || '';
        item.dataset.recordId = String(senior.id || checkbox.value || '');
        item.innerHTML = '<strong>' + fullName + '</strong> <span class="text-muted">' + phone + '</span>';
        recipientsList.appendChild(item);
      });

      if (!recipientsList.children.length) {
        alert('No selected records have a mobile number.');
        return;
      }

      $('#assistanceModal').modal('show');
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
          recipient_type: 'Senior'
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

      const label = btn.textContent;
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
        $('#assistanceModal').modal('hide');
      } catch (error) {
        alert('The SMS service is down at the moment, sorry. Please try again later.');
      } finally {
        btn.disabled = false;
        btn.textContent = label;
      }
    });

    document.getElementById('viewHistoryBtn').addEventListener('click', async function () {
      $('#smsHistoryModal').modal('show');
      const tableBody = document.getElementById('smsHistoryTableBody');
      tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Loading...</td></tr>';
      try {
        const response = await fetch('/sms-history?recipient_type=Senior&limit=200');
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
            + '<td>' + sentAt + '</td>'
            + '<td>' + (record.phone_number || 'N/A') + '</td>'
            + '<td>' + fullName + '</td>'
            + '<td>' + (record.barangay || 'N/A') + '</td>'
            + '<td>' + (record.purok || 'N/A') + '</td>'
            + '<td>' + (record.message || 'N/A') + '</td>'
            + '<td>' + (record.status || 'N/A') + '</td>'
            + '<td class="text-center"><input type="checkbox" class="sms-received-checkbox" data-sms-id="' + (record._id || '') + '" ' + checked + '></td>'
            + '</tr>';
        }).join('');
        document.querySelectorAll('.sms-received-checkbox').forEach(function (checkbox) {
          checkbox.addEventListener('change', async function () {
            const desired = checkbox.checked;
            try {
              const response = await fetch('/update-sms-received', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ smsId: checkbox.dataset.smsId, received: desired })
              });
              if (!response.ok) {
                checkbox.checked = !desired;
              }
            } catch (error) {
              checkbox.checked = !desired;
            }
          });
        });
      } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading SMS history</td></tr>';
      }
    });

    // Multi-step form functions
    let editCurrentStep = 0;
    let editCurrentSenior = null;

    function editShowStep(n) {
      const fieldsets = document.querySelectorAll('.edit-fieldset');
      const steps = document.querySelectorAll('.edit-step');
      
      if (n < 0 || n >= fieldsets.length) return;
      
      fieldsets.forEach(fieldset => fieldset.style.display = 'none');
      fieldsets[n].style.display = 'block';
      
      steps.forEach((step, index) => {
        const indicator = step.querySelector('div');
        if (index <= n) {
          indicator.style.backgroundColor = '#0f766e';
          indicator.style.color = '#ffffff';
          step.style.color = '#0f766e';
        } else {
          indicator.style.backgroundColor = '#94a3b8';
          indicator.style.color = '#ffffff';
          step.style.color = '#6b7280';
        }
      });

      const prevBtn = document.getElementById('editPrevBtn');
      const nextBtn = document.getElementById('editNextBtn');
      if (!prevBtn || !nextBtn) return;
      nextBtn.disabled = false;
      
      if (n === 0) {
        prevBtn.style.display = 'none';
      } else {
        prevBtn.style.display = 'inline-block';
      }

      nextBtn.textContent = (n === fieldsets.length - 1) ? 'Update' : 'Next';
    }

    function editMoveStep(n) {
      editCurrentStep += n;
      editShowStep(editCurrentStep);
    }

    function editCalculateAge() {
      const birthdayInput = document.getElementById('editBirthday');
      const ageInput = document.getElementById('editAge');
      if (!birthdayInput || !ageInput) return;
      
      const birthday = new Date(birthdayInput.value);
      if (isNaN(birthday)) {
        ageInput.value = '';
        return;
      }

      const today = new Date();
      let age = today.getFullYear() - birthday.getFullYear();
      const monthDiff = today.getMonth() - birthday.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
        age--;
      }
      ageInput.value = age;
    }

    function editToggleSpouseInput() {
      const maritalStatus = document.getElementById('editCivilStatus').value;
      const spouseGroup = document.getElementById('editSpouseGroup');
      if (spouseGroup) {
        spouseGroup.style.display = (maritalStatus === 'Married') ? 'flex' : 'none';
      }
    }

    function getCheckedValues(name) {
      return Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(function (input) {
        return input.value;
      });
    }

    function setCheckedValues(name, values) {
      const normalizedValues = Array.isArray(values) ? values : (values ? [values] : []);
      document.querySelectorAll(`input[name="${name}"]`).forEach(function (input) {
        input.checked = normalizedValues.includes(input.value);
      });
    }

    function editAddContactForm(contact) {
      const container = document.getElementById('editContactsContainer');
      if (!container) return;
      
      const wrapper = document.createElement('div');
      wrapper.className = 'border rounded p-3 mb-3 edit-contact-row';
      wrapper.innerHTML = `
        <div class="form-row">
          <div class="form-group col-md-3">
            <label style="color: #374151; font-weight: 600;">Type</label>
            <input type="text" class="form-control edit-contact-type" value="${escapeHtml(contact?.type || 'primary')}" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
          </div>
          <div class="form-group col-md-3">
            <label style="color: #374151; font-weight: 600;">Name</label>
            <input type="text" class="form-control edit-contact-name" value="${escapeHtml(contact?.name || '')}" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
          </div>
          <div class="form-group col-md-3">
            <label style="color: #374151; font-weight: 600;">Relationship</label>
            <input type="text" class="form-control edit-contact-relationship" value="${escapeHtml(contact?.relationship || '')}" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
          </div>
          <div class="form-group col-md-3">
            <label style="color: #374151; font-weight: 600;">Phone</label>
            <input type="text" class="form-control edit-contact-phone" value="${escapeHtml(contact?.phone || '')}" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
          </div>
        </div>
        <div class="form-row align-items-end">
          <div class="form-group col-md-9 mb-0">
            <label style="color: #374151; font-weight: 600;">Email</label>
            <input type="email" class="form-control edit-contact-email" value="${escapeHtml(contact?.email || '')}" style="background: #ffffff; color: #111827; border: 1px solid #d1d5db; border-radius: 8px;">
          </div>
          <div class="form-group col-md-3 mb-0 text-right"></div>
        </div>`;
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'btn btn-sm btn-outline-danger';
      removeButton.textContent = 'Remove';
      removeButton.addEventListener('click', function () {
        wrapper.remove();
      });
      wrapper.querySelector('.text-right').appendChild(removeButton);
      container.appendChild(wrapper);
    }

    function escapeHtml(value) {
      return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function getInputValue(id) {
      const element = document.getElementById(id);
      return element ? String(element.value || '').trim() : '';
    }

    function getPreferredValue(primaryId, fallbackId) {
      const primary = getInputValue(primaryId);
      if (primary !== '') {
        return primary;
      }
      return getInputValue(fallbackId);
    }

    function updateEditPurokOptions(selectedBarangay, selectedPurok) {
      const purokSelect = document.getElementById('editPurok');
      if (!purokSelect) return;

      purokSelect.innerHTML = '<option value="">Select Purok</option>';

      const normalizedBarangay = String(selectedBarangay || '').replace(/\s+/g, ' ').trim().toLowerCase();
      for (const barangay in barangays) {
        if (barangay.replace(/\s+/g, ' ').trim().toLowerCase() === normalizedBarangay) {
          barangays[barangay].forEach(function (purok) {
            const option = document.createElement('option');
            option.value = purok;
            option.textContent = purok;
            if (selectedPurok && selectedPurok === purok) {
              option.selected = true;
            }
            purokSelect.appendChild(option);
          });
          break;
        }
      }
    }

    let viewCurrentStep = 0;

    function viewSetText(id, value, fallback = 'N/A') {
      const element = document.getElementById(id);
      if (element) {
        element.textContent = value ? String(value) : fallback;
      }
    }

    function viewSetInput(id, value) {
      const element = document.getElementById(id);
      if (element) {
        element.value = value || '';
      }
    }

    function viewSetTextarea(id, value) {
      const element = document.getElementById(id);
      if (element) {
        element.value = value || '';
      }
    }

    function viewParseList(value) {
      if (Array.isArray(value)) {
        return value.filter(Boolean).map(function (item) {
          return typeof item === 'string' ? item.trim() : String(item);
        }).filter(Boolean);
      }

      if (typeof value === 'string') {
        return value.split(',').map(function (item) {
          return item.trim();
        }).filter(Boolean);
      }

      return [];
    }

    function viewRenderContacts(contacts) {
      const container = document.getElementById('viewContactsContainer');
      if (!container) return;

      if (!contacts.length) {
        container.innerHTML = '<div class="text-muted">No contact information recorded.</div>';
        return;
      }

      container.innerHTML = contacts.map(function (contact, index) {
        return `
          <div class="border rounded p-3 mb-3" style="background: #f9fafb; border-color: #e5e7eb !important;">
            <div class="font-weight-bold mb-2" style="color: #0f766e;">Contact ${index + 1}</div>
            <div class="form-row">
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Type</label><div>${escapeHtml(contact?.type || '') || 'N/A'}</div></div>
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Name</label><div>${escapeHtml(contact?.name || '') || 'N/A'}</div></div>
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Relationship</label><div>${escapeHtml(contact?.relationship || '') || 'N/A'}</div></div>
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Phone</label><div>${escapeHtml(contact?.phone || '') || 'N/A'}</div></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12 mb-0"><label class="mb-1">Email</label><div>${escapeHtml(contact?.email || '') || 'N/A'}</div></div>
            </div>
          </div>`;
      }).join('');
    }

    function viewRenderChildren(children) {
      const container = document.getElementById('viewChildrenContainer');
      if (!container) return;

      if (!children.length) {
        container.innerHTML = '<div class="text-muted">No children recorded.</div>';
        return;
      }

      container.innerHTML = children.map(function (child, index) {
        return `
          <div class="border rounded p-3 mb-3" style="background: #f9fafb; border-color: #e5e7eb !important;">
            <div class="font-weight-bold mb-2" style="color: #0f766e;">Child ${index + 1}</div>
            <div class="form-row">
              <div class="form-group col-md-4 mb-2"><label class="mb-1">Full Name</label><div>${escapeHtml(child?.full_name || '') || 'N/A'}</div></div>
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Occupation</label><div>${escapeHtml(child?.occupation || '') || 'N/A'}</div></div>
              <div class="form-group col-md-2 mb-2"><label class="mb-1">Income</label><div>${escapeHtml(child?.income || '') || 'N/A'}</div></div>
              <div class="form-group col-md-3 mb-2"><label class="mb-1">Age</label><div>${escapeHtml(child?.age || '') || 'N/A'}</div></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12 mb-0"><label class="mb-1">Working Status</label><div>${escapeHtml(child?.working_status || '') || 'N/A'}</div></div>
            </div>
          </div>`;
      }).join('');
    }

    function viewFormatDateTime(value) {
      if (!value) return 'N/A';
      const raw = String(value).trim();
      const iso = raw.includes('T') ? raw : raw.replace(' ', 'T');
      const hasTz = /([zZ]|[+\-]\d\d:\d\d)$/.test(iso);
      // DB DATETIME/TIMESTAMP without TZ: treat as UTC to avoid browser-local misparse.
      const date = new Date(hasTz ? iso : (iso + 'Z'));
      if (isNaN(date.getTime())) return escapeHtml(String(value));
      return date.toLocaleString();
    }

    function viewRenderEditLogs(logs) {
      const container = document.getElementById('viewEditLogsContainer');
      if (!container) return;

      if (!Array.isArray(logs) || logs.length === 0) {
        container.innerHTML = '<div class="edit-log-muted">No edit logs yet.</div>';
        return;
      }

      const rows = logs.map(function (log) {
        return `
          <tr>
            <td>${escapeHtml(log.field || '') || 'N/A'}</td>
            <td>${escapeHtml(log.old_value || '') || 'N/A'}</td>
            <td>${escapeHtml(log.new_value || '') || 'N/A'}</td>
            <td>${escapeHtml(log.edited_by || '') || 'N/A'}</td>
            <td>${viewFormatDateTime(log.edited_at)}</td>
          </tr>`;
      }).join('');

      container.innerHTML = `
        <div style="overflow-x: auto;">
          <table class="edit-log-table">
            <thead>
              <tr>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Edited By</th>
                <th>Date / Time</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>`;
    }

    function viewRenderSubmittedMetaFromRecord(senior) {
      const meta = document.getElementById('viewSeniorMeta');
      if (!meta) return;

      const submittedBy = (senior && (senior.created_by || senior.createdBy)) ? String(senior.created_by || senior.createdBy) : 'Unknown';
      const submittedAtRaw = (senior && (senior.created_at || senior.createdAt)) ? String(senior.created_at || senior.createdAt) : '';
      const submittedAt = submittedAtRaw ? viewFormatDateTime(submittedAtRaw) : 'N/A';

      meta.innerHTML =
        '<div style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:999px;background:#f9fafb;"><strong style="color:#0f766e;">Submitted by:</strong> ' + escapeHtml(submittedBy) + '</div>' +
        '<div style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:999px;background:#f9fafb;"><strong style="color:#0f766e;">Submitted at:</strong> ' + escapeHtml(submittedAt) + '</div>';
    }

    function loadSeniorEditLogs(residentId) {
      const container = document.getElementById('viewEditLogsContainer');
      if (!container) return;

      if (!residentId) {
        container.innerHTML = '<div class="edit-log-muted">Unable to load logs because record ID is missing.</div>';
        return;
      }

      container.innerHTML = '<div class="edit-log-muted">Loading edit logs...</div>';

      fetch('/api/senior-edit-logs/' + encodeURIComponent(residentId))
        .then(function (response) {
          return response.json().then(function (data) {
            if (!response.ok || !data.success) {
              throw new Error(data.message || 'Failed to load edit logs.');
            }
            return data;
          });
        })
        .then(function (data) {
          const logs = data.data || [];
          viewRenderEditLogs(logs);
        })
        .catch(function (error) {
          container.innerHTML = '<div class="edit-log-muted">' + escapeHtml(error.message || 'Failed to load edit logs.') + '</div>';
        });
    }

    function viewShowStep(step) {
      const fieldsets = document.querySelectorAll('#viewSeniorForm .view-fieldset');
      const steps = document.querySelectorAll('#viewSeniorModal .view-step');
      if (!fieldsets.length) return;

      viewCurrentStep = Math.max(0, Math.min(step, fieldsets.length - 1));

      fieldsets.forEach(function (fieldset, index) {
        fieldset.style.display = index === viewCurrentStep ? 'block' : 'none';
      });

      steps.forEach(function (stepElement, index) {
        const bubble = stepElement.querySelector('div');
        if (!bubble) return;
        if (index === viewCurrentStep) {
          bubble.style.backgroundColor = '#0f766e';
          stepElement.style.color = '#0f766e';
        } else {
          bubble.style.backgroundColor = '#94a3b8';
          stepElement.style.color = '#6b7280';
        }
      });

      const prevBtn = document.getElementById('viewPrevBtn');
      const nextBtn = document.getElementById('viewNextBtn');
      if (prevBtn) {
        prevBtn.style.display = viewCurrentStep === 0 ? 'none' : 'inline-block';
      }
      if (nextBtn) {
        nextBtn.textContent = viewCurrentStep === fieldsets.length - 1 ? 'Close' : 'Next';
      }
    }

    function viewMoveStep(direction) {
      const fieldsets = document.querySelectorAll('#viewSeniorForm .view-fieldset');
      if (!fieldsets.length) return;

      if (direction === 1 && viewCurrentStep === fieldsets.length - 1) {
        $('#viewSeniorModal').modal('hide');
        return;
      }

      const nextStep = viewCurrentStep + direction;
      if (nextStep >= 0 && nextStep < fieldsets.length) {
        viewShowStep(nextStep);
      }
    }

    function openSeniorEditModal(senior, rowResidentId) {
      editCurrentStep = 0;
      editCurrentSenior = senior || null;
      
      const info = senior?.identifying_information || {};
      const name = info.name || {};
      const address = info.address || {};
      const family = senior?.family_composition || {};
      const contacts = Array.isArray(info.contacts) ? info.contacts : [];

      const normalizedRowId = Number.isFinite(Number(rowResidentId)) && Number(rowResidentId) > 0
        ? String(parseInt(rowResidentId, 10))
        : '';
      document.getElementById('editResidentId').value = normalizedRowId || getSeniorRecordId(senior);
      document.getElementById('editFirstName').value = name.first_name || '';
      document.getElementById('editMiddleName').value = name.middle_name || '';
      document.getElementById('editLastName').value = name.last_name || '';
      document.getElementById('editExtension').value = name.extension || '';
      document.getElementById('editBirthday').value = info.date_of_birth || '';
      document.getElementById('editAge').value = info.age ?? '';
      document.getElementById('editBarangay').value = address.barangay || '';
      updateEditPurokOptions(address.barangay || '', address.purok || '');
      document.getElementById('editGender').value = info.gender || '';
      document.getElementById('editCivilStatus').value = info.marital_status || '';
      document.getElementById('editPlaceOfBirth').value = Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : (info.place_of_birth || '');
      document.getElementById('editSpouseName').value = family?.spouse?.name || '';
      document.getElementById('editServiceBusinessEmployment').value = info.service_business_employment || '';
      document.getElementById('editCurrentPension').value = info.current_pension || '';
      document.getElementById('editCapabilityToTravel').value = info.capability_to_travel || '';
      document.getElementById('editOtherGovtId').value = info.other_govt_id || '';
      document.getElementById('editServiceBusinessEmploymentStep5').value = info.service_business_employment || '';
      document.getElementById('editCurrentPensionStep5').value = info.current_pension || '';
      document.getElementById('editCapabilityToTravelStep5').value = info.capability_to_travel || '';

      // Contacts
      const contactsContainer = document.getElementById('editContactsContainer');
      if (contactsContainer) {
        contactsContainer.innerHTML = '';
        if (contacts.length) {
          contacts.forEach(function (contact) {
            editAddContactForm(contact);
          });
        } else {
          editAddContactForm({});
        }
      }

      // Family
      document.getElementById('editFatherLastName').value = family?.father?.last_name || '';
      document.getElementById('editFatherFirstName').value = family?.father?.first_name || '';
      document.getElementById('editFatherMiddleName').value = family?.father?.middle_name || '';
      document.getElementById('editFatherExtension').value = family?.father?.extension || '';
      document.getElementById('editMotherLastName').value = family?.mother?.last_name || '';
      document.getElementById('editMotherFirstName').value = family?.mother?.first_name || '';
      document.getElementById('editMotherMiddleName').value = family?.mother?.middle_name || '';

      // IDs
      const gsisSssRaw = String(info.gsis_sss || '').trim();
      const gsisSssParts = gsisSssRaw.split('/').map(function (part) {
        return part.trim();
      }).filter(Boolean);
      document.getElementById('editOscaId').value = info.osca_id_number || '';
      document.getElementById('editGsisId').value = gsisSssParts[0] || gsisSssRaw || '';
      document.getElementById('editSssId').value = gsisSssParts[1] || '';
      document.getElementById('editPhilhealthId').value = info.philhealth || '';
      document.getElementById('editOtherId').value = info.other_govt_id || '';

      const education = senior?.education_hr_profile || {};
      const educationalAttainment = Array.isArray(education.educational_attainment)
        ? (education.educational_attainment[0] || '')
        : (education.educational_attainment || '');
      document.getElementById('editEducationalAttainment').value = educationalAttainment;
      document.getElementById('editServiceBusinessEmployment').value = info.service_business_employment || '';
      document.getElementById('editCurrentPension').value = info.current_pension || '';
      document.getElementById('editCapabilityToTravel').value = info.capability_to_travel || '';
      document.getElementById('editServiceBusinessEmploymentStep5').value = info.service_business_employment || '';
      document.getElementById('editCurrentPensionStep5').value = info.current_pension || '';
      document.getElementById('editCapabilityToTravelStep5').value = info.capability_to_travel || '';
      setCheckedValues('editSkills[]', education.skills || []);
      document.getElementById('editSkillOtherText').value = education.skill_other_text || '';

      setCheckedValues('editCommunityService[]', senior?.community_service || []);
      document.getElementById('editCommunityServiceOtherText').value = senior?.community_service_other_text || '';

      editToggleSpouseInput();
      editShowStep(0);
      const nextBtn = document.getElementById('editNextBtn');
      if (nextBtn) {
        nextBtn.disabled = false;
        nextBtn.textContent = 'Next';
      }
      $('#editSeniorModal').modal('show');
    }

    // Event listeners for form interactions
    document.getElementById('editBirthday').addEventListener('change', editCalculateAge);
    document.getElementById('editCivilStatus').addEventListener('change', editToggleSpouseInput);
    document.getElementById('editBarangay').addEventListener('change', function() {
      updateEditPurokOptions(this.value, '');
    });

    document.getElementById('editAddContact').addEventListener('click', function() {
      editAddContactForm({});
    });

    document.getElementById('editPrevBtn').addEventListener('click', function() {
      editMoveStep(-1);
    });

    document.getElementById('viewPrevBtn').addEventListener('click', function() {
      viewMoveStep(-1);
    });

    document.getElementById('viewNextBtn').addEventListener('click', function() {
      viewMoveStep(1);
    });

    // Helper functions for view/edit actions
    function parseSeniorPayload(button) {
      try {
        const payload = JSON.parse(button.getAttribute('data-senior') || '{}');
        const attrId = parseInt(button.getAttribute('data-senior-id') || '0', 10);
        if ((!payload.id || Number(payload.id) <= 0) && Number.isFinite(attrId) && attrId > 0) {
          payload.id = attrId;
        }
        return payload;
      } catch (error) {
        const attrId = parseInt(button.getAttribute('data-senior-id') || '0', 10);
        if (Number.isFinite(attrId) && attrId > 0) {
          return { id: attrId };
        }
        return null;
      }
    }

    function getSeniorRecordId(senior) {
      // Prefer SQL numeric id used by the PHP update endpoint.
      const directId = Number(senior?.id);
      if (Number.isFinite(directId) && directId > 0) {
        return String(directId);
      }

      const fallbackId = senior?._id;
      if (typeof fallbackId === 'string' && fallbackId.trim() !== '') {
        return fallbackId.trim();
      }

      if (typeof fallbackId === 'number' && Number.isFinite(fallbackId)) {
        return String(fallbackId);
      }

      return '';
    }

    function getSeniorFullName(senior) {
      const name = senior?.identifying_information?.name || {};
      return [name.first_name, name.middle_name, name.last_name, name.extension]
        .filter(Boolean)
        .join(' ')
        .replace(/\s+/g, ' ')
        .trim() || 'Unnamed record';
    }

    function formatValue(value) {
      if (value === null || value === undefined || value === '') {
        return 'N/A';
      }

      if (Array.isArray(value)) {
        return value.length ? value.map(item => escapeHtml(item)).join(', ') : 'N/A';
      }

      if (typeof value === 'object') {
        return escapeHtml(JSON.stringify(value));
      }

      return escapeHtml(value);
    }

    function renderDetailItems(items) {
      return '<div class="detail-grid">' + items.map(function (item) {
        return '<div class="detail-item"><span class="detail-label">' + escapeHtml(item.label) + ':</span> <span class="detail-value">' + formatValue(item.value) + '</span></div>';
      }).join('') + '</div>';
    }

    function renderSeniorDetailsHtml(senior) {
      const info = senior?.identifying_information || {};
      const name = info.name || {};
      const address = info.address || {};
      const family = senior?.family_composition || {};
      const education = senior?.education_hr_profile || {};
      const contacts = Array.isArray(info.contacts) ? info.contacts : [];
      const children = Array.isArray(family.children) ? family.children : [];
      const services = Array.isArray(senior?.community_service) ? senior.community_service : [];

      const sections = [];
      sections.push('<div class="detail-section"><h6>Personal Information</h6>' + renderDetailItems([
        { label: 'First Name', value: name.first_name },
        { label: 'Middle Name', value: name.middle_name },
        { label: 'Last Name', value: name.last_name },
        { label: 'Extension', value: name.extension },
        { label: 'Date of Birth', value: info.date_of_birth },
        { label: 'Age', value: info.age },
        { label: 'Gender', value: info.gender },
        { label: 'Marital Status', value: info.marital_status },
        { label: 'Place of Birth', value: Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : info.place_of_birth },
        { label: 'Barangay', value: address.barangay },
        { label: 'Purok', value: address.purok },
      ]) + '</div>');

      sections.push('<div class="detail-section"><h6>Identification Numbers</h6>' + renderDetailItems([
        { label: 'OSCA ID', value: info.osca_id_number },
        { label: 'GSIS / SSS', value: info.gsis_sss },
        { label: 'PhilHealth', value: info.philhealth },
        { label: 'SC Association / Org ID', value: info.sc_association_org_id_no },
        { label: 'TIN', value: info.tin },
        { label: 'Other Government ID', value: info.other_govt_id },
      ]) + '</div>');

      sections.push('<div class="detail-section"><h6>Contact Information</h6>' + (contacts.length ? contacts.map(function (contact, index) {
        return renderDetailItems([
          { label: 'Contact ' + (index + 1) + ' Type', value: contact.type },
          { label: 'Name', value: contact.name },
          { label: 'Relationship', value: contact.relationship },
          { label: 'Phone', value: contact.phone },
          { label: 'Email', value: contact.email },
        ]);
      }).join('<hr class="my-2">') : '<div class="detail-item">N/A</div>') + '</div>');

      sections.push('<div class="detail-section"><h6>Family Composition</h6>' + renderDetailItems([
        { label: 'Spouse', value: family?.spouse?.name },
        { label: 'Father Last Name', value: family?.father?.last_name },
        { label: 'Father First Name', value: family?.father?.first_name },
        { label: 'Father Middle Name', value: family?.father?.middle_name },
        { label: 'Father Extension', value: family?.father?.extension },
        { label: 'Mother Last Name', value: family?.mother?.last_name },
        { label: 'Mother First Name', value: family?.mother?.first_name },
        { label: 'Mother Middle Name', value: family?.mother?.middle_name },
      ]) + '</div>');

      sections.push('<div class="detail-section"><h6>Children</h6>' + (children.length ? children.map(function (child, index) {
        return renderDetailItems([
          { label: 'Child ' + (index + 1), value: child.full_name },
          { label: 'Occupation', value: child.occupation },
          { label: 'Income', value: child.income },
          { label: 'Age', value: child.age },
          { label: 'Working Status', value: child.working_status },
        ]);
      }).join('<hr class="my-2">') : '<div class="detail-item">No children recorded</div>') + '</div>');

      sections.push('<div class="detail-section"><h6>Education / HR Profile</h6>' + renderDetailItems([
        { label: 'Educational Attainment', value: education.educational_attainment },
        { label: 'Skills', value: education.skills },
        { label: 'Skill Other Text', value: education.skill_other_text },
        { label: 'Service / Business / Employment', value: info.service_business_employment },
        { label: 'Current Pension', value: info.current_pension },
        { label: 'Capability to Travel', value: info.capability_to_travel },
      ]) + '</div>');

      sections.push('<div class="detail-section"><h6>Community Service</h6>' + renderDetailItems([
        { label: 'Services', value: services.length ? services.join(', ') : null },
        { label: 'Other Community Service', value: senior?.community_service_other_text },
      ]) + '</div>');

      return sections.join('');
    }

    function openSeniorViewModal(senior) {
      viewCurrentStep = 0;

      const info = senior?.identifying_information || {};
      const name = info.name || {};
      const address = info.address || {};
      const family = senior?.family_composition || {};
      const education = senior?.education_hr_profile || {};
      const contacts = Array.isArray(info.contacts) ? info.contacts : [];
      const skills = viewParseList(education.skills);
      const communityService = viewParseList(senior?.community_service);
      const children = Array.isArray(family.children) ? family.children : [];

      viewSetText('viewFullName', getSeniorFullName(senior));
      viewSetText('viewAge', info.age ?? 'N/A');
      viewSetText('viewGender', info.gender || 'N/A');
      viewSetText('viewStatus', senior?.status || 'Active');
      viewSetText('viewBarangay', address.barangay || 'N/A');
      viewSetText('viewPurok', address.purok || 'N/A');
      viewSetText('viewMaritalStatus', info.marital_status || 'N/A');

      const seniorRecordId = getSeniorRecordId(senior);
      viewSetInput('viewResidentId', seniorRecordId);
      viewSetInput('viewFirstName', name.first_name || '');
      viewSetInput('viewMiddleName', name.middle_name || '');
      viewSetInput('viewLastName', name.last_name || '');
      viewSetInput('viewExtension', name.extension || '');
      viewSetInput('viewBirthday', info.date_of_birth || '');
      viewSetInput('viewAgeField', info.age ?? '');
      viewSetInput('viewBarangayField', address.barangay || '');
      viewSetInput('viewPurokField', address.purok || '');
      viewSetInput('viewGenderField', info.gender || '');
      viewSetInput('viewPlaceOfBirth', Array.isArray(info.place_of_birth) ? info.place_of_birth.join(', ') : (info.place_of_birth || ''));
      viewSetInput('viewCivilStatus', info.marital_status || '');
      viewSetInput('viewSpouseName', family?.spouse?.name || '');
      viewSetInput('viewOtherGovtId', info.other_govt_id || '');
      viewSetInput('viewFatherLastName', family?.father?.last_name || '');
      viewSetInput('viewFatherFirstName', family?.father?.first_name || '');
      viewSetInput('viewFatherMiddleName', family?.father?.middle_name || '');
      viewSetInput('viewFatherExtension', family?.father?.extension || '');
      viewSetInput('viewMotherLastName', family?.mother?.last_name || '');
      viewSetInput('viewMotherFirstName', family?.mother?.first_name || '');
      viewSetInput('viewMotherMiddleName', family?.mother?.middle_name || '');
      viewSetInput('viewOscaId', info.osca_id_number || '');
      viewSetInput('viewGsisId', info.gsis_id || info.gsis_sss || '');
      viewSetInput('viewSssId', info.sss_id || info.sss_number || '');
      viewSetInput('viewPhilhealthId', info.philhealth || '');
      viewSetInput('viewEducationalAttainment', education.educational_attainment || '');
      viewSetInput('viewServiceBusinessEmployment', info.service_business_employment || '');
      viewSetInput('viewCurrentPension', info.current_pension || '');
      viewSetInput('viewCapabilityToTravel', info.capability_to_travel || '');
      viewSetTextarea('viewSkills', skills.length ? skills.join(', ') : '');
      viewSetInput('viewSkillOtherText', education.skill_other_text || '');
      viewSetTextarea('viewCommunityService', communityService.length ? communityService.join(', ') : '');
      viewSetInput('viewCommunityServiceOtherText', senior?.community_service_other_text || '');

      viewRenderContacts(contacts);
      viewRenderChildren(children);
      currentViewSeniorId = seniorRecordId ? String(seniorRecordId) : '';
      currentViewSeniorData = senior;
      viewRenderSubmittedMetaFromRecord(senior);
      loadSeniorEditLogs(seniorRecordId);

      $('#viewSeniorModal').modal('show');
      viewShowStep(0);
    }

    // Global event handler for view/edit/archive buttons
    document.addEventListener('click', function (event) {
      const viewButton = event.target.closest('.view-btn');
      if (viewButton) {
        const senior = parseSeniorPayload(viewButton);
        if (senior) {
          openSeniorViewModal(senior);
        }
        return;
      }

      const editButton = event.target.closest('.edit-btn');
      if (editButton) {
        const senior = parseSeniorPayload(editButton);
        const rowResidentId = parseInt(editButton.getAttribute('data-senior-id') || '0', 10);
        if (senior) {
          openSeniorEditModal(senior, rowResidentId);
        }
        return;
      }

      const archiveButton = event.target.closest('.archive-btn');
      if (archiveButton) {
        const seniorId = archiveButton.getAttribute('data-senior-id');
        const currentStatus = archiveButton.getAttribute('data-status') || 'Active';
        const isArchived = currentStatus === 'Archived';
        const endpoint = isArchived ? '/unarchive-senior' : '/archive-senior';
        const actionLabel = isArchived ? 'unarchive' : 'archive';

        if (!seniorId) {
          Swal.fire({ icon: 'error', title: 'Missing ID', text: 'Senior Citizen ID not found.' });
          return;
        }

        Swal.fire({
          title: isArchived ? 'Unarchive Senior Citizen?' : 'Archive Senior Citizen?',
          input: isArchived ? 'text' : 'textarea',
          inputLabel: isArchived ? 'Reason for unarchiving (optional)' : 'Reason for archiving (optional)',
          inputPlaceholder: isArchived ? 'Reason for unarchiving' : 'Reason for archiving',
          showCancelButton: true,
          confirmButtonText: isArchived ? 'Unarchive' : 'Archive',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#0f766e'
        }).then(async function (result) {
          if (!result.isConfirmed) return;

          try {
            const response = await fetch(endpoint, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ senior_id: seniorId, reason: result.value || '' })
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
              throw new Error(data.message || `Failed to ${actionLabel} record`);
            }

            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || `Senior Citizen record ${actionLabel}d successfully`
            }).then(function () {
              window.location.reload();
            });
          } catch (error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: error.message || `An error occurred while ${actionLabel}ing the record.`
            });
          }
        });
      }
    });

    document.getElementById('printApplicationBtn').addEventListener('click', async function () {
      if (!currentViewSeniorId) {
        alert('Please open a Senior Citizen record before printing.');
        return;
      }
      try {
        const bytes = await buildSeniorApplicationPdf(currentViewSeniorData);
        const blob = new Blob([bytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
        setTimeout(function () { URL.revokeObjectURL(url); }, 30000);
      } catch (error) {
        console.error('Senior browser PDF generation failed:', error);
        alert('Failed to generate application PDF in browser: ' + ((error && error.message) ? error.message : String(error)));
      }
    });

    function validateEditSeniorPayload() {
      const requiredChecks = [
        { id: 'editFirstName', label: 'First Name' },
        { id: 'editLastName', label: 'Last Name' },
        { id: 'editBirthday', label: 'Birthday' },
        { id: 'editBarangay', label: 'Barangay' },
        { id: 'editPurok', label: 'Purok' },
        { id: 'editGender', label: 'Gender' },
        { id: 'editCivilStatus', label: 'Marital Status' }
      ];

      const missing = requiredChecks.filter(function (item) {
        return getInputValue(item.id) === '';
      }).map(function (item) {
        return item.label;
      });

      if (missing.length) {
        editCurrentStep = 0;
        editShowStep(0);
        Swal.fire({
          icon: 'warning',
          title: 'Required fields are missing',
          text: 'Please complete: ' + missing.join(', ')
        });
        return false;
      }

      return true;
    }

    function submitEditSeniorUpdate() {
      const form = document.getElementById('editSeniorForm');
      if (!form) return;
      if (!validateEditSeniorPayload()) {
        return;
      }

      const residentIdRaw = document.getElementById('editResidentId').value;
      const residentId = parseInt(residentIdRaw, 10);
      if (!Number.isFinite(residentId) || residentId <= 0) {
        Swal.fire({
          icon: 'error',
          title: 'Update failed',
          text: 'Resident ID is invalid. Please reopen the edit modal and try again.'
        });
        return;
      }

      const contacts = [];
      document.querySelectorAll('.edit-contact-row').forEach(function(row) {
        contacts.push({
          type: row.querySelector('.edit-contact-type')?.value || 'primary',
          name: row.querySelector('.edit-contact-name')?.value || '',
          relationship: row.querySelector('.edit-contact-relationship')?.value || '',
          phone: row.querySelector('.edit-contact-phone')?.value || '',
          email: row.querySelector('.edit-contact-email')?.value || ''
        });
      });

      const gsisId = document.getElementById('editGsisId').value.trim();
      const sssId = document.getElementById('editSssId').value.trim();
      const existingGsisSss = String(editCurrentSenior?.identifying_information?.gsis_sss || '').trim();
      const educationalAttainment = document.getElementById('editEducationalAttainment').value;
      const serviceBusinessEmployment = getPreferredValue('editServiceBusinessEmploymentStep5', 'editServiceBusinessEmployment');
      const currentPension = getPreferredValue('editCurrentPensionStep5', 'editCurrentPension');
      const capabilityToTravel = getPreferredValue('editCapabilityToTravelStep5', 'editCapabilityToTravel');
      const otherGovtId = getPreferredValue('editOtherId', 'editOtherGovtId');

      const nextBtn = document.getElementById('editNextBtn');
      if (nextBtn) {
        nextBtn.disabled = true;
      }

      let payload;
      try {
        payload = {
          residentId: residentId,
          first_name: document.getElementById('editFirstName').value.trim(),
          middle_name: document.getElementById('editMiddleName').value.trim(),
          last_name: document.getElementById('editLastName').value.trim(),
          extension: document.getElementById('editExtension').value.trim(),
          birthday: document.getElementById('editBirthday').value,
          place_of_birth: document.getElementById('editPlaceOfBirth').value.trim(),
          barangay: document.getElementById('editBarangay').value,
          purok: document.getElementById('editPurok').value,
          gender: document.getElementById('editGender').value,
          age: document.getElementById('editAge').value,
          marital_status: document.getElementById('editCivilStatus').value,
          spouse_name: document.getElementById('editSpouseName').value.trim(),
          service_business_employment: serviceBusinessEmployment,
          current_pension: currentPension,
          capability_to_travel: capabilityToTravel,
          other_govt_id: otherGovtId,
          contacts: contacts,
          father_last_name: document.getElementById('editFatherLastName').value.trim(),
          father_first_name: document.getElementById('editFatherFirstName').value.trim(),
          father_middle_name: document.getElementById('editFatherMiddleName').value.trim(),
          father_extension: document.getElementById('editFatherExtension').value.trim(),
          mother_last_name: document.getElementById('editMotherLastName').value.trim(),
          mother_first_name: document.getElementById('editMotherFirstName').value.trim(),
          mother_middle_name: document.getElementById('editMotherMiddleName').value.trim(),
          osca_id_number: document.getElementById('editOscaId').value.trim(),
          gsis_sss: [gsisId, sssId].filter(Boolean).join('/') || existingGsisSss,
          philhealth: document.getElementById('editPhilhealthId').value.trim(),
          educational_attainment: educationalAttainment ? [educationalAttainment] : [],
          skills: getCheckedValues('editSkills[]'),
          skill_other_text: document.getElementById('editSkillOtherText').value.trim(),
          community_service: getCheckedValues('editCommunityService[]'),
          community_service_other_text: document.getElementById('editCommunityServiceOtherText').value.trim(),
          // Preserve existing children when edit UI does not include child editing fields.
          children: Array.isArray(editCurrentSenior?.family_composition?.children) ? editCurrentSenior.family_composition.children : []
        };
      } catch (error) {
        if (nextBtn) {
          nextBtn.disabled = false;
        }
        Swal.fire({
          icon: 'error',
          title: 'Update failed',
          text: 'Unable to collect form values. Please reopen the edit modal and try again.'
        });
        return;
      }

      fetch('/update-senior', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(function(response) {
        return response.text().then(function(text) {
          let data = null;
          try {
            data = text ? JSON.parse(text) : {};
          } catch (error) {
            const preview = (text || '').slice(0, 120);
            throw new Error('Server returned invalid response. ' + preview);
          }

          if (!response.ok || !data.success) {
            throw new Error(data.message || data.error || 'Failed to update senior citizen.');
          }

          return data;
        });
      })
      .then(function(data) {
        $('#editSeniorModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Updated',
          text: data.message || 'Senior citizen updated successfully'
        }).then(function() {
          window.location.reload();
        });
      })
      .catch(function(error) {
        Swal.fire({
          icon: 'error',
          title: 'Update failed',
          text: error.message || 'Unable to update the record.'
        });
      })
      .finally(function() {
        if (nextBtn) {
          nextBtn.disabled = false;
        }
      });
    }

    function handleEditNextClick(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      const fieldsets = document.querySelectorAll('.edit-fieldset');
      if (editCurrentStep >= fieldsets.length - 1) {
        submitEditSeniorUpdate();
      } else {
        editMoveStep(1);
      }
    }

    // Single direct handler for modal footer button reliability.
    const editNextBtnEl = document.getElementById('editNextBtn');
    if (editNextBtnEl) {
      editNextBtnEl.addEventListener('click', handleEditNextClick);
    }

    // Initialize pagination
    updateSeniorPagination();
  </script>

  <!-- Socket.IO client and room join for staff pages -->
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    (function () {
      try {
        const host = window.location.hostname || 'localhost';
        const proto = window.location.protocol === 'https:' ? 'https' : 'http';
        // Try multiple candidate endpoints: same-origin, 3000, then 8080
        (function () {
          const portsToTry = [null, '3000', '8080'];
          let connected = false;
          function tryPort(index) {
            if (connected) return;
            if (index >= portsToTry.length) {
              console.warn('Socket.IO: unable to connect to any candidate ports');
              return;
            }
            const port = portsToTry[index];
            let socket;
            try {
              if (port === null) {
                socket = io({ transports: ['websocket', 'polling'], timeout: 5000 });
              } else {
                const url = proto + '://' + host + ':' + port;
                socket = io(url, { transports: ['websocket', 'polling'], timeout: 5000 });
              }
            } catch (err) {
              console.warn('Socket.IO instantiation failed for port', port, err);
              return tryPort(index + 1);
            }

            socket.on('connect', function () {
              connected = true;
              console.debug('socket connected', socket.id, 'via port', port || '(same origin)');
              try { socket.emit('join-room', 'staff'); } catch (e) {}
              window._socket = socket;
            });

            socket.on('connect_error', function (err) {
              console.debug('connect_error on port', port, err && err.message);
              try { socket.close && socket.close(); } catch (e) {}
              if (!connected) tryPort(index + 1);
            });

            socket.on('disconnect', function (reason) {
              console.debug('socket disconnected', reason);
            });

              socket.on('receive-alert', function (data) {
                try {
                  console.info('receive-alert', data);
                  const title = data && data.subject ? data.subject : ((data && data.from) ? ('Alert from ' + data.from) : 'Alert');
                  const message = data && data.message ? data.message : 'You have a new notification.';
                  if (window.Swal && typeof Swal.fire === 'function') {
                    Swal.fire({ title: title, text: String(message), icon: 'info', toast: true, position: 'top-end', timer: 8000 });
                  }
                  try { if (typeof window.loadNotificationsSenior === 'function') window.loadNotificationsSenior(); } catch (e) { console.warn(e); }
                } catch (err) { console.warn('alert handler error', err); }
              });
          }
          tryPort(0);
        })();
      } catch (error) {
        console.warn('Socket initialization failed', error);
      }
    })();
  </script>

  <div class="modal fade" id="birthdaysSeniorModal" tabindex="-1" role="dialog" aria-labelledby="birthdaysSeniorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 980px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="birthdaysSeniorModalTitle">Senior Birthdays</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 20px;">
          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
            <input id="bdaySearchSenior" class="form-control" placeholder="Search name/barangay/purok…" style="max-width:320px;">
            <select id="bdayRangeSenior" class="form-control" style="max-width:220px;">
              <option value="today">Today's birthdays</option>
              <option value="month" selected>This month's birthdays</option>
            </select>
            <select id="bdayBarangaySenior" class="form-control" style="max-width:260px;">
              <option value="">All Barangays</option>
              <?php foreach (($barangays ?? []) as $brgy => $puroks): ?>
                <option value="<?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
            <input id="bdayPurokSenior" class="form-control" placeholder="Purok (optional)" style="max-width:220px;">
          </div>

          <div style="overflow:auto;border:1px solid #e5e7eb;border-radius:10px;">
            <table class="table table-sm mb-0">
              <thead style="background:#f9fafb;">
                <tr>
                  <th style="width:34px;"><input type="checkbox" id="bdaySeniorSelectAll"></th>
                  <th>Name</th>
                  <th>Birthday</th>
                  <th>Barangay</th>
                  <th>Purok</th>
                </tr>
              </thead>
              <tbody id="birthdaysSeniorTableBody">
                <tr><td colspan="5" class="text-center">Loading...</td></tr>
              </tbody>
            </table>
          </div>
          <small id="birthdaysSeniorCount" style="display:block;margin-top:8px;color:#6b7280;">0 results</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="bdaySeniorSendSmsBtn" disabled>Send SMS</button>
          <button type="button" class="btn btn-outline-primary" id="bdaySeniorViewHistoryBtn">View SMS History</button>
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // UI helper for senior page notifications
    (function () {
      const badge = document.getElementById('notifBadgeSenior');
      const list = document.getElementById('notifListSenior');
      const dropdown = document.getElementById('notifDropdownSenior');
      const bell = document.getElementById('notifBellSenior');
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
          await fetch('/api/notifications/mark-read', {
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
      window.loadNotificationsSenior = async function () {
        try {
          const res = await fetch('/api/notifications', { credentials: 'same-origin' });
          const json = await res.json();
          notifications = (json && json.success && Array.isArray(json.data)) ? json.data : [];
          renderNotifications();
        } catch (error) {
          console.warn('Failed to load notifications', error);
        }
      };
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
            await window.loadNotificationsSenior();
            await markVisibleNotificationsRead();
          }
        });
        if (dropdown) {
          dropdown.addEventListener('click', function (e) { e.stopPropagation(); });
        }
        document.addEventListener('click', function () { if (dropdown) dropdown.style.display = 'none'; });
      }
      window.loadNotificationsSenior();
      setInterval(window.loadNotificationsSenior, 10000);
      document.addEventListener('visibilitychange', function () {
        if (!document.hidden) window.loadNotificationsSenior();
      });
    })();
  </script>

  <script>
    (function () {
      const btn = document.getElementById('birthdaysBtnSenior');
      const badge = document.getElementById('birthdaysBadgeSenior');
      const body = document.getElementById('birthdaysSeniorTableBody');
      const countEl = document.getElementById('birthdaysSeniorCount');
      const qEl = document.getElementById('bdaySearchSenior');
      const rangeEl = document.getElementById('bdayRangeSenior');
      const brgyEl = document.getElementById('bdayBarangaySenior');
      const purokEl = document.getElementById('bdayPurokSenior');
      const selectAllEl = document.getElementById('bdaySeniorSelectAll');
      const sendSmsBtnEl = document.getElementById('bdaySeniorSendSmsBtn');
      const viewHistoryBtnEl = document.getElementById('bdaySeniorViewHistoryBtn');
      const birthdayGreetingMessage = 'Happy Birthday! Greetings from Mayor Matthew Louis P. Malacon and Vice Mayor Marvin M. Malacon.';
      let cache = [];

      function escapeHtmlLocal(text) {
        return String(text == null ? '' : text).replace(/[&<>"']/g, function (c) {
          return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
      }

      function render() {
        if (!body) return;
        const q = (qEl && qEl.value ? qEl.value : '').trim().toLowerCase();
        const brgy = brgyEl ? brgyEl.value : '';
        const purokQ = (purokEl && purokEl.value ? purokEl.value : '').trim().toLowerCase();
        const filtered = cache.filter(function (row) {
          const name = String(row.full_name || '').toLowerCase();
          const barangay = String(row.barangay || '');
          const purok = String(row.purok || '');
          const matchesQ = !q || name.includes(q) || barangay.toLowerCase().includes(q) || purok.toLowerCase().includes(q);
          const matchesBrgy = !brgy || barangay === brgy;
          const matchesPurok = !purokQ || purok.toLowerCase().includes(purokQ);
          return matchesQ && matchesBrgy && matchesPurok;
        });

        if (!filtered.length) {
          body.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No birthdays found.</td></tr>';
        } else {
          body.innerHTML = filtered.map(function (row) {
            return '<tr>'
              + '<td><input type="checkbox" class="bday-senior-row" value="' + escapeHtmlLocal(row.id || '') + '"></td>'
              + '<td>' + escapeHtmlLocal(row.full_name || 'N/A') + '</td>'
              + '<td>' + escapeHtmlLocal(row.birth_date || 'N/A') + '</td>'
              + '<td>' + escapeHtmlLocal(row.barangay || 'N/A') + '</td>'
              + '<td>' + escapeHtmlLocal(row.purok || 'N/A') + '</td>'
              + '</tr>';
          }).join('');
        }
        if (countEl) countEl.textContent = filtered.length + ' results';
        if (selectAllEl) selectAllEl.checked = false;
        if (sendSmsBtnEl) sendSmsBtnEl.disabled = true;
        body.querySelectorAll('.bday-senior-row').forEach(function (cb) {
          cb.addEventListener('change', function () {
            const all = body.querySelectorAll('.bday-senior-row');
            const checked = body.querySelectorAll('.bday-senior-row:checked');
            if (selectAllEl) selectAllEl.checked = all.length > 0 && checked.length === all.length;
            if (sendSmsBtnEl) sendSmsBtnEl.disabled = checked.length === 0;
          });
        });
      }

      async function load(range) {
        if (!body) return;
        body.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
        try {
          const res = await fetch('/api/birthdays?type=senior&range=' + encodeURIComponent(range || 'month'), { credentials: 'same-origin' });
          const json = await res.json();
          cache = (json && json.success && Array.isArray(json.data)) ? json.data : [];
          render();
        } catch (e) {
          cache = [];
          body.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load birthdays.</td></tr>';
          if (countEl) countEl.textContent = '0 results';
          if (sendSmsBtnEl) sendSmsBtnEl.disabled = true;
        }
      }

      async function updateBadge() {
        try {
          const res = await fetch('/api/birthdays?type=senior&range=today', { credentials: 'same-origin' });
          const json = await res.json();
          const todayCount = (json && json.success && Array.isArray(json.data)) ? json.data.length : 0;
          if (badge) {
            badge.style.display = todayCount > 0 ? '' : 'none';
            badge.textContent = String(todayCount);
          }
        } catch (e) {
          if (badge) badge.style.display = 'none';
        }
      }

      if (btn) {
        btn.addEventListener('click', async function () {
          $('#birthdaysSeniorModal').modal('show');
          await load(rangeEl ? rangeEl.value : 'month');
        });
      }
      if (rangeEl) rangeEl.addEventListener('change', function () { load(rangeEl.value); });
      if (qEl) qEl.addEventListener('input', render);
      if (brgyEl) brgyEl.addEventListener('change', render);
      if (purokEl) purokEl.addEventListener('input', render);
      if (selectAllEl) {
        selectAllEl.addEventListener('change', function () {
          body.querySelectorAll('.bday-senior-row').forEach(function (cb) {
            cb.checked = selectAllEl.checked;
          });
          if (sendSmsBtnEl) {
            sendSmsBtnEl.disabled = body.querySelectorAll('.bday-senior-row:checked').length === 0;
          }
        });
      }
      if (sendSmsBtnEl) {
        sendSmsBtnEl.addEventListener('click', function () {
          const selected = Array.from(body.querySelectorAll('.bday-senior-row:checked'));
          const recipientsList = document.getElementById('recipientsList');
          recipientsList.innerHTML = '';

          selected.forEach(function (cb) {
            const row = cache.find(function (r) { return String(r.id || '') === String(cb.value || ''); }) || null;
            if (!row) return;
            const phone = String(row.mobile_number || '').trim();
            if (!phone) return;
            const fullName = String(row.full_name || 'N/A');
            const item = document.createElement('div');
            item.className = 'recipient-item';
            item.dataset.phone = phone;
            item.dataset.name = fullName;
            item.dataset.firstName = row.first_name || '';
            item.dataset.middleName = row.middle_name || '';
            item.dataset.lastName = row.last_name || '';
            item.dataset.barangay = row.barangay || '';
            item.dataset.purok = row.purok || '';
            item.dataset.recordId = String(row.id || '');
            item.innerHTML = '<strong>' + escapeHtmlLocal(fullName) + '</strong> <span class="text-muted">' + escapeHtmlLocal(phone) + '</span>';
            recipientsList.appendChild(item);
          });

          if (!recipientsList.children.length) {
            alert('No selected birthday records have a mobile number.');
            return;
          }

          const smsMessageEl = document.getElementById('smsMessage');
          const charCountEl = document.getElementById('charCount');
          if (smsMessageEl) smsMessageEl.value = birthdayGreetingMessage;
          if (charCountEl) charCountEl.textContent = String(birthdayGreetingMessage.length);

          $('#birthdaysSeniorModal').modal('hide');
          $('#assistanceModal').modal('show');
        });
      }
      if (viewHistoryBtnEl) {
        viewHistoryBtnEl.addEventListener('click', function () {
          $('#birthdaysSeniorModal').modal('hide');
          const historyBtn = document.getElementById('viewHistoryBtn');
          if (historyBtn) historyBtn.click();
        });
      }
      updateBadge();
    })();
  </script>
</body>
</html>
